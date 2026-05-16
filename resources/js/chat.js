/**
 * chat.js
 * Logika utama AI Chatbot:
 * - Kirim pesan & terima respons
 * - Render Markdown (marked.js)
 * - Realtime via Laravel Echo (Pusher)
 * - Auto-resize textarea
 * - Copy message, typing indicator, scroll-to-bottom
 */

import { marked }   from 'marked';
import DOMPurify    from 'dompurify';

/* ============================================================
   Konfigurasi Marked (Markdown parser)
   ============================================================ */
marked.setOptions({
    gfm:     true,   // GitHub Flavored Markdown
    breaks:  true,   // Newline → <br>
    mangle:  false,
    headerIds: false,
});

// Custom renderer: tambah header pada blok kode
const renderer = new marked.Renderer();
renderer.code = (code, language) => {
    const lang = language ? DOMPurify.sanitize(language) : 'text';
    const escaped = code
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');

    return `<pre>
        <div class="code-header">
            <span>${lang}</span>
            <button class="btn-bubble-action" onclick="ChatApp.copyCode(this)" title="Salin kode">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:11px;height:11px">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166
                             1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75
                             0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11
                             1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0
                             1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057
                             1.907-2.185a48.208 48.208 0 0 1 1.927-.184"/>
                </svg>
                Salin
            </button>
        </div>
        <code class="language-${lang}">${escaped}</code>
    </pre>`;
};
marked.use({ renderer });

/* ============================================================
   State Aplikasi
   ============================================================ */
const state = {
    conversationId:  null,
    isLoading:       false,
    totalTokens:     0,
    echoChannel:     null,
    pendingMessageId: null,   // ID pesan user yang menunggu respons AI
};

/* ============================================================
   DOM References
   ============================================================ */
const $ = (id) => document.getElementById(id);

const els = {
    messagesContainer: () => $('chat-messages'),
    messagesInner:     () => $('messages-inner'),
    messageInput:      () => $('message-input'),
    btnSend:           () => $('btn-send'),
    welcome:           () => $('chat-welcome'),
    tokenCounter:      () => $('token-counter'),
    btnClear:          () => $('btn-clear-chat'),
    chatTitle:         () => $('chat-title'),
    convList:          () => $('conversation-list'),
    sidebar:           () => $('chat-sidebar'),
    sidebarOverlay:    () => $('sidebar-overlay'),
    searchInput:       () => $('search-conversations'),
};

/* ============================================================
   Public API — window.ChatApp
   ============================================================ */
window.ChatApp = {

    /**
     * Inisialisasi — dipanggil saat DOM siap.
     */
    init() {
        this._bindTextarea();
        this._bindSendButton();
        this._bindSidebar();
        this._bindSearch();
        this._renderExistingMarkdown();
        console.log('[ChatApp] Siap.');
    },

    /* ----------------------------------------------------------
       Kirim Pesan
    ---------------------------------------------------------- */
    async sendMessage() {
        const input   = els.messageInput();
        const message = input.value.trim();

        if (! message || state.isLoading) return;

        // Tampilkan pesan user secara langsung (optimistic UI)
        this._hideWelcome();
        this._appendUserMessage(message);
        this._showTypingIndicator();
        this._setLoading(true);
        input.value = '';
        this._resizeTextarea(input);

        try {
            const res = await fetch(window.ChatConfig.routes.chat, {
                method:  'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'Accept':        'application/json',
                    'X-CSRF-TOKEN':  window.ChatConfig.csrfToken,
                },
                body: JSON.stringify({
                    message,
                    conversation_id: state.conversationId ?? undefined,
                }),
            });

            const data = await res.json();

            if (! res.ok) {
                throw new Error(data.message ?? `Error ${res.status}`);
            }

            // Set conversation ID jika baru
            if (! state.conversationId) {
                state.conversationId = data.conversation_id;
                this._subscribeToChannel(data.conversation_id);
                this._addConversationToSidebar(
                    data.conversation_id,
                    message.substring(0, 60)
                );
            }

            state.pendingMessageId = data.message?.id ?? null;

            // Tampilkan clear button & update header
            this._updateHeader(message);

        } catch (err) {
            console.error('[ChatApp] sendMessage error:', err);
            this._removeTypingIndicator();
            this._appendError(err.message ?? 'Gagal mengirim pesan.');
            this._setLoading(false);
        }
    },

    /* ----------------------------------------------------------
       Muat conversation yang sudah ada
    ---------------------------------------------------------- */
    async loadConversation(id) {
        if (state.conversationId === id) return;

        // Update active state sidebar
        document.querySelectorAll('.conversation-item').forEach(el => {
            el.classList.toggle('active', parseInt(el.dataset.id) === id);
        });

        // Unsubscribe dari channel lama
        if (state.echoChannel) {
            window.Echo.leave(`conversation.${state.conversationId}`);
            state.echoChannel = null;
        }

        state.conversationId = id;
        state.totalTokens    = 0;

        this._clearMessages();
        this._setLoading(true);

        try {
            const url = window.ChatConfig.routes.history.replace(':id', id);
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json' },
            });

            if (! res.ok) throw new Error('Gagal memuat riwayat.');

            const { data } = await res.json();

            if (data.length === 0) {
                this._showWelcome();
            } else {
                this._hideWelcome();
                data.forEach(msg => this._appendMessage(msg));
                this._updateTokenCounter();
            }

            this._subscribeToChannel(id);

            // Update title
            const item = document.querySelector(`.conversation-item[data-id="${id}"]`);
            if (item) {
                const title = item.dataset.title ?? 'Percakapan';
                els.chatTitle().textContent = title;
            }

            els.btnClear().style.display = 'flex';

        } catch (err) {
            console.error('[ChatApp] loadConversation error:', err);
            this._appendError(err.message);
        } finally {
            this._setLoading(false);
            this._scrollToBottom();
        }
    },

    /* ----------------------------------------------------------
       Hapus conversation
    ---------------------------------------------------------- */
    async deleteConversation(id, btn) {
        if (! confirm('Hapus percakapan ini?')) return;

        try {
            const url = window.ChatConfig.routes.deleteConv.replace(':id', id);
            const res = await fetch(url, {
                method:  'DELETE',
                headers: {
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': window.ChatConfig.csrfToken,
                },
            });

            if (! res.ok) throw new Error('Gagal menghapus.');

            // Hapus dari sidebar
            btn.closest('.conversation-item').remove();

            // Reset jika yang dihapus sedang aktif
            if (state.conversationId === id) {
                state.conversationId = null;
                this.clearChat();
            }

        } catch (err) {
            alert(err.message);
        }
    },

    /* ----------------------------------------------------------
       Bersihkan chat (tanpa hapus dari DB)
    ---------------------------------------------------------- */
    clearChat() {
        state.conversationId = null;
        state.totalTokens    = 0;
        state.pendingMessageId = null;

        if (state.echoChannel) {
            window.Echo.leave(`conversation.${state.conversationId}`);
            state.echoChannel = null;
        }

        this._clearMessages();
        this._showWelcome();
        this._setLoading(false);

        els.chatTitle().textContent = window.ChatConfig.botName ?? 'AI Assistant';
        els.btnClear().style.display = 'none';
        els.tokenCounter().style.display = 'none';

        document.querySelectorAll('.conversation-item.active').forEach(el => {
            el.classList.remove('active');
        });
    },

    /* ----------------------------------------------------------
       Gunakan suggestion card
    ---------------------------------------------------------- */
    useSuggestion(text) {
        const input = els.messageInput();
        input.value = text;
        this._resizeTextarea(input);
        els.btnSend().disabled = false;
        input.focus();
    },

    /* ----------------------------------------------------------
       Copy pesan ke clipboard
    ---------------------------------------------------------- */
    async copyMessage(btn) {
        const bubble = btn.closest('.message-bubble');
        const text   = bubble?.dataset.raw ?? bubble?.textContent?.trim() ?? '';

        try {
            await navigator.clipboard.writeText(text);
            btn.classList.add('copy-success');
            setTimeout(() => btn.classList.remove('copy-success'), 2000);
        } catch {
            prompt('Salin teks di bawah:', text);
        }
    },

    /* ----------------------------------------------------------
       Copy kode dari code block
    ---------------------------------------------------------- */
    async copyCode(btn) {
        const code = btn.closest('pre')?.querySelector('code')?.textContent ?? '';
        try {
            await navigator.clipboard.writeText(code);
            btn.textContent = 'Tersalin!';
            btn.classList.add('copy-success');
            setTimeout(() => {
                btn.textContent = 'Salin';
                btn.classList.remove('copy-success');
            }, 2000);
        } catch {
            prompt('Salin kode di bawah:', code);
        }
    },

    /* ============================================================
       Private Helpers
    ============================================================ */

    _bindTextarea() {
        const input = els.messageInput();
        const btn   = els.btnSend();

        input.addEventListener('input', () => {
            btn.disabled = input.value.trim() === '';
            this._resizeTextarea(input);
        });

        input.addEventListener('keydown', (e) => {
            // Enter tanpa Shift = kirim
            if (e.key === 'Enter' && ! e.shiftKey) {
                e.preventDefault();
                if (! btn.disabled) this.sendMessage();
            }
        });
    },

    _bindSendButton() {
        els.btnSend().addEventListener('click', () => this.sendMessage());
    },

    _bindSidebar() {
        const toggleBtn = $('btn-sidebar-toggle');
        const newChatBtn = $('btn-new-chat');
        const overlay   = els.sidebarOverlay();
        const sidebar   = els.sidebar();

        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('visible');
        });

        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('visible');
        });

        newChatBtn?.addEventListener('click', () => this.clearChat());
    },

    _bindSearch() {
        const searchInput = els.searchInput();
        if (! searchInput) return;

        searchInput.addEventListener('input', () => {
            const q = searchInput.value.toLowerCase();
            document.querySelectorAll('.conversation-item').forEach(item => {
                const title = (item.dataset.title ?? '').toLowerCase();
                item.style.display = title.includes(q) ? '' : 'none';
            });
        });
    },

    /**
     * Subscribe ke private channel conversation untuk menerima respons AI.
     */
    _subscribeToChannel(conversationId) {
        if (state.echoChannel) return;

        state.echoChannel = window.Echo
            .private(`conversation.${conversationId}`)
            .listen('.ai.response.received', (e) => {
                this._removeTypingIndicator();
                this._appendMessage(e.message);
                this._setLoading(false);
                state.totalTokens += e.message.tokens_used ?? 0;
                this._updateTokenCounter();
            })
            .listen('.message.sent', () => {
                // Tampilkan typing indicator jika belum ada
                if (! $('typing-indicator')) {
                    this._showTypingIndicator();
                }
            });

        console.log(`[Echo] Subscribe ke conversation.${conversationId}`);
    },

    /**
     * Render Markdown pada elemen .markdown-content yang sudah ada di DOM
     * (untuk pesan yang di-load dari server-side Blade).
     */
    _renderExistingMarkdown() {
        document.querySelectorAll('.markdown-content').forEach(el => {
            const raw = el.textContent ?? '';
            el.innerHTML = DOMPurify.sanitize(marked.parse(raw));
        });
    },

    /**
     * Append pesan user (optimistic UI).
     */
    _appendUserMessage(content) {
        const html = this._buildBubbleHtml({
            role:    'user',
            content,
            is_user: true,
            created_at: new Date().toISOString(),
        });
        els.messagesInner().insertAdjacentHTML('beforeend', html);
        this._scrollToBottom();
    },

    /**
     * Append pesan dari API response.
     */
    _appendMessage(msg) {
        const html = this._buildBubbleHtml(msg);
        els.messagesInner().insertAdjacentHTML('beforeend', html);

        // Render Markdown pada pesan AI yang baru ditambahkan
        const last = els.messagesInner().lastElementChild;
        if (msg.role === 'assistant') {
            last.querySelectorAll('.markdown-content').forEach(el => {
                const raw = el.textContent ?? '';
                el.innerHTML = DOMPurify.sanitize(marked.parse(raw));
            });
        }

        this._scrollToBottom();
        this._updateTokenCounter();
    },

    /**
     * Build HTML string untuk bubble pesan.
     */
    _buildBubbleHtml(msg) {
        const isUser   = msg.is_user || msg.role === 'user';
        const initials = window.ChatConfig.userInitials ?? 'U';
        const botName  = window.ChatConfig.botName ?? 'AI';
        const timeAgo  = this._timeAgo(msg.created_at);

        const copyBtn = `
            <div class="bubble-actions">
                <button class="btn-bubble-action" title="Salin"
                        onclick="ChatApp.copyMessage(this)">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                         style="width:11px;height:11px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03
                                 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75
                                 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332
                                 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907
                                 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1
                                 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208
                                 48.208 0 0 1 1.927-.184"/>
                    </svg>
                </button>
            </div>`;

        const tokenBadge = (! isUser && msg.tokens_used)
            ? `<span class="token-badge">${msg.tokens_used} token</span>`
            : '';

        const avatarHtml = isUser
            ? `<div class="message-avatar user-avatar-msg">${initials}</div>`
            : `<div class="message-avatar ai-avatar">
                   <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        style="width:14px;height:14px">
                       <path stroke-linecap="round" stroke-linejoin="round"
                             d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25
                                12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813
                                2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5
                                4.5 0 0 0-3.09 3.09Z"/>
                   </svg>
               </div>`;

        const bubbleClass = isUser ? 'bubble-user' : 'bubble-ai';
        const rowClass    = isUser ? 'user-row'    : 'ai-row';
        const senderName  = isUser ? window.ChatConfig.userName : botName;

        const contentHtml = isUser
            ? `<div style="white-space:pre-wrap">${this._escapeHtml(msg.content)}</div>`
            : `<div class="markdown-content">${this._escapeHtml(msg.content)}</div>`;

        return `
        <div class="message-group" data-message-id="${msg.id ?? ''}">
            <div class="message-row ${rowClass}">
                ${avatarHtml}
                <div class="message-content-wrap">
                    <div class="message-sender">
                        ${this._escapeHtml(senderName)} ${tokenBadge}
                    </div>
                    <div class="message-bubble ${bubbleClass}"
                         data-raw="${this._escapeAttr(msg.content)}">
                        ${copyBtn}
                        ${contentHtml}
                    </div>
                    <div class="message-time">${timeAgo}</div>
                </div>
            </div>
        </div>`;
    },

    _showTypingIndicator() {
        if ($('typing-indicator')) return;
        const html = `
        <div class="typing-indicator" id="typing-indicator">
            <div class="typing-avatar">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                     style="width:14px;height:14px">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25
                             12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5
                             4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/>
                </svg>
            </div>
            <div class="typing-bubble">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>`;
        els.messagesInner().insertAdjacentHTML('beforeend', html);
        this._scrollToBottom();
    },

    _removeTypingIndicator() {
        $('typing-indicator')?.remove();
    },

    _appendError(message) {
        const html = `
        <div class="chat-alert error" style="max-width:780px;margin:8px auto;padding:8px 20px">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                 style="width:14px;height:14px;flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73
                         0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898
                         0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
            ${this._escapeHtml(message)}
        </div>`;
        els.messagesInner().insertAdjacentHTML('beforeend', html);
        this._scrollToBottom();
    },

    _setLoading(loading) {
        state.isLoading = loading;
        const btn = els.btnSend();
        if (loading) {
            btn.disabled = true;
            btn.classList.add('loading');
            btn.innerHTML = `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                  stroke-width="2" style="width:15px;height:15px">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993
                         0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0
                         1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
            </svg>`;
        } else {
            btn.classList.remove('loading');
            btn.disabled = els.messageInput().value.trim() === '';
            btn.innerHTML = `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                  stroke-width="2" style="width:15px;height:15px">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0
                         1 3.27 20.875L5.999 12Zm0 0h7.5"/>
            </svg>`;
        }
    },

    _resizeTextarea(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 160) + 'px';
    },

    _scrollToBottom(smooth = true) {
        const container = els.messagesContainer();
        if (! container) return;
        container.scrollTo({
            top:      container.scrollHeight,
            behavior: smooth ? 'smooth' : 'instant',
        });
    },

    _hideWelcome() {
        const w = els.welcome();
        if (w) w.style.display = 'none';
    },

    _showWelcome() {
        const w = els.welcome();
        if (w) w.style.display = '';
    },

    _clearMessages() {
        const inner = els.messagesInner();
        // Hapus semua kecuali welcome screen
        Array.from(inner.children).forEach(child => {
            if (child.id !== 'chat-welcome') child.remove();
        });
    },

    _updateHeader(firstMessage) {
        els.btnClear().style.display = 'flex';
        const title = firstMessage.length > 40
            ? firstMessage.substring(0, 40) + '…'
            : firstMessage;
        els.chatTitle().textContent = title;
    },

    _updateTokenCounter() {
        const el = els.tokenCounter();
        if (! el) return;
        if (state.totalTokens > 0) {
            el.style.display = '';
            el.textContent   = `${state.totalTokens.toLocaleString('id')} token`;
        }
    },

    _addConversationToSidebar(id, title) {
        const list  = els.convList();
        const empty = document.getElementById('empty-conversations');
        if (empty) empty.remove();

        // Tandai item lain tidak aktif
        list.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));

        const item = document.createElement('div');
        item.className    = 'conversation-item active';
        item.dataset.id   = id;
        item.dataset.title = title;
        item.onclick = () => ChatApp.loadConversation(id);

        item.innerHTML = `
            <div class="conversation-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                     style="width:13px;height:13px">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227
                             1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133
                             a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233
                             2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394
                             48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746
                             2.25 5.14 2.25 6.741v6.018Z"/>
                </svg>
            </div>
            <div class="conversation-info">
                <div class="conversation-title">${this._escapeHtml(title)}</div>
                <div class="conversation-meta">Baru saja</div>
            </div>
            <div class="conversation-actions">
                <button class="btn-conv-action" title="Hapus"
                        onclick="event.stopPropagation(); ChatApp.deleteConversation(${id}, this)">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                         style="width:12px;height:12px">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107
                                 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244
                                 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456
                                 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114
                                 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18
                                 -.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037
                                 -2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                </button>
            </div>`;

        // Insert setelah label section
        const label = list.querySelector('.sidebar-section-label');
        label
            ? label.insertAdjacentElement('afterend', item)
            : list.prepend(item);
    },

    /* ----------------------------------------------------------
       Utilities
    ---------------------------------------------------------- */

    _escapeHtml(str) {
        if (typeof str !== 'string') return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    },

    _escapeAttr(str) {
        if (typeof str !== 'string') return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    },

    _timeAgo(isoString) {
        if (! isoString) return '';
        try {
            const date = new Date(isoString);
            const diff = Math.floor((Date.now() - date) / 1000);
            if (diff < 5)   return 'Baru saja';
            if (diff < 60)  return `${diff} detik lalu`;
            if (diff < 3600) return `${Math.floor(diff / 60)} menit lalu`;
            return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        } catch {
            return '';
        }
    },
};

/* ============================================================
   Boot saat DOM siap
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => ChatApp.init());