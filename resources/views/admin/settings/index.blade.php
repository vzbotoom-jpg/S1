@extends('layouts.admin')
@section('title', 'Pengaturan — NexusAI Admin')

@section('content')
<div class="p-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="font-display text-3xl font-bold text-white">Pengaturan</h1>
            <p class="text-slate-400 mt-1">Konfigurasi NexusAI dan integrasi API</p>
        </div>

        @if(session('success'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-brand-500/10 border border-brand-500/30 text-brand-300 text-sm">
                ✓ {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf @method('PUT')

            {{-- Gemini API --}}
            <div class="glass rounded-2xl p-6">
                <h2 class="font-semibold text-white mb-1">Google Gemini API</h2>
                <p class="text-xs text-slate-500 mb-5">Konfigurasi koneksi ke Google Gemini AI</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">API Key <span class="text-red-400">*</span></label>
                        <input type="password" name="gemini_api_key" value="{{ old('gemini_api_key', $settings['gemini_api_key'] ?? '') }}"
                            class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition font-mono text-sm"
                            placeholder="AIza...">
                        <p class="mt-1.5 text-xs text-slate-600">Dapatkan API key dari <a href="https://makersuite.google.com/app/apikey" target="_blank" class="text-brand-400 hover:underline">Google AI Studio</a></p>
                        @error('gemini_api_key')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Model</label>
                        <select name="gemini_model"
                            class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-brand-500 transition">
                            @foreach(['gemini-pro', 'gemini-1.5-pro', 'gemini-1.5-flash', 'gemini-2.0-flash'] as $model)
                                <option value="{{ $model }}" {{ ($settings['gemini_model'] ?? 'gemini-1.5-flash') === $model ? 'selected' : '' }}>
                                    {{ $model }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">System Prompt (Opsional)</label>
                        <textarea name="system_prompt" rows="4"
                            class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition resize-none text-sm"
                            placeholder="Anda adalah asisten AI yang membantu...">{{ old('system_prompt', $settings['system_prompt'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Chat Settings --}}
            <div class="glass rounded-2xl p-6">
                <h2 class="font-semibold text-white mb-1">Pengaturan Chat</h2>
                <p class="text-xs text-slate-500 mb-5">Konfigurasi perilaku chatbot</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Maks. Pesan per Sesi</label>
                        <input type="number" name="max_messages" value="{{ old('max_messages', $settings['max_messages'] ?? 100) }}"
                            min="10" max="1000"
                            class="w-full bg-dark-700/60 border border-dark-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-brand-500 transition">
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-300">Rate Limiting</p>
                            <p class="text-xs text-slate-600 mt-0.5">Batasi jumlah request per menit per user</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="rate_limit_enabled" value="1" class="sr-only peer"
                                {{ ($settings['rate_limit_enabled'] ?? true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-dark-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-brand-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 rounded-xl border border-dark-600 text-slate-400 hover:text-white hover:border-dark-500 transition text-sm font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-semibold transition-all active:scale-[0.98] text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
