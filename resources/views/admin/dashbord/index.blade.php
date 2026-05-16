@extends('layouts.admin')
@section('title', 'Dashboard Admin — NexusAI')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="font-display text-2xl md:text-3xl font-bold text-white">Dashboard</h1>
            <p class="text-slate-400 mt-1">Selamat datang, {{ auth()->user()->name ?? 'Admin' }}!</p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5 mb-8">
            @foreach([
                ['label' => 'Total Pengguna',    'value' => $totalUsers ?? 0,         'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label' => 'Total Percakapan',  'value' => $totalConversations ?? 0,  'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                ['label' => 'Total Pesan',       'value' => $totalMessages ?? 0,       'icon' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'],
                ['label' => 'Aktif Hari Ini',    'value' => $todayActive ?? 0,         'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            ] as $stat)
            <div class="glass rounded-2xl p-4 md:p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs md:text-sm text-slate-400">{{ $stat['label'] }}</p>
                    <div class="w-8 md:w-9 h-8 md:h-9 rounded-xl bg-brand-500/10 border border-brand-500/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <p class="font-display text-xl md:text-3xl font-bold text-white">{{ number_format($stat['value']) }}</p>
            </div>
            @endforeach
        </div>

        {{-- Recent Users Table --}}
        <div class="glass rounded-2xl overflow-hidden">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-4 md:px-6 py-4 border-b border-dark-600/40 gap-2">
                <h2 class="font-semibold text-white text-sm md:text-base">Pengguna Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs md:text-sm min-w-max md:min-w-0">
                    <thead>
                        <tr class="border-b border-dark-600/40">
                            <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider hidden md:table-cell">Percakapan</th>
                            <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider hidden lg:table-cell">Daftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-600/30">
                        @forelse($recentUsers ?? [] as $user)
                        <tr class="hover:bg-dark-700/30 transition">
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex items-center gap-2 md:gap-3">
                                    <div class="w-6 md:w-8 h-6 md:h-8 rounded-full bg-gradient-to-br from-brand-400 to-indigo-500 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-white font-medium truncate">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-slate-400 truncate">{{ $user->email }}</td>
                            <td class="px-4 md:px-6 py-4 text-slate-400 hidden md:table-cell">{{ $user->conversations_count ?? 0 }}</td>
                            <td class="px-4 md:px-6 py-4 text-slate-500 text-xs hidden lg:table-cell">{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 md:px-6 py-10 text-center text-slate-600 text-sm">Belum ada pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
