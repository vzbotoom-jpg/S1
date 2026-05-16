@extends('layouts.app')
@section('title', 'Reset Password — NexusAI')
@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md animate-fade-in">
        <div class="text-center mb-10">
            <h1 class="font-display text-3xl font-bold text-white">Reset Password</h1>
            <p class="text-slate-400 mt-2 text-sm">Buat password baru untuk akun Anda.</p>
        </div>
        <div class="glass rounded-2xl p-8">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" required
                        class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition"
                        placeholder="nama@email.com">
                    @error('email')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Password Baru</label>
                    <input type="password" name="password" required
                        class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition"
                        placeholder="Min. 8 karakter">
                    @error('password')<p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition"
                        placeholder="Ulangi password baru">
                </div>
                <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-semibold py-3 rounded-xl transition-all active:scale-[0.98]">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection