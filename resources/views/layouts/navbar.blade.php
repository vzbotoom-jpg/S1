<nav class="glass fixed w-full top-0 z-50 border-b border-purple-500/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg gradient-bg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
                    </svg>
                </div>
                <span class="text-xl font-bold gradient-text">NexusAI</span>
            </div>
            
            <div class="flex space-x-3">
                @guest
                    <a href="{{ route('login') }}" class="px-4 py-2 text-gray-300 hover:text-purple-400 text-sm font-medium transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2 btn-primary text-white rounded-lg text-sm font-medium">
                        Get Started
                    </a>
                @else
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('chat.index') }}" class="px-5 py-2 btn-primary text-white rounded-lg text-sm font-medium">
                            Go to Chat
                        </a>
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full gradient-bg flex items-center justify-center text-white text-sm font-semibold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </button>
                            
                            <div x-show="open" x-cloak class="absolute right-0 mt-2 w-48 glass rounded-lg shadow-xl py-2 z-50 border border-purple-500/20">
                                <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-gray-300 hover:text-purple-400 hover:bg-purple-500/10 transition">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-gray-300 hover:text-purple-400 hover:bg-purple-500/10 transition">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                                <hr class="my-1 border-purple-500/20">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 transition">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>