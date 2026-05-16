@extends('layouts.app')

@section('title', 'Dashboard - NexusAI')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">  {{-- atau max-w-3xl sesuai tampilan awal --}}
        <!-- Welcome Section -->
        <div class="mb-8 reveal" data-aos="fade-up">
            <div class="flex items-center gap-5">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-r from-indigo-500/20 to-purple-600/20 border border-indigo-500/30 mb-6">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold gradient-text-premium mb-2">
                        Welcome back, {{ $user->name }}! 👋
                    </h1>
                    <p class="text-gray-400">Here's what's happening with your AI conversations.</p>
                </div>
            </div>
        </div>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Conversations -->
            <div class="glass-premium rounded-2xl p-6 card-3d reveal" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-indigo-500/20 to-purple-600/20 flex items-center justify-center">
                        <i class="fas fa-comments text-2xl text-indigo-400"></i>
                    </div>
                    <span class="text-3xl font-bold gradient-text-premium">{{ $stats['total_conversations'] }}</span>
                </div>
                <h3 class="text-white font-semibold mb-1">Total Conversations</h3>
                <p class="text-gray-500 text-sm">All time conversations</p>
            </div>
            
            <!-- Total Messages -->
            <div class="glass-premium rounded-2xl p-6 card-3d reveal" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-indigo-500/20 to-purple-600/20 flex items-center justify-center">
                        <i class="fas fa-envelope text-2xl text-purple-400"></i>
                    </div>
                    <span class="text-3xl font-bold gradient-text-premium">{{ $stats['total_messages'] }}</span>
                </div>
                <h3 class="text-white font-semibold mb-1">Total Messages</h3>
                <p class="text-gray-500 text-sm">Messages exchanged with AI</p>
            </div>
            
            <!-- Member Since -->
            <div class="glass-premium rounded-2xl p-6 card-3d reveal" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-indigo-500/20 to-purple-600/20 flex items-center justify-center">
                        <i class="fas fa-calendar text-2xl text-pink-400"></i>
                    </div>
                    <span class="text-3xl font-bold gradient-text-premium">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <h3 class="text-white font-semibold mb-1">Member Since</h3>
                <p class="text-gray-500 text-sm">Joined NexusAI</p>
            </div>
        </div>
        
        <!-- Recent Conversations -->
        <div class="glass-premium rounded-2xl p-6 reveal" data-aos="fade-up" data-aos-delay="400">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-white">Recent Conversations</h2>
                <a href="{{ route('chat.index') }}" class="text-purple-400 hover:text-purple-300 transition flex items-center space-x-1">
                    <span>View All</span>
                    <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>
            
            @if($stats['recent_conversations']->count() > 0)
                <div class="space-y-3">
                    @foreach($stats['recent_conversations'] as $conversation)
                        <a href="{{ route('chat.show', $conversation) }}" 
                           class="block glass-premium rounded-xl p-4 hover:border-purple-500/30 transition group">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white font-medium group-hover:text-purple-400 transition">
                                        {{ $conversation->title }}
                                    </h3>
                                    <p class="text-gray-500 text-sm">
                                        {{ $conversation->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500">
                                        {{ $conversation->messages_count ?? $conversation->messages()->count() }} messages
                                    </span>
                                    <i class="fas fa-chevron-right text-gray-500 group-hover:text-purple-400 transition"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 rounded-full bg-purple-500/10 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comment-dots text-3xl text-purple-400"></i>
                    </div>
                    <h3 class="text-white text-lg font-semibold mb-2">No conversations yet</h3>
                    <p class="text-gray-400 mb-4">Start your first conversation with NexusAI</p>
                    <a href="{{ route('chat.index') }}" class="inline-flex items-center space-x-2 px-6 py-3 btn-premium text-white rounded-lg font-semibold">
                        <span>Start Chatting</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="glass-premium rounded-2xl p-6 reveal" data-aos="fade-up" data-aos-delay="500">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-indigo-500/20 to-purple-600/20 flex items-center justify-center">
                        <i class="fas fa-robot text-2xl text-indigo-400"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold">AI Assistant Ready</h3>
                        <p class="text-gray-500 text-sm">Powered by Google Gemini AI</p>
                    </div>
                </div>
                <p class="text-gray-400 mb-4">Your AI assistant is ready to help with any questions or tasks.</p>
                <a href="{{ route('chat.index') }}" class="text-purple-400 hover:text-purple-300 transition inline-flex items-center space-x-1">
                    <span>Start new conversation</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="glass-premium rounded-2xl p-6 reveal" data-aos="fade-up" data-aos-delay="600">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-indigo-500/20 to-purple-600/20 flex items-center justify-center">
                        <i class="fas fa-cog text-2xl text-purple-400"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold">Customize Settings</h3>
                        <p class="text-gray-500 text-sm">Personalize your experience</p>
                    </div>
                </div>
                <p class="text-gray-400 mb-4">Adjust your AI preferences, API keys, and more.</p>
                <a href="{{ route('settings.index') }}" class="text-purple-400 hover:text-purple-300 transition inline-flex items-center space-x-1">
                    <span>Go to settings</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .glass-premium {
        background: rgba(15, 25, 45, 0.6);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(139, 92, 246, 0.2);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }
    
    .card-3d {
        transform-style: preserve-3d;
        transform: perspective(1000px);
        transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .card-3d:hover {
        transform: perspective(1000px) rotateX(5deg) translateY(-10px);
    }
    
    .gradient-text-premium {
        background: linear-gradient(135deg, #818cf8 0%, #c084fc 30%, #e879f9 60%, #f0abfc 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        background-size: 200% auto;
    }
    
    .btn-premium {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        transition: all 0.3s ease;
    }
    
    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.5);
    }
    
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@push('scripts')
<script>
    // Scroll reveal observer
    const reveals = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, { threshold: 0.1 });
    
    reveals.forEach(reveal => revealObserver.observe(reveal));
</script>
@endpush
@endsection