<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .menu-text {
            transition: opacity 0.2s ease;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-[#0a0a0a] text-white overflow-hidden">
    <div class="flex h-screen">
        
        <!-- ===================== SIDEBAR GROK.AI STYLE ===================== -->
        <div 
            id="sidebar"
            class="sidebar w-14 hover:w-64 bg-[#0f0f0f] border-r border-white/10 h-full flex flex-col z-50 overflow-hidden group"
        >
            <!-- Logo / Toggle Area -->
            <div class="h-14 flex items-center px-4 border-b border-white/10">
                <button 
                    onclick="toggleSidebar()"
                    class="flex items-center gap-3 w-full hover:bg-white/5 rounded-xl p-2 transition"
                >
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-2xl flex items-center justify-center font-bold text-lg flex-shrink-0">
                        G
                    </div>
                    <span id="logo-text" class="menu-text text-xl font-semibold opacity-0 group-hover:opacity-100 transition">
                        Grok
                    </span>
                </button>
            </div>

            <!-- Menu Items -->
            <nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition group/item">
                    <i class="fas fa-home w-5 text-gray-400 group-hover/item:text-white"></i>
                    <span class="menu-text text-sm font-medium opacity-0 group-hover:opacity-100">Dashboard</span>
                </a>

                <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition group/item sidebar-active">
                    <i class="fas fa-comment-dots w-5 text-gray-400 group-hover/item:text-white"></i>
                    <span class="menu-text text-sm font-medium opacity-0 group-hover:opacity-100">Chat</span>
                </a>

                <a href="{{ route('chat.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition group/item">
                    <i class="fas fa-history w-5 text-gray-400 group-hover/item:text-white"></i>
                    <span class="menu-text text-sm font-medium opacity-0 group-hover:opacity-100">History</span>
                </a>

                <div class="pt-4">
                    <div class="px-4 text-xs font-medium text-gray-500 mb-2 menu-text opacity-0 group-hover:opacity-100">TOOLS</div>
                    
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition group/item">
                        <i class="fas fa-image w-5 text-gray-400 group-hover/item:text-white"></i>
                        <span class="menu-text text-sm font-medium opacity-0 group-hover:opacity-100">Imagine</span>
                    </a>
                    
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition group/item">
                        <i class="fas fa-microphone w-5 text-gray-400 group-hover/item:text-white"></i>
                        <span class="menu-text text-sm font-medium opacity-0 group-hover:opacity-100">Voice</span>
                    </a>
                </div>

                <div class="pt-6">
                    <div class="px-4 text-xs font-medium text-gray-500 mb-2 menu-text opacity-0 group-hover:opacity-100">PROJECTS</div>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition group/item">
                        <i class="fas fa-folder w-5 text-gray-400 group-hover/item:text-white"></i>
                        <span class="menu-text text-sm font-medium opacity-0 group-hover:opacity-100">New Project</span>
                    </a>
                </div>
            </nav>

            <!-- Bottom User -->
            <div class="p-4 border-t border-white/10">
                <button onclick="toggleUserMenu()" 
                        class="w-full flex items-center gap-3 hover:bg-white/5 p-2 rounded-2xl transition">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center font-bold">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="menu-text opacity-0 group-hover:opacity-100 flex-1 text-left">
                        <div class="text-sm font-medium">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="text-xs text-gray-500">Free</div>
                    </div>
                </button>
            </div>
        </div>

        <!-- ===================== MAIN CONTENT ===================== -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <main class="flex-1 overflow-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('w-14')) {
                sidebar.classList.remove('w-14');
                sidebar.classList.add('w-64');
            } else {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-14');
            }
        }

        // Optional: Klik di luar sidebar untuk collapse (seperti Grok)
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar.contains(e.target) && sidebar.classList.contains('w-64')) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-14');
            }
        });

        // Tailwind script sudah ada
    </script>

    @stack('scripts')
</body>
</html>