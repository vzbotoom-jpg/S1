@extends('layouts.app')
@section('title', 'Pengaturan — NexusAI')

@section('content')
<div class="min-h-screen py-10 px-4">
    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Pengaturan</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola preferensi dan API key kamu.</p>
        </div>

        {{-- Flash success --}}
        @if(session('success'))
            <div class="mb-6 px-5 py-3 bg-green-500/10 border border-green-500/20 text-green-400 text-sm rounded-2xl flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Flash error --}}
        @if(session('error'))
            <div class="mb-6 px-5 py-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-2xl flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Form Pengaturan --}}
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PATCH')

            {{-- Card: Gemini API Key --}}
            <div class="glass rounded-3xl border border-white/10 p-6 mb-6">

                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-white">Gemini API Key</h2>
                        <p class="text-xs text-slate-500">Gunakan API key milik kamu sendiri untuk prioritas lebih tinggi</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-slate-400 mb-2">API Key</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="gemini_api_key"
                                name="gemini_api_key"
                                value="{{ auth()->user()->gemini_api_key ?? '' }}"
                                placeholder="AIzaSy..."
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-violet-500 transition pr-12"
                            >
                            {{-- Toggle show/hide password --}}
                            <button
                                type="button"
                                onclick="toggleApiKey()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition"
                            >
                                <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('gemini_api_key')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info cara dapat API key --}}
                    <div class="flex items-start gap-2 p-3 bg-blue-500/5 border border-blue-500/10 rounded-xl">
                        <svg class="w-4 h-4 text-blue-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-slate-400">
                            Dapatkan API key gratis di
                            <a href="https://aistudio.google.com/app/apikey" target="_blank"
                               class="text-blue-400 hover:text-blue-300 underline transition">
                                aistudio.google.com
                            </a>.
                            Jika kosong, sistem akan menggunakan API key default.
                        </p>
                    </div>

                    {{-- Status API key --}}
                    <div class="flex items-center gap-2">
                        @if(auth()->user()->gemini_api_key)
                            <span class="flex items-center gap-1.5 text-xs text-green-400">
                                <span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span>
                                API key kustom aktif
                            </span>
                            <button
                                type="button"
                                onclick="clearApiKey()"
                                class="ml-auto text-xs text-red-400 hover:text-red-300 transition"
                            >
                                Hapus API key
                            </button>
                        @else
                            <span class="flex items-center gap-1.5 text-xs text-slate-500">
                                <span class="w-2 h-2 rounded-full bg-slate-500 inline-block"></span>
                                Menggunakan API key default
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex justify-end gap-3">

            {{-- Tombol Kembali ke Chat --}}
            <a href="{{ route('chat.index') }}" 
                class="px-8 py-3 rounded-2xl font-medium flex items-center gap-2 transition bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400 hover:text-white">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Chat
            </a>
                
                <button
                    type="submit"
                    class="btn-primary px-8 py-3 rounded-2xl font-medium flex items-center gap-2 transition"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>

        </form>
    </div>
</div>

<script>
// Toggle show/hide API key
function toggleApiKey() {
    const input   = document.getElementById('gemini_api_key');
    const eyeIcon = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        `;
    } else {
        input.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
    }
}

// Hapus API key (set input ke kosong)
function clearApiKey() {
    if (confirm('Hapus API key kustom? Sistem akan kembali menggunakan API key default.')) {
        document.getElementById('gemini_api_key').value = '';
        document.querySelector('form').submit();
    }
}
</script>
@endsection