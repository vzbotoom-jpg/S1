@extends('layouts.app')
@section('title', 'Verifikasi Email — NexusAI')
@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-lg text-center animate-fade-in">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-brand-500/15 border border-brand-500/25 mb-6">
            <svg class="w-10 h-10 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 class="font-display text-3xl font-bold text-white mb-3">Cek Inbox Anda</h1>
        <p class="text-slate-400 leading-relaxed mb-8">Sebelum melanjutkan, cek email Anda untuk link verifikasi. Jika tidak menerima email, kami bisa kirimkan ulang.</p>

        @if(session('resent'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-brand-500/10 border border-brand-500/30 text-brand-300 text-sm">
                Link verifikasi baru telah dikirim ke email Anda.
            </div>
        @endif

        <div class="glass rounded-2xl p-6 flex flex-col sm:flex-row gap-3 justify-center">
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="w-full sm:w-auto bg-brand-500 hover:bg-brand-600 text-white font-semibold px-6 py-3 rounded-xl transition-all active:scale-[0.98]">
                    Kirim Ulang Email
                </button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full sm:w-auto bg-dark-700 hover:bg-dark-600 border border-dark-600 text-slate-300 font-semibold px-6 py-3 rounded-xl transition-all active:scale-[0.98]">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection