@extends('layouts.app')
@section('title', 'Edit Profil — NexusAI')

@section('content')
<div class="min-h-screen py-10 px-4">
    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('profile.show') }}"
               class="p-2.5 text-slate-400 hover:text-white hover:bg-white/10 rounded-xl transition border border-white/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Edit Profil</h1>
                <p class="text-slate-500 text-sm mt-0.5">Perbarui informasi akun kamu.</p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 px-5 py-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm rounded-2xl flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 px-5 py-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-2xl flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Avatar Preview --}}
        <div class="glass rounded-3xl border border-white/10 p-5 mb-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white text-xl font-bold shadow-lg shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-white font-semibold">{{ $user->name }}</p>
                <p class="text-slate-400 text-sm">{{ $user->email }}</p>
            </div>
            <div class="ml-auto">
                <span class="px-2.5 py-1 text-xs font-semibold bg-violet-500/15 border border-violet-500/30 text-violet-300 rounded-full">
                    Pro Member
                </span>
            </div>
        </div>

        {{-- ===== FORM: Update Info ===== --}}
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PATCH')

            <div class="glass rounded-3xl border border-white/10 p-6 mb-4">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-white text-sm">Informasi Akun</h2>
                        <p class="text-xs text-slate-500">Nama dan email yang ditampilkan</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-violet-500 focus:bg-violet-500/5 transition @error('name') border-red-500/50 @enderror"
                            placeholder="Nama lengkap..."
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-violet-500 focus:bg-violet-500/5 transition @error('email') border-red-500/50 @enderror"
                            placeholder="email@example.com"
                        >
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mb-5">
                <a href="{{ route('profile.show') }}"
                   class="px-6 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 transition bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400 hover:text-white">
                    Batal
                </a>
                <button type="submit"
                        class="btn-primary px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2 transition shadow-lg shadow-violet-900/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

        {{-- ===== FORM: Ganti Password ===== --}}
        <form method="POST" action="{{ route('profile.password') }}">
            @csrf @method('PATCH')

            <div class="glass rounded-3xl border border-white/10 p-6 mb-4">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-white text-sm">Ganti Password</h2>
                        <p class="text-xs text-slate-500">Gunakan password yang kuat dan unik</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Password Saat Ini</label>
                        <input
                            type="password"
                            name="current_password"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-yellow-500 focus:bg-yellow-500/5 transition @error('current_password') border-red-500/50 @enderror"
                            placeholder="••••••••"
                        >
                        @error('current_password')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Password Baru</label>
                            <input
                                type="password"
                                name="password"
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-yellow-500 focus:bg-yellow-500/5 transition @error('password') border-red-500/50 @enderror"
                                placeholder="Min. 8 karakter"
                            >
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-yellow-500 focus:bg-yellow-500/5 transition"
                                placeholder="Ulangi password baru"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mb-5">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2 transition bg-yellow-500/10 hover:bg-yellow-500/20 border border-yellow-500/20 hover:border-yellow-500/40 text-yellow-400 hover:text-yellow-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Ganti Password
                </button>
            </div>
        </form>

        {{-- ===== Danger Zone ===== --}}
        <div class="glass rounded-3xl border border-red-500/20 p-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-9 h-9 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-red-400 text-sm">Zona Berbahaya</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Menghapus akun bersifat permanen dan tidak dapat dipulihkan.</p>
                </div>
            </div>
            <button
                type="button"
                onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-400 hover:bg-red-500/10 border border-red-500/20 hover:border-red-500/40 rounded-xl transition"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Akun Saya
            </button>
        </div>

    </div>
</div>

{{-- Modal Hapus Akun --}}
<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm px-4">
    <div class="bg-[#12101e] border border-red-500/20 rounded-2xl p-6 w-full max-w-sm shadow-2xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-semibold">Hapus Akun?</h3>
                <p class="text-slate-500 text-xs">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>

        <p class="text-slate-400 text-sm mb-4">Masukkan password kamu untuk konfirmasi penghapusan akun beserta seluruh datanya.</p>

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf @method('DELETE')
            <input
                type="password"
                name="password"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-red-500 transition mb-4"
                placeholder="Password kamu..."
                required
                autofocus
            >
            @error('password')
                <p class="mb-3 text-xs text-red-400">{{ $message }}</p>
            @enderror

            <div class="flex gap-2 justify-end">
                <button type="button"
                        onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-500 rounded-xl transition">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.add('hidden');
});
</script>
@endsection