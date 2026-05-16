@extends('layouts.admin')
@section('title', 'Edit Pengguna — NexusAI Admin')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-2xl mx-auto">

        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 rounded-xl transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Pengguna
            </a>
        </div>

        <div class="mb-8">
            <h1 class="font-display text-2xl md:text-3xl font-bold text-white">Edit Pengguna</h1>
            <p class="text-slate-400 mt-1 text-sm">Ubah informasi pengguna: {{ $user->name }}</p>
        </div>

        @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded-xl bg-green-500/10 border border-green-500/30 text-green-400 text-sm">
            ✓ {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf @method('PUT')

            <div class="glass rounded-2xl p-6 space-y-5">
                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition text-sm"
                        placeholder="Nama lengkap">
                    @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition text-sm"
                        placeholder="email@contoh.com">
                    @error('email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                {{-- Password (opsional) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Password Baru <span class="text-slate-500">(opsional)</span></label>
                    <input type="password" name="password"
                        class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition text-sm"
                        placeholder="Kosongkan jika tidak ingin mengubah">
                    @error('password')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition text-sm"
                        placeholder="Ulangi password baru">
                </div>

                {{-- Role Admin --}}
                <div class="flex items-center justify-between pt-2 border-t border-dark-600/40">
                    <div>
                        <p class="text-sm font-medium text-slate-300">Role Administrator</p>
                        <p class="text-xs text-slate-500 mt-0.5">Berikan akses penuh ke panel admin</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_admin" value="1" class="sr-only peer"
                            {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-dark-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-brand-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.users.index') }}"
                   class="px-6 py-3 rounded-xl border border-dark-600 text-slate-400 hover:text-white hover:border-dark-500 transition text-sm font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-semibold transition-all active:scale-[0.98] text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
