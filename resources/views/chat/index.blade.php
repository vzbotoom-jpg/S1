@extends('layouts.app')
@section('title', 'NexusAI — Chat')

@section('content')
<div 
    x-data="{
    sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') ?? 'true'),
    activeChatId: {{ request()->route('id') ?? 'null' }},

    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
        localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
    }
}"
    class="flex h-screen bg-transparent overflow-hidden text-slate-200"
>
    {{-- ===================== SIDEBAR (CLAUDE STYLE) ===================== --}}
    <aside 
        class="relative flex flex-col h-full bg-[#1a1738]/95 backdrop-blur-xl border-r border-white/10 transition-all duration-300 ease-in-out z-40"
        :class="sidebarOpen ? 'w-72' : 'w-20'"
    >
        {{-- Bagian Atas: Brand & Toggle --}}
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
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition group">
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
                           class="block px-4 py-2 rounded-xl text-sm text-slate-400 hover:bg-white/5 hover:text-white transition truncate border border-transparent hover:border-white/5">
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
    {{-- ===================== MAIN CONTENT ===================== --}}
    <main class="flex-1 flex flex-col relative bg-transparent transition-all duration-300 mesh-bg">

        {{-- Floating Toggle (saat sidebar tertutup) --}}
       
        {{-- Welcome Section --}}
        <div class="flex-1 flex flex-col justify-center items-center p-8">
            <div class="text-center max-w-2xl w-full animate-fade-in">
                <h1 class="text-5xl font-bold gradient-text mb-4">
                    Halo, {{ auth()->user()->name ?? 'User' }}!
                </h1>
                <p class="text-xl text-slate-400 mb-16">
                    Tanya apa saja kepada AI. Saya siap membantu.
                </p>

                {{-- FIXED: onclick pakai getElementById, bukan closest('form') --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-2xl w-full">
                    @foreach([
                        ['💡', 'Jelaskan konsep machine learning dengan sederhana'],
                        ['✍️', 'Bantu saya menulis email profesional'],
                        ['🔧', 'Debug kode saya'],
                        ['📊', 'Analisa data']
                    ] as [$icon, $text])
                    <div 
                        onclick="
                            var ta = document.getElementById('chat-input');
                            ta.value = '{{ addslashes($text) }}';
                            ta.focus();
                            ta.style.height = 'auto';
                            ta.style.height = Math.min(ta.scrollHeight, 160) + 'px';
                        "
                        class="feature-card p-6 rounded-3xl cursor-pointer group"
                    >
                        <span class="text-4xl mb-5 block">{{ $icon }}</span>
                        <p class="text-slate-300 group-hover:text-white transition-colors">{{ $text }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="max-w-3xl w-full mx-auto px-6 pb-8">
            <div class="glass rounded-3xl p-2 shadow-2xl border border-white/10">
                <form action="{{ route('chat.store') }}" method="POST" class="flex flex-col" id="chat-form-index">
                    @csrf
                    <textarea 
                        rows="1" 
                        name="message"
                        id="chat-input"
                        required
                        class="w-full bg-transparent border-0 focus:ring-0 text-white placeholder-slate-400 resize-none px-6 py-5 text-[17px] leading-relaxed"
                        placeholder="Ketik judul anda di sini..."
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
            <p class="text-center text-xs text-slate-600 mt-3">NexusAI dapat membuat kesalahan. Pertimbangkan untuk memeriksa informasi penting.</p>
        </div>
    </main>
</div>

{{-- ===================== SEARCH MODAL ===================== --}}
<div 
    x-data="{ open: false, query: '' }"
    @open-search.window="open = true; $nextTick(() => $refs.searchInput && $refs.searchInput.focus())"
    x-show="open"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-start justify-center pt-[10vh] px-4"
    x-cloak
>
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>

    <div class="relative w-full max-w-lg bg-[#1a1738] border border-white/15 rounded-3xl shadow-2xl overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-4 border-b border-white/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input 
                x-ref="searchInput"
                x-model="query"
                type="text" 
                placeholder="Cari percakapan..."
                class="flex-1 bg-transparent text-white placeholder-slate-500 border-0 focus:ring-0 text-sm"
            >
            <kbd class="text-xs text-slate-600 border border-white/10 rounded px-2 py-0.5">Esc</kbd>
        </div>
        <div class="max-h-80 overflow-y-auto p-3">
            @forelse($conversations ?? [] as $conv)
                {{-- FIXED: conversations.show --}}
                <a 
                    href="{{ route('conversations.show', $conv->id) }}"
                    x-show="query === '' || '{{ strtolower($conv->title ?? 'percakapan baru') }}'.includes(query.toLowerCase())"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="text-sm text-slate-300 truncate">{{ $conv->title ?? 'Percakapan baru' }}</span>
                    <span class="ml-auto text-xs text-slate-600 shrink-0">{{ $conv->updated_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-center text-sm text-slate-600 py-8">Belum ada percakapan.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const textarea  = document.getElementById('chat-input');
    const form      = document.getElementById('chat-form-index');
    
    const btnText   = document.getElementById('btn-text');
    const btnIcon   = document.getElementById('btn-icon');
    const btnSpinner = document.getElementById('btn-spinner');

    // Auto resize textarea
    textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 160) + 'px';
    });

    // Ctrl+Enter untuk kirim
    textarea.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (this.value.trim()) form.requestSubmit();
        }
    });

    // Cegah submit kosong & tampilkan loading
    form.addEventListener('submit', function (e) {
        if (!textarea.value.trim()) {
            e.preventDefault();
            textarea.focus();
            return;
        }
        submitBtn.disabled = true;
        btnText.textContent = 'Memproses...';
        btnIcon.classList.add('hidden');
        btnSpinner.classList.remove('hidden');
    });

    @if(request('prompt'))
        setTimeout(() => textarea.focus(), 300);
    @endif
});
</script>
@endsection