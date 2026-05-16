@extends('layouts.admin')
@section('title', 'Detail Pengguna — NexusAI Admin')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-3xl mx-auto">

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

        {{-- Hero Card --}}
        <div class="relative glass rounded-3xl border border-white/10 overflow-hidden mb-5">
            <div class="h-1.5 w-full bg-gradient-to-r from-violet-600 via-indigo-500 to-purple-600"></div>

            <div class="relative px-8 pt-7 pb-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 mb-8">

                    {{-- Avatar --}}
                    <div class="relative shrink-0">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-xl">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-1.5 -right-1.5 w-5 h-5 bg-green-400 rounded-full border-2 border-[#0d0b1a]"></span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                            @if($user->is_admin ?? false)
                                <span class="px-2.5 py-0.5 text-xs font-semibold bg-red-500/15 border border-red-500/30 text-red-400 rounded-full">Admin</span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-semibold bg-violet-500/15 border border-violet-500/30 text-violet-300 rounded-full">Member</span>
                            @endif
                        </div>
                        <p class="text-slate-400 text-sm">{{ $user->email }}</p>
                        <p class="text-slate-600 text-xs mt-0.5">Bergabung sejak {{ $user->created_at->format('d M Y') }}</p>
                    </div>

                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-violet-900/30 shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Pengguna
                    </a>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white/5 rounded-2xl p-4 text-center border border-white/5">
                        <p class="text-3xl font-bold text-violet-400">{{ $user->conversations()->count() }}</p>
                        <p class="text-xs text-slate-500 mt-1">Percakapan</p>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center border border-white/5">
                        <p class="text-3xl font-bold text-indigo-400">{{ $user->conversations()->withCount('messages')->get()->sum('messages_count') }}</p>
                        <p class="text-xs text-slate-500 mt-1">Total Pesan</p>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center border border-white/5">
                        <p class="text-3xl font-bold text-pink-400">{{ number_format($user->created_at->diffInDays(now())) }}</p>
                        <p class="text-xs text-slate-500 mt-1">Hari Bergabung</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
            <div class="glass rounded-2xl border border-white/10 p-5">
                <p class="text-xs text-slate-500 uppercase tracking-widest font-medium mb-4">Email</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm text-white font-medium truncate">{{ $user->email }}</p>
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center gap-1 mt-1 text-xs text-green-400">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 mt-1 text-xs text-yellow-400">Belum terverifikasi</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="glass rounded-2xl border border-white/10 p-5">
                <p class="text-xs text-slate-500 uppercase tracking-widest font-medium mb-4">Role</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-white font-medium">{{ $user->is_admin ? 'Administrator' : 'Member' }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $user->is_admin ? 'Akses penuh ke admin panel' : 'Akses standar' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="glass rounded-2xl border border-white/10 p-5">
            <p class="text-xs text-slate-500 uppercase tracking-widest font-medium mb-4">Aksi Admin</p>
            <div class="flex flex-wrap gap-3">
                <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition {{ $user->is_admin ? 'bg-red-500/10 text-red-400 hover:bg-red-500/20 border border-red-500/20' : 'bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 border border-blue-500/20' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $user->is_admin ? 'Cabut Hak Admin' : 'Jadikan Admin' }}
                    </button>
                </form>

                @if($user->id !== auth()->user()->id)
                <form method="POST" action="{{ route('admin.users.delete', $user) }}" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium bg-red-500/10 text-red-400 hover:bg-red-500/20 border border-red-500/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Pengguna
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
