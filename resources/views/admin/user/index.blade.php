@extends('layouts.admin')
@section('title', 'Kelola Pengguna — NexusAI Admin')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-7xl mx-auto">

        @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-display text-2xl md:text-3xl font-bold text-white">Kelola Pengguna</h1>
                <p class="text-slate-400 mt-1 text-sm">Mengelola semua pengguna sistem</p>
            </div>
        </div>

        <div class="glass rounded-2xl overflow-hidden">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-4 md:px-6 py-4 border-b border-dark-600/40 gap-2">
                <h2 class="font-semibold text-white text-sm md:text-base">Daftar Pengguna ({{ $users->total() }})</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs md:text-sm">
                    <thead>
                        <tr class="border-b border-dark-600/40">
                            <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider hidden md:table-cell">Percakapan</th>
                            <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider hidden lg:table-cell">Status</th>
                            <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider hidden lg:table-cell">Daftar</th>
                            <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-600/30">
                        @forelse($users as $user)
                        <tr class="hover:bg-dark-700/30 transition">
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-400 to-indigo-500 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-white font-medium">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-slate-400">{{ $user->email }}</td>
                            <td class="px-4 md:px-6 py-4 text-slate-400 hidden md:table-cell">{{ $user->conversations_count ?? 0 }}</td>
                            <td class="px-4 md:px-6 py-4 hidden lg:table-cell">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->is_admin ? 'bg-blue-500/10 text-blue-400' : 'bg-slate-700/40 text-slate-400' }}">
                                    {{ $user->is_admin ? 'Admin' : 'User' }}
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-slate-500 text-xs hidden lg:table-cell">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-700/40 text-slate-300 hover:bg-slate-700/60 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $user->is_admin ? 'bg-red-500/10 text-red-400 hover:bg-red-500/20' : 'bg-blue-500/10 text-blue-400 hover:bg-blue-500/20' }}">
                                            {{ $user->is_admin ? 'Remove Admin' : 'Jadikan Admin' }}
                                        </button>
                                    </form>
                                    @if($user->id !== auth()->user()->id)
                                    <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="inline" onsubmit="return confirm('Hapus pengguna ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-600 text-sm">Belum ada pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-dark-600/40">
                <div class="flex justify-center">{{ $users->links() }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
