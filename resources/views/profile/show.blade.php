@extends('layouts.app')
@section('title', 'Profil — NexusAI')

@section('content')
<div class="min-h-screen py-10 px-4">
    <div class="max-w-3xl mx-auto">

        @if(session('success'))
            <div class="mb-6 px-5 py-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm rounded-2xl flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- ── Back Button ── --}}
<div class="mb-4">
    <a href="{{ route('chat.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 text-sm text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 rounded-xl transition-all">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Chat
    </a>
</div>



        {{-- ── Hero Card ── --}}
        <div class="relative glass rounded-3xl border border-white/10 overflow-hidden mb-5">
            <div class="absolute -top-16 -right-16 w-64 h-64 rounded-full bg-violet-600/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -left-10 w-48 h-48 rounded-full bg-indigo-600/15 blur-3xl pointer-events-none"></div>
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
                                <span class="px-2.5 py-0.5 text-xs font-semibold bg-violet-500/15 border border-violet-500/30 text-violet-300 rounded-full">Pro Member</span>
                            @endif
                        </div>
                        <p class="text-slate-400 text-sm">{{ $user->email }}</p>
                        <p class="text-slate-600 text-xs mt-0.5">Bergabung sejak {{ $stats['member_since'] }}</p>
                    </div>

                    <a href="{{ route('profile.index') }}"
                       class="flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-violet-900/30 shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Profil
                    </a>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white/5 rounded-2xl p-4 text-center border border-white/5 hover:border-violet-500/20 transition">
                        <p class="text-3xl font-bold text-violet-400">{{ $stats['total_conversations'] }}</p>
                        <p class="text-xs text-slate-500 mt-1">Percakapan</p>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center border border-white/5 hover:border-indigo-500/20 transition">
                        <p class="text-3xl font-bold text-indigo-400">{{ $stats['total_messages'] }}</p>
                        <p class="text-xs text-slate-500 mt-1">Pesan Dikirim</p>
                    </div>
                    <div class="bg-white/5 rounded-2xl p-4 text-center border border-white/5 hover:border-pink-500/20 transition">
                        <p class="text-3xl font-bold text-pink-400 truncate" 
                           title="{{ $user->created_at->diffInDays(now()) }} hari">
                            {{ number_format($user->created_at->diffInDays(now())) }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">Hari Bersama</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Detail Cards ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">

            <div class="glass rounded-2xl border border-white/10 p-5 hover:border-blue-500/20 transition">
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
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 mt-1 text-xs text-yellow-400">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Belum terverifikasi
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="glass rounded-2xl border border-white/10 p-5 hover:border-violet-500/20 transition">
                <p class="text-xs text-slate-500 uppercase tracking-widest font-medium mb-4">Gemini API Key</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <div>
                        @if($user->gemini_api_key ?? false)
                            <p class="text-sm text-white font-medium tracking-widest">••••••••••••••</p>
                            <span class="inline-flex items-center gap-1 mt-1 text-xs text-green-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                API key kustom aktif
                            </span>
                        @else
                            <p class="text-sm text-slate-400">Belum diatur</p>
                            <a href="{{ route('settings.index') }}" class="inline-flex items-center gap-1 mt-1 text-xs text-violet-400 hover:text-violet-300 transition">
                                Atur sekarang →
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Quick Actions ── --}}
        <div class="glass rounded-2xl border border-white/10 p-5">
            <p class="text-xs text-slate-500 uppercase tracking-widest font-medium mb-4">Aksi Cepat</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('profile.index') }}" class="group flex flex-col items-center gap-2.5 p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-violet-500/10 hover:border-violet-500/20 transition">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-violet-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span class="text-xs text-slate-400 group-hover:text-white transition font-medium">Edit Profil</span>
                </a>
                <a href="{{ route('settings.index') }}" class="group flex flex-col items-center gap-2.5 p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-blue-500/10 hover:border-blue-500/20 transition">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-xs text-slate-400 group-hover:text-white transition font-medium">Pengaturan</span>
                </a>
                <a href="{{ route('conversations.index') }}" class="group flex flex-col items-center gap-2.5 p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-indigo-500/10 hover:border-indigo-500/20 transition">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span class="text-xs text-slate-400 group-hover:text-white transition font-medium">Riwayat Chat</span>
                </a>
                <a href="{{ route('chat.index') }}" class="group flex flex-col items-center gap-2.5 p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-green-500/10 hover:border-green-500/20 transition">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-green-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="text-xs text-slate-400 group-hover:text-white transition font-medium">Chat Baru</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection