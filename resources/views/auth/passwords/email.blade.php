@extends('layouts.app')
@section('title', 'Reset Password — NexusAI')
@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md animate-fade-in">
        <div class="text-center mb-10">
            <h1 class="font-display text-3xl font-bold text-white">Lupa Password?</h1>
            <p class="text-slate-400 mt-2 text-sm leading-relaxed">Masukkan email Anda dan kami akan kirimkan<br>link untuk reset password.</p>
        </div>
        <div class="glass rounded-2xl p-8">
            @if(session('status'))
                <div class="mb-5 px-4 py-3 rounded-xl bg-brand-500/10 border border-brand-500/30 text-brand-300 text-sm">{{ session('status') }}</div>
            @endif
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition"
                        placeholder="nama@email.com">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold py-3 rounded-xl transition-all active:scale-[0.98]">
                    Kirim Link Reset
                </button>
            </form>
            <p class="text-center text-sm text-slate-500 mt-6">
                <a href="{{ route('login') }}" class="text-brand-400 hover:text-brand-300 transition">← Kembali ke Login</a>
            </p>
        </div>
    </div>
</div>
@endsection