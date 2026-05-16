@extends('layouts.admin')
@section('title', 'Analytics — NexusAI Admin')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="font-display text-3xl font-bold text-white">Analytics</h1>
            <p class="text-slate-400 mt-1">Statistik penggunaan website secara real-time</p>
        </div>

        {{-- Time Range Selector --}}
        <div class="mb-6 flex gap-3">
            <button class="px-4 py-2 rounded-lg bg-brand-500/15 text-brand-300 border border-brand-500/20 text-sm font-medium hover:bg-brand-500/25 transition">7 Hari</button>
            <button class="px-4 py-2 rounded-lg bg-dark-700/60 text-slate-400 border border-dark-600/40 text-sm font-medium hover:bg-dark-700/80 transition">30 Hari</button>
            <button class="px-4 py-2 rounded-lg bg-dark-700/60 text-slate-400 border border-dark-600/40 text-sm font-medium hover:bg-dark-700/80 transition">90 Hari</button>
        </div>

        {{-- Top Metrics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="glass rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-slate-400">Total Views</p>
                    <div class="w-9 h-9 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="font-display text-3xl font-bold text-white">{{ $totalViews ?? 0 }}</p>
                <p class="text-xs text-green-400 mt-2">+12% dari sebelumnya</p>
            </div>

            <div class="glass rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-slate-400">Unique Users</p>
                    <div class="w-9 h-9 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="font-display text-3xl font-bold text-white">{{ $uniqueUsers ?? 0 }}</p>
                <p class="text-xs text-green-400 mt-2">+8% dari sebelumnya</p>
            </div>

            <div class="glass rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-slate-400">Avg Session</p>
                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="font-display text-3xl font-bold text-white">{{ $avgSession ?? '0m' }}</p>
                <p class="text-xs text-red-400 mt-2">-3% dari sebelumnya</p>
            </div>

            <div class="glass rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-slate-400">Bounce Rate</p>
                    <div class="w-9 h-9 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4m0 0L3 5"/>
                        </svg>
                    </div>
                </div>
                <p class="font-display text-3xl font-bold text-white">{{ $bounceRate ?? '0%' }}</p>
                <p class="text-xs text-red-400 mt-2">+5% dari sebelumnya</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 glass rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-semibold text-white">Penggunaan Naik Turun</h2>
                    <select class="px-3 py-1.5 rounded-lg bg-dark-700/60 border border-dark-600/40 text-slate-400 text-sm">
                        <option>Harian</option>
                        <option>Mingguan</option>
                        <option>Bulanan</option>
                    </select>
                </div>
                <div class="bg-dark-800 rounded-xl p-4" style="height: 400px;">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            <div class="space-y-4">
                <div class="glass rounded-2xl p-6">
                    <h3 class="text-sm font-semibold text-white mb-4">Top Pages</h3>
                    <div class="space-y-3">
                        @foreach($topPages as $page)
                        <div>
                            <p class="text-xs text-slate-400 mb-1">{{ $page['path'] }}</p>
                            <div class="w-full h-1.5 bg-dark-700/40 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500" style="width: {{ $page['percentage'] }}%"></div>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">{{ $page['views'] }} views</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="glass rounded-2xl p-6">
                    <h3 class="text-sm font-semibold text-white mb-4">Browser</h3>
                    <div class="space-y-3">
                        @foreach([['Chrome','65%'],['Firefox','20%'],['Safari','10%'],['Lainnya','5%']] as [$name,$pct])
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-slate-400">{{ $name }}</p>
                            <p class="text-xs font-semibold text-white">{{ $pct }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Secondary Chart --}}
        <div class="glass rounded-2xl p-6">
            <h2 class="font-semibold text-white mb-6">Konversi Pengguna</h2>
            <div class="bg-dark-800 rounded-xl p-4" style="height: 300px;">
                <canvas id="conversionChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = {!! json_encode($labels) !!};
    const dailyData = {!! json_encode($dailyData) !!};
    const conversations = dailyData.map(d => d.conversations);
    const activeUsers = dailyData.map(d => d.active_users);
    const messages = dailyData.map(d => d.messages);

    const mainCtx = document.getElementById('mainChart').getContext('2d');
    new Chart(mainCtx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                { label: 'Percakapan', data: conversations, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)', tension: 0.4, fill: true, pointRadius: 5, pointBackgroundColor: '#3b82f6', borderWidth: 3 },
                { label: 'Pengguna Aktif', data: activeUsers, borderColor: '#a855f7', backgroundColor: 'rgba(168,85,247,0.1)', tension: 0.4, fill: true, pointRadius: 5, pointBackgroundColor: '#a855f7', borderWidth: 3 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { color: '#cbd5e1', font: { size: 12 }, padding: 20 } } }, scales: { y: { beginAtZero: true, ticks: { color: '#94a3b8' }, grid: { color: 'rgba(148,163,184,0.1)' } }, x: { ticks: { color: '#94a3b8' }, grid: { display: false } } } }
    });

    const conversionCtx = document.getElementById('conversionChart').getContext('2d');
    new Chart(conversionCtx, {
        type: 'bar',
        data: {
            labels: ['Percakapan', 'Total Pesan', 'Pengguna', 'Total'],
            datasets: [{ label: 'Count', data: [ conversations.reduce((a,b)=>a+b,0), messages.reduce((a,b)=>a+b,0), {{ $uniqueUsers ?? 0 }}, conversations.reduce((a,b)=>a+b,0)+messages.reduce((a,b)=>a+b,0) ], backgroundColor: ['#3b82f6','#8b5cf6','#10b981','#f59e0b'], borderRadius: 8, borderSkipped: false }]
        },
        options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(148,163,184,0.1)' } }, y: { ticks: { color: '#94a3b8' }, grid: { display: false } } } }
    });
</script>
@endpush
