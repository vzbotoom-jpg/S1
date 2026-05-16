@extends('layouts.app')
@section('title', $conversation->title ?? 'NexusAI — Chat')

@section('content')
<div 
    x-data="{
        sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') ?? 'true'),
        activeChatId: {{ $conversation->id ?? 'null' }},

        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
        }
    }"
    class="flex h-screen bg-transparent overflow-hidden text-slate-200"
>
    {{-- ===================== SIDEBAR (SAMA DENGAN INDEX) ===================== --}}
    <aside 
        class="relative flex flex-col h-full bg-[#1a1738]/95 backdrop-blur-xl border-r border-white/10 transition-all duration-300 ease-in-out z-40"
        :class="sidebarOpen ? 'w-72' : 'w-20'"
    >
        <div class="flex items-center justify-between p-4 h-16 border-b border-white/10 shrink-0">
    <div x-show="sidebarOpen" x-transition.opacity class="flex items-center space-x-2 ml-1">
        {{-- Logo icon sama seperti welcome --}}
        <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
            </svg>
        </div>
        {{-- Tulisan NexusAI dengan gradient sama seperti welcome --}}
        <span class="text-xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">NexusAI</span>
    </div>

    <button @click="toggleSidebar" class="flex items-center gap-3 px-2 py-2 rounded-2xl hover:bg-white/10 transition group">   
        <div class="w-7 h-7 border border-slate-400 rounded-md flex items-center justify-center shrink-0">
            <div class="w-[2px] h-4 bg-slate-400 rounded-full"></div>
        </div>
    </button>
</div>
        {{-- Bagian Tengah: Navigasi Utama --}}
        <div class="flex flex-col flex-1 overflow-hidden">
            <div class="px-3 py-4 space-y-2">
                {{-- New Chat Button --}}
                <a href="{{ route('chat.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 transition group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span x-show="sidebarOpen" class="text-sm font-medium">New chat</span>
                </a>

                {{-- Search --}}
                <button @click="$dispatch('open-search')" class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl hover:bg-white/10 transition group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 group-hover:text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span x-show="sidebarOpen" class="text-sm font-medium text-slate-400 group-hover:text-white">Search</span>
                </button>

                {{-- History Link --}}
                <a href="{{ route('conversations.index') }}" class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl hover:bg-white/10 transition group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 group-hover:text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span x-show="sidebarOpen" class="text-sm font-medium text-slate-400 group-hover:text-white">All Chats</span>
                </a>
            </div>

            {{-- Riwayat Chat (Scrollable Area) --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar px-3 mt-2" x-show="sidebarOpen" x-transition.opacity>
                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Recents</p>
                <div class="space-y-1">
                    @forelse($conversations ?? [] as $conv)
                        <a href="{{ route('chat.show', $conv->id) }}" 
                           class="block px-4 py-2 rounded-xl text-sm transition truncate border border-transparent 
                           {{ isset($conversation) && $conversation->id == $conv->id 
                              ? 'bg-violet-500/20 text-white border-violet-500/30' 
                              : 'text-slate-400 hover:bg-white/5 hover:text-white hover:border-white/5' }}">
                            {{ $conv->title ?? 'Untitled Chat' }}
                        </a>
                    @empty
                        <p class="text-[11px] text-slate-600 px-4 italic">No recent chats</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Bagian Bawah: Profile --}}
        <div class="p-4 border-t border-white/10 shrink-0" x-data="{ userMenuOpen: false }">
            <div class="relative">
                <button @click="userMenuOpen = !userMenuOpen" 
                        class="flex items-center gap-3 w-full p-2 rounded-2xl hover:bg-white/10 transition group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shrink-0 text-white font-bold shadow-lg shadow-purple-500/20">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0 text-left" x-show="sidebarOpen">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-[10px] text-violet-400 font-bold uppercase tracking-tight">Pro Member</p>
                    </div>
                </button>

                {{-- Dropdown Logout --}}
                <div 
                    x-show="userMenuOpen"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    @click.outside="userMenuOpen = false"
                    class="absolute bottom-full left-0 w-56 mb-2 bg-[#1a1738] border border-white/10 rounded-2xl overflow-hidden shadow-2xl z-50"
                    x-cloak
                >
                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-white/10 transition">
                        Profil
                    </a>
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-white/10 transition">
                        Pengaturan
                    </a>
                    <div class="border-t border-white/10 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-red-400 hover:bg-red-500/10 transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- ===================== MAIN CHAT AREA ===================== --}}
    <main class="flex-1 flex flex-col relative mesh-bg overflow-hidden">

        {{-- Chat Header --}}
        <div class="h-16 border-b border-white/10 flex items-center justify-between px-6 bg-[#1a1738]/70 backdrop-blur-md shrink-0">
            <h1 class="font-medium text-white truncate">{{ $conversation->title ?? 'Percakapan Baru' }}</h1>
            <div class="flex items-center gap-2">
                {{-- FIXED: conversations.index untuk kembali ke list --}}
                <a href="{{ route('conversations.index') }}" 
                   class="p-2 text-slate-500 hover:text-slate-300 hover:bg-white/10 rounded-xl transition text-xs flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span class="hidden sm:inline">Riwayat</span>
                </a>
                {{-- FIXED: chat.destroy sesuai web.php --}}
                <form method="POST" action="{{ route('chat.destroy', $conversation->id) }}" 
                      onsubmit="return confirm('Hapus percakapan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" 
                            class="p-2 text-slate-500 hover:text-red-400 hover:bg-red-500/10 rounded-xl transition" 
                            title="Hapus percakapan">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Messages Area --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto py-6 space-y-4">
            @forelse($messages ?? [] as $message)
                @include('chat.partials.message', ['message' => $message])
            @empty
                <div class="flex flex-col items-center justify-center h-full text-center py-20">
                    <div class="text-6xl mb-6 opacity-60">👋</div>
                    <p class="text-slate-400 text-lg">Belum ada pesan dalam percakapan ini.</p>
                    <p class="text-slate-500 mt-2">Ketik pesan untuk memulai.</p>
                </div>
            @endforelse

            {{-- Typing indicator --}}
            <div id="typing-indicator" class="flex justify-center px-6 hidden">
                <div class="w-full max-w-4xl flex items-start gap-4">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div class="py-3">
                        <div class="flex gap-1.5 items-center h-5">
                            <div class="w-2 h-2 rounded-full bg-violet-400 typing-dot" style="animation-delay:0ms"></div>
                            <div class="w-2 h-2 rounded-full bg-violet-400 typing-dot" style="animation-delay:200ms"></div>
                            <div class="w-2 h-2 rounded-full bg-violet-400 typing-dot" style="animation-delay:400ms"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="shrink-0 px-6 pb-6 pt-2">
            <div class="max-w-3xl mx-auto">
                <div class="glass rounded-3xl p-2 border border-white/10 shadow-xl">
                    {{-- 
                        FIXED: route chat.message.store sesuai web.php
                        di web.php: Route::post('/{conversation}/message', ...)->name('message.store')
                        dengan prefix 'chat.' maka nama fullnya adalah 'chat.message.store'
                    --}}
                    <form id="chat-form" 
                          action="{{ route('chat.message.store', $conversation->id) }}" 
                          method="POST">
                        @csrf
                        
                        <textarea 
                            id="chat-input"
                            name="message"
                            rows="1"
                            placeholder="Ketik pesan Anda di sini..."
                            required
                            class="w-full bg-transparent border-0 border-white/10 focus:ring-0 text-white placeholder-slate-400 resize-none px-6 py-5 text-base leading-relaxed"
                        ></textarea>

                        <div class="flex items-center justify-end px-3 pb-3">
                            <button 
                            type="submit" 
                            id="send-btn"
                            class="w-9 h-9 rounded-full bg-violet-600 hover:bg-violet-500 disabled:opacity-30 disabled:cursor-not-allowed flex items-center justify-center transition-all duration-200 shadow-md"
                            title="Kirim (Enter)"
                            >
                            
                            {{-- Arrow up icon --}}
                            <svg id="send-icon" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            
                            {{-- Spinner --}}
                            <svg id="send-spinner" class="w-4 h-4 text-white animate-spin hidden" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Typing animation + Markdown styles --}}
<style>
@keyframes typing-bounce {
    0%, 80%, 100% { transform: translateY(0); opacity: 0.4; }
    40% { transform: translateY(-6px); opacity: 1; }
}
.typing-dot { animation: typing-bounce 1.2s ease-in-out infinite; }

.prose-ai h1, .prose-ai h2, .prose-ai h3 { color: #e2e8f0; font-weight: 600; margin: 1rem 0 0.5rem; }
.prose-ai h1 { font-size: 1.5em; }
.prose-ai h2 { font-size: 1.25em; }
.prose-ai h3 { font-size: 1.1em; }
.prose-ai p  { margin-bottom: 0.85rem; line-height: 1.8; font-size: 1rem; }
.prose-ai ul, .prose-ai ol { margin-left: 1.5rem; margin-bottom: 0.85rem; }
.prose-ai ul { list-style-type: disc; }
.prose-ai ol { list-style-type: decimal; }
.prose-ai li { margin-bottom: 0.4rem; font-size: 1rem; line-height: 1.8; }
.prose-ai code:not(pre code) {
    background: rgba(139, 92, 246, 0.2);
    border: 1px solid rgba(139, 92, 246, 0.3);
    border-radius: 4px;
    padding: 1px 6px;
    font-size: 0.875em;
    font-family: 'Fira Code', 'Courier New', monospace;
    color: #c4b5fd;
}
.prose-ai pre {
    background: #0f0e1a;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 1rem;
    margin: 0.75rem 0;
    overflow-x: auto;
    position: relative;
}
.prose-ai pre code {
    font-family: 'Fira Code', 'Courier New', monospace;
    font-size: 0.875em;
    line-height: 1.6;
    color: #e2e8f0;
}
.prose-ai blockquote {
    border-left: 3px solid #7c3aed;
    padding-left: 1rem;
    color: #94a3b8;
    margin: 0.75rem 0;
    font-style: italic;
}
.prose-ai table { width: 100%; border-collapse: collapse; margin: 0.75rem 0; font-size: 0.9em; }
.prose-ai th, .prose-ai td { border: 1px solid rgba(255,255,255,0.1); padding: 0.5rem 0.75rem; text-align: left; }
.prose-ai th { background: rgba(139,92,246,0.15); font-weight: 600; }
.prose-ai strong { color: #e2e8f0; font-weight: 600; }
.prose-ai a { color: #818cf8; text-decoration: underline; }
.prose-ai hr { border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 1rem 0; }

.code-copy-btn {
    position: absolute; top: 8px; right: 8px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.1);
    color: #94a3b8; border-radius: 6px;
    padding: 3px 8px; font-size: 11px;
    cursor: pointer; transition: all 0.2s;
}
.code-copy-btn:hover { background: rgba(255,255,255,0.15); color: #fff; }
</style>

{{-- marked.js untuk render markdown --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.6/marked.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form              = document.getElementById('chat-form');
    const textarea          = document.getElementById('chat-input');
    const messagesContainer = document.getElementById('chat-messages');
    const typingIndicator   = document.getElementById('typing-indicator');
    const sendBtn           = document.getElementById('send-btn');
    
    const sendIcon          = document.getElementById('send-icon');
    const sendSpinner       = document.getElementById('send-spinner');

    // Konfigurasi marked.js
    if (typeof marked !== 'undefined') {
        marked.setOptions({ breaks: true, gfm: true });
    }

    function renderMarkdown(text) {
        if (typeof marked === 'undefined') return escapeHtml(text).replace(/\n/g, '<br>');
        return marked.parse(text);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    function addCopyButtons(container) {
        container.querySelectorAll('pre').forEach(pre => {
            if (pre.querySelector('.code-copy-btn')) return;
            const btn = document.createElement('button');
            btn.className = 'code-copy-btn';
            btn.textContent = 'Copy';
            btn.addEventListener('click', () => {
                const code = pre.querySelector('code');
                navigator.clipboard.writeText(code ? code.innerText : pre.innerText).then(() => {
                    btn.textContent = 'Disalin!';
                    setTimeout(() => { btn.textContent = 'Copy'; }, 2000);
                });
            });
            pre.appendChild(btn);
        });
    }

    function setLoading(loading) {
        sendBtn.disabled = loading;
        sendIcon.classList.toggle('hidden', loading);
        sendSpinner.classList.toggle('hidden', !loading);
        typingIndicator.classList.toggle('hidden', !loading);
        if (loading) messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function appendErrorBubble(msg) {
        const el = document.createElement('div');
        el.className = 'flex justify-center';
        el.innerHTML = `
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-2xl px-5 py-3 max-w-md text-center">
                ⚠️ ${escapeHtml(msg)}
            </div>`;
        messagesContainer.appendChild(el);
        scrollToBottom();
    }

    // Render markdown pada pesan AI yang sudah ada saat halaman load
    document.querySelectorAll('.ai-message-content').forEach(el => {
        el.innerHTML = renderMarkdown(el.dataset.raw || el.textContent);
        el.classList.add('prose-ai');
        addCopyButtons(el);
    });

    // Auto resize textarea
    textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 180) + 'px';
    });

    // Ctrl+Enter untuk kirim
    textarea.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (this.value.trim()) form.dispatchEvent(new Event('submit', { cancelable: true }));
        }
    });

    // FIXED: AJAX submit — append bubble AI ke DOM, tanpa reload
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const messageText = textarea.value.trim();
        if (!messageText) return;

        // 1. Tampilkan pesan user
        const userBubble = document.createElement('div');
        userBubble.className = 'flex justify-center px-6';
        userBubble.innerHTML = `
            <div class="w-full max-w-4xl flex justify-end">
                <div class="bg-violet-600/90 text-white rounded-2xl rounded-tr-sm w-fit max-w-[75%] px-5 py-3.5 text-base leading-relaxed break-words whitespace-pre-wrap">
                    ${escapeHtml(messageText)}
                </div>
            </div>`;
        messagesContainer.appendChild(userBubble);

        // 2. Reset textarea & loading state
        textarea.value = '';
        textarea.style.height = 'auto';
        setLoading(true);

        try {
            const formData = new FormData();
            formData.append('message', messageText);
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            setLoading(false);

            if (!response.ok) {
                const errData = await response.json().catch(() => ({}));
                throw new Error(errData.message || 'Server error: ' + response.status);
            }

            const data = await response.json();

            // 3. Append bubble AI
            const aiText = data.message ?? data.reply ?? data.content ?? data.response ?? null;

            if (aiText) {
                const aiBubble = document.createElement('div');
                aiBubble.className = 'flex justify-center px-6';
                const rendered = renderMarkdown(aiText);
                aiBubble.innerHTML = `
                    <div class="w-full max-w-4xl flex items-start gap-4">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shrink-0 mt-1">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 py-1">
                            <div class="ai-message-content prose-ai text-slate-200 text-base leading-relaxed">${rendered}</div>
                            <div class="flex items-center gap-3 mt-2">
                                <button onclick="copyAiMessage(this)" 
                                        class="text-xs text-slate-600 hover:text-slate-400 transition flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    Salin
                                </button>
                            </div>
                        </div>
                    </div>`;
                messagesContainer.appendChild(aiBubble);
                addCopyButtons(aiBubble);

            } else if (data.error) {
                appendErrorBubble(data.error);
            } else {
                appendErrorBubble('Respons tidak dikenali dari server.');
            }

        } catch (error) {
            setLoading(false);
            console.error('Chat error:', error);
            appendErrorBubble(error.message || 'Gagal terhubung ke server. Silakan coba lagi.');
        } finally {
            scrollToBottom();
            textarea.focus();
        }
    });

    // Scroll ke bawah saat load
    scrollToBottom();
});

function copyAiMessage(btn) {
    const content = btn.closest('.flex-1').querySelector('.ai-message-content');
    if (!content) return;
    navigator.clipboard.writeText(content.innerText).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = `<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Disalin!`;
        setTimeout(() => { btn.innerHTML = original; }, 2000);
    });
}
</script>
@endsection
