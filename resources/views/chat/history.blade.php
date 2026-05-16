@extends('layouts.app')
@section('title', 'Riwayat Chat — NexusAI')

@section('content')
<div class="min-h-screen flex flex-col">

    {{-- ===================== HEADER ===================== --}}
    <header class="glass border-b border-white/10 sticky top-0 z-10 backdrop-blur-xl">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center gap-4">

            <a href="{{ route('chat.index') }}"
               class="p-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-xl transition shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>

            <div>
                <h1 class="font-bold text-white text-xl">Riwayat Chat</h1>
                <p class="text-xs text-slate-500">
                    @if($conversations instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $conversations->total() }} percakapan
                    @else
                        {{ count($conversations ?? []) }} percakapan
                    @endif
                </p>
            </div>

            <div class="ml-auto flex items-center gap-2 sm:gap-3">

                {{-- Search bar (desktop) --}}
                <div class="relative hidden sm:block">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        id="search-history"
                        placeholder="Cari percakapan..."
                        class="bg-white/5 border border-white/10 rounded-xl pl-9 pr-4 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 w-52 transition"
                    >
                </div>

                {{-- Hapus Semua --}}
                @if(($conversations ?? collect())->count() > 0)
                <form
                    method="POST"
                    action="{{ route('conversations.bulk-destroy') }}"
                    onsubmit="return confirm('Hapus SEMUA percakapan? Tindakan ini tidak dapat dibatalkan.')"
                >
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="flex items-center gap-2 px-3 py-2 text-sm text-red-400 hover:bg-red-500/10 border border-red-500/20 hover:border-red-500/40 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span class="hidden sm:inline">Hapus Semua</span>
                    </button>
                </form>
                @endif

                <a href="{{ route('chat.index') }}"
                   class="flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl px-4 py-2 text-sm font-medium transition shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Chat Baru
                </a>
            </div>
        </div>

        {{-- Search bar (mobile) --}}
        <div class="sm:hidden max-w-4xl mx-auto px-6 pb-4">
            <div class="relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    type="text"
                    id="search-history-mobile"
                    placeholder="Cari percakapan..."
                    class="w-full bg-white/5 border border-white/10 rounded-xl pl-9 pr-4 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-violet-500 transition"
                >
            </div>
        </div>
    </header>

    {{-- ===================== MAIN ===================== --}}
    <main class="flex-1 max-w-4xl mx-auto w-full px-6 py-8">

        {{-- Flash success --}}
        @if(session('success'))
            <div class="mb-6 px-5 py-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm rounded-2xl flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter count --}}
        <p id="filter-count" class="text-xs text-slate-600 mb-4 hidden"></p>

        {{-- ===================== CONVERSATION LIST ===================== --}}
        <div id="conversations-list">
            @forelse($conversations ?? [] as $conv)
                <div
                    class="conversation-item glass rounded-2xl p-5 mb-4 border border-white/10 hover:border-violet-500/30 transition-all group animate-fade-in"
                    data-title="{{ strtolower($conv->title ?? 'percakapan baru') }}"
                    data-preview="{{ strtolower(Str::limit(optional($conv->messages->last())->content ?? '', 120)) }}"
                >
                    <div class="flex items-start gap-4">

                        {{-- Icon --}}
                        <div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center flex-shrink-0 group-hover:bg-violet-500/20 transition">
                            @if($conv->is_starred ?? false)
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3 mb-1">
                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                    <h3
                                        class="font-semibold text-white group-hover:text-violet-300 transition truncate conv-title"
                                        data-id="{{ $conv->id }}"
                                    >
                                        {{ $conv->title ?? 'Percakapan baru' }}
                                    </h3>
                                    @if($conv->is_starred ?? false)
                                        <span class="text-yellow-400 text-xs">★</span>
                                    @endif
                                </div>
                                <span class="text-xs text-slate-600 flex-shrink-0">{{ $conv->updated_at->format('d M Y, H:i') }}</span>
                            </div>

                            <p class="text-xs text-slate-500 mb-2">
                                {{ $conv->messages_count ?? $conv->messages->count() }} pesan
                            </p>

                            @if(optional($conv->messages->last())->content)
                                <p class="text-sm text-slate-400 line-clamp-2">
                                    {{ Str::limit($conv->messages->last()->content, 120) }}
                                </p>
                            @endif
                        </div>

                        {{-- Actions: Buka + Dropdown ··· --}}
                        <div class="flex items-center gap-2 flex-shrink-0 relative">

                            {{-- Buka --}}
                            <a href="{{ route('conversations.show', $conv->id) }}"
                               class="px-3 py-1.5 rounded-lg bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 text-violet-300 text-xs font-medium transition whitespace-nowrap">
                                Buka →
                            </a>

                            {{-- Tombol ··· --}}
                            <button
                                onclick="toggleDropdown(event, {{ $conv->id }})"
                                class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/10 transition"
                                title="Opsi lainnya"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 7a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 7a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/>
                                </svg>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div
                                id="dropdown-{{ $conv->id }}"
                                class="dropdown-menu hidden absolute right-0 top-9 z-50 w-48 bg-[#1a1730] border border-white/10 rounded-xl shadow-2xl py-1 overflow-hidden"
                            >
                                {{-- Star / Unstar --}}
                                <button
                                    onclick="toggleStar(event, {{ $conv->id }})"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition"
                                >
                                    <svg class="w-4 h-4 text-yellow-400" fill="{{ ($conv->is_starred ?? false) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    {{ ($conv->is_starred ?? false) ? 'Unstar' : 'Star' }}
                                </button>

                                {{-- Rename --}}
                                <button
                                    onclick="openRenameModal(event, {{ $conv->id }}, '{{ addslashes($conv->title ?? 'Percakapan baru') }}')"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition"
                                >
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Rename
                                </button>

                                {{-- Add to Project --}}
                                <button
                                    onclick="openProjectModal(event, {{ $conv->id }})"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition"
                                >
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                                    </svg>
                                    Add to project
                                </button>

                                <hr class="border-white/10 my-1">

                                {{-- Delete --}}
                                <form
                                    method="POST"
                                    action="{{ route('conversations.destroy', $conv->id) }}"
                                    onsubmit="return confirm('Hapus percakapan ini?')"
                                >
                                    @csrf @method('DELETE')
                                    <button
                                        type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20" id="empty-state">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/5 mb-5">
                        <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8"/>
                        </svg>
                    </div>
                    <p class="text-slate-400 font-medium mb-2">Belum ada percakapan</p>
                    <p class="text-slate-600 text-sm mb-6">Mulai chat pertama Anda sekarang!</p>
                    <a href="{{ route('chat.index') }}"
                       class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold px-6 py-3 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Mulai Chat
                    </a>
                </div>
            @endforelse

            {{-- No results dari pencarian --}}
            <div id="no-results" class="hidden text-center py-16">
                <div class="text-4xl mb-4 opacity-50">🔍</div>
                <p class="text-slate-400 font-medium">Tidak ada hasil untuk pencarian ini.</p>
                <button onclick="clearSearch()"
                        class="mt-3 text-sm text-violet-400 hover:text-violet-300 transition">
                    Hapus pencarian
                </button>
            </div>
        </div>

        {{-- Pagination --}}
        @if($conversations instanceof \Illuminate\Pagination\LengthAwarePaginator && $conversations->hasPages())
            <div class="mt-6">
                {{ $conversations->links() }}
            </div>
        @endif
    </main>
</div>

{{-- ===================== MODAL RENAME ===================== --}}
<div id="rename-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-[#1a1730] border border-white/10 rounded-2xl p-6 w-full max-w-sm mx-4 shadow-2xl">
        <h3 class="text-white font-semibold mb-1">Rename Percakapan</h3>
        <p class="text-slate-500 text-xs mb-4">Masukkan nama baru untuk percakapan ini.</p>
        <input
            type="text"
            id="rename-modal-input"
            class="w-full bg-white/5 border border-violet-500/40 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-violet-500 transition mb-4"
            placeholder="Nama baru..."
        >
        <div class="flex gap-2 justify-end">
            <button onclick="closeRenameModal()"
                    class="px-4 py-2 text-sm text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition">
                Batal
            </button>
            <button onclick="submitRenameModal()"
                    class="px-4 py-2 text-sm text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition">
                Simpan
            </button>
        </div>
    </div>
</div>

{{-- ===================== MODAL ADD TO PROJECT ===================== --}}
<div id="project-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-[#1a1730] border border-white/10 rounded-2xl p-6 w-full max-w-sm mx-4 shadow-2xl">
        <h3 class="text-white font-semibold mb-1">Add to Project</h3>
        <p class="text-slate-500 text-xs mb-4">Pilih project untuk percakapan ini.</p>

        {{-- Input nama project baru --}}
        <div class="mb-4">
            <input
                type="text"
                id="project-name-input"
                class="w-full bg-white/5 border border-violet-500/40 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-violet-500 transition"
                placeholder="Nama project..."
            >
        </div>

        <div class="flex gap-2 justify-end">
            <button onclick="closeProjectModal()"
                    class="px-4 py-2 text-sm text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition">
                Batal
            </button>
            <button onclick="submitProject()"
                    class="px-4 py-2 text-sm text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition">
                Simpan
            </button>
        </div>
    </div>
</div>

<script>
// ===================== DROPDOWN =====================
let activeDropdownId = null;

function toggleDropdown(e, id) {
    e.stopPropagation();
    const menu = document.getElementById('dropdown-' + id);

    // Tutup semua dropdown lain
    document.querySelectorAll('.dropdown-menu').forEach(m => {
        if (m !== menu) m.classList.add('hidden');
    });

    menu.classList.toggle('hidden');
    activeDropdownId = menu.classList.contains('hidden') ? null : id;
}

// Klik di luar → tutup semua dropdown
document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
    activeDropdownId = null;
});

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
    activeDropdownId = null;
}

// ===================== STAR =====================
async function toggleStar(e, id) {
    e.stopPropagation();
    closeAllDropdowns();

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    try {
        const res = await fetch(`/conversations/${id}/star`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            }
        });
        if (res.ok) location.reload();
        else alert('Gagal mengubah status bintang.');
    } catch (err) {
        console.error('Star error:', err);
    }
}

// ===================== RENAME MODAL =====================
let renameTargetId = null;

function openRenameModal(e, id, currentTitle) {
    e.stopPropagation();
    closeAllDropdowns();
    renameTargetId = id;
    document.getElementById('rename-modal-input').value = currentTitle;
    document.getElementById('rename-modal').classList.remove('hidden');
    setTimeout(() => {
        const input = document.getElementById('rename-modal-input');
        input.focus();
        input.select();
    }, 100);
}

function closeRenameModal() {
    document.getElementById('rename-modal').classList.add('hidden');
    renameTargetId = null;
}

async function submitRenameModal() {
    const input    = document.getElementById('rename-modal-input');
    const newTitle = input.value.trim();
    if (!newTitle || !renameTargetId) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    try {
        const res = await fetch(`/conversations/${renameTargetId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ title: newTitle })
        });

        if (res.ok) {
            // Update DOM tanpa reload
            const titleEl = document.querySelector(`.conv-title[data-id="${renameTargetId}"]`);
            if (titleEl) titleEl.textContent = newTitle;

            const item = titleEl?.closest('.conversation-item');
            if (item) item.dataset.title = newTitle.toLowerCase();

            closeRenameModal();
        } else {
            const err = await res.json().catch(() => ({}));
            alert(err.message || 'Gagal menyimpan nama.');
        }
    } catch (err) {
        console.error('Rename error:', err);
        alert('Terjadi kesalahan jaringan.');
    }
}

// Enter / Escape di modal rename
document.getElementById('rename-modal-input')?.addEventListener('keydown', e => {
    if (e.key === 'Enter')  { e.preventDefault(); submitRenameModal(); }
    if (e.key === 'Escape') { closeRenameModal(); }
});

// Klik backdrop rename modal
document.getElementById('rename-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRenameModal();
});

// ===================== PROJECT MODAL =====================
let projectTargetId = null;

function openProjectModal(e, id) {
    e.stopPropagation();
    closeAllDropdowns();
    projectTargetId = id;
    document.getElementById('project-name-input').value = '';
    document.getElementById('project-modal').classList.remove('hidden');
    setTimeout(() => document.getElementById('project-name-input').focus(), 100);
}

function closeProjectModal() {
    document.getElementById('project-modal').classList.add('hidden');
    projectTargetId = null;
}

async function submitProject() {
    const projectName = document.getElementById('project-name-input').value.trim();
    if (!projectName || !projectTargetId) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    try {
        const res = await fetch(`/conversations/${projectTargetId}/project`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ project_name: projectName })
        });

        if (res.ok) {
            closeProjectModal();
            location.reload();
        } else {
            const err = await res.json().catch(() => ({}));
            alert(err.message || 'Gagal menyimpan project.');
        }
    } catch (err) {
        console.error('Project error:', err);
        alert('Terjadi kesalahan jaringan.');
    }
}

// Klik backdrop project modal
document.getElementById('project-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeProjectModal();
});

// ===================== SEARCH REAL-TIME =====================
function initSearch() {
    const inputIds = ['search-history', 'search-history-mobile'];
    const inputs   = inputIds.map(id => document.getElementById(id)).filter(Boolean);
    const items    = document.querySelectorAll('.conversation-item');
    const noResults   = document.getElementById('no-results');
    const filterCount = document.getElementById('filter-count');

    inputs.forEach(input => {
        input.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();

            inputs.forEach(i => { if (i !== this) i.value = this.value; });

            let visible = 0;
            items.forEach(item => {
                const title   = item.dataset.title   || '';
                const preview = item.dataset.preview || '';
                const match   = q === '' || title.includes(q) || preview.includes(q);
                item.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            const hasItems = items.length > 0;
            noResults.classList.toggle('hidden', visible > 0 || q === '' || !hasItems);

            if (q && visible > 0) {
                filterCount.textContent = `${visible} percakapan ditemukan untuk "${this.value}"`;
                filterCount.classList.remove('hidden');
            } else {
                filterCount.classList.add('hidden');
            }
        });
    });
}

function clearSearch() {
    ['search-history', 'search-history-mobile'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.value = ''; el.dispatchEvent(new Event('input')); }
    });
}

document.addEventListener('DOMContentLoaded', initSearch);
</script>
@endsection