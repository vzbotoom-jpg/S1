{{-- Component: chat-box --}}
{{-- Reusable chat input box. Props: $conversationId (optional) --}}

<div x-data="chatBox({{ $conversationId ?? 'null' }})" class="w-full">
    <div class="glass rounded-2xl px-4 py-3 focus-within:border-brand-500/50 transition-colors">
        <div class="flex items-end gap-3">
            <textarea
                x-ref="textarea"
                x-model="message"
                rows="1"
                placeholder="Ketik pesan Anda..."
                maxlength="4000"
                @keydown.enter.prevent="if(!$event.shiftKey) send()"
                @input="resize()"
                class="flex-1 bg-transparent text-white placeholder-slate-500 resize-none outline-none text-sm leading-relaxed max-h-40"
            ></textarea>

            <div class="flex items-center gap-2 flex-shrink-0">
                {{-- Character count --}}
                <span class="text-xs text-slate-600" x-text="message.length + '/4000'" x-show="message.length > 100"></span>

                {{-- Send button --}}
                <button @click="send()"
                    :disabled="!message.trim() || loading"
                    class="w-9 h-9 rounded-xl bg-brand-500 hover:bg-brand-600 disabled:opacity-40 disabled:cursor-not-allowed text-white flex items-center justify-center transition-all active:scale-95">
                    <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <p class="text-xs text-slate-600 text-center mt-2">
        Enter untuk kirim · Shift+Enter untuk baris baru
    </p>
</div>

<script>
function chatBox(conversationId) {
    return {
        message: '',
        loading: false,
        conversationId: conversationId,

        resize() {
            const el = this.$refs.textarea;
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight, 160) + 'px';
        },

        async send() {
            const msg = this.message.trim();
            if (!msg || this.loading) return;

            this.loading = true;
            this.message = '';
            this.$nextTick(() => this.resize());

            // Dispatch event so parent can handle the message
            this.$dispatch('message-sent', { message: msg, conversationId: this.conversationId });

            this.loading = false;
        }
    }
}
</script>