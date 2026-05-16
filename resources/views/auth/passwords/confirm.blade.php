@extends('layouts.app')
@section('title', 'Konfirmasi Password — NexusAI')
@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md animate-fade-in">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/25 mb-4">
                <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="font-display text-3xl font-bold text-white">Konfirmasi Password</h1>
            <p class="text-slate-400 mt-2 text-sm">Harap konfirmasi password sebelum melanjutkan.</p>
        </div>
        <div class="glass rounded-2xl p-8">
            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                    <input type="password" name="password" required autofocus
                        class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition"
                        placeholder="••••••••">
                    @error('password')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold py-3 rounded-xl transition-all active:scale-[0.98]">
                    Konfirmasi
                </button>
            </form>
            @if(Route::has('password.request'))
            <p class="text-center text-sm text-slate-500 mt-5">
                <a href="{{ route('password.request') }}" class="text-brand-400 hover:text-brand-300 transition">Lupa password?</a>
            </p>
            @endif
        </div>
    </div>
</div>
@endsection