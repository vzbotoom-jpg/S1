<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NexusAI - AI Chatbot Assistant</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            min-height: 100vh;
            cursor: none;
            overflow-x: hidden;
        }
        
        /* Custom cursor */
        .custom-cursor {
            width: 20px;
            height: 20px;
            background: rgba(139, 92, 246, 0.5);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.1s ease;
            backdrop-filter: blur(2px);
        }
        
        .custom-cursor-dot {
            width: 6px;
            height: 6px;
            background: #c084fc;
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 10000;
        }
        
        /* Particle styles */
        .particle {
            position: fixed;
            pointer-events: none;
            z-index: 9998;
            filter: blur(1px);
            will-change: transform, opacity;
        }
        
        .glass {
            background: rgba(15, 25, 35, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .glass-card {
            background: rgba(20, 30, 45, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            transform: translateY(-5px);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #818cf8 0%, #a78bfa 50%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.3);
        }
        
        .btn-outline {
            border: 1.5px solid rgba(139, 92, 246, 0.5);
            background: transparent;
            transition: all 0.3s ease;
        }
        
        .btn-outline:hover {
            border-color: #8b5cf6;
            background: rgba(139, 92, 246, 0.1);
            transform: translateY(-2px);
        }
        
        .feature-icon {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .animate-fade-in-delay {
            animation: fadeIn 0.8s ease-out 0.3s both;
        }
        
        .animate-fade-in-delay-2 {
            animation: fadeIn 0.8s ease-out 0.6s both;
        }
    </style>
</head>
<body>
    <!-- Custom Cursors -->
    <div class="custom-cursor"></div>
    <div class="custom-cursor-dot"></div>
    
    <!-- Navbar -->
    <nav class="glass fixed w-full top-0 z-50 border-b border-white/10">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold gradient-text">NexusAI</span>
                </div>
                
                <div class="flex space-x-3">
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-300 hover:text-white text-sm font-medium transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2 btn-primary text-white rounded-lg text-sm font-medium">
                            Get Started
                        </a>
                    @else
                        <a href="{{ route('chat.index') }}" class="px-5 py-2 btn-primary text-white rounded-lg text-sm font-medium">
                            Go to Chat
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <div class="relative overflow-hidden pt-20">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
            <div class="text-center animate-fade-in">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-r from-indigo-500/20 to-purple-600/20 border border-indigo-500/30 mb-6">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
                    </svg>
                </div>
                <h1 class="text-5xl md:text-7xl font-extrabold mb-6">
                    <span class="gradient-text">NexusAI</span>
                    <br>
                    <span class="text-white">Intelligent Conversations</span>
                </h1>
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Experience the power of Google Gemini AI. Get instant answers, creative insights, and intelligent conversations.
                </p>
                <div class="flex justify-center space-x-4">
                    @guest
                        <a href="{{ route('register') }}" class="px-8 py-3 btn-primary text-white rounded-lg font-semibold inline-flex items-center space-x-2">
                            <span>Start Free Trial</span>
                            <i class="fas fa-arrow-right text-sm"></i>
                        </a>
                        <a href="#features" class="px-8 py-3 btn-outline text-purple-400 rounded-lg font-semibold inline-flex items-center space-x-2">
                            <span>Learn More</span>
                            <i class="fas fa-chevron-down text-sm"></i>
                        </a>
                    @else
                        <a href="{{ route('chat.index') }}" class="px-8 py-3 btn-primary text-white rounded-lg font-semibold inline-flex items-center space-x-2">
                            <span>Start Chatting</span>
                            <i class="fas fa-comment-dots text-sm"></i>
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
    
    <!-- Features Section -->
    <div id="features" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in">
                <h2 class="text-3xl md:text-4xl font-bold gradient-text mb-4">Powerful Features</h2>
                <p class="text-xl text-gray-400">Everything you need for intelligent conversations</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="glass-card rounded-2xl p-8 text-center animate-fade-in-delay">
                    <div class="feature-icon w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-bolt text-2xl text-indigo-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Lightning Fast</h3>
                    <p class="text-gray-400">Get responses instantly with optimized API calls</p>
                </div>
                
                <div class="glass-card rounded-2xl p-8 text-center animate-fade-in-delay">
                    <div class="feature-icon w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-brain text-2xl text-purple-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Smart AI</h3>
                    <p class="text-gray-400">Powered by Google's most advanced Gemini AI</p>
                </div>
                
                <div class="glass-card rounded-2xl p-8 text-center animate-fade-in-delay-2">
                    <div class="feature-icon w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-history text-2xl text-pink-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Chat History</h3>
                    <p class="text-gray-400">Never lose your conversations with persistent storage</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Section -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-4xl font-bold gradient-text">10K+</div>
                    <p class="text-gray-400 mt-2">Active Users</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold gradient-text">1M+</div>
                    <p class="text-gray-400 mt-2">Messages Sent</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold gradient-text">99.9%</div>
                    <p class="text-gray-400 mt-2">Uptime</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold gradient-text">24/7</div>
                    <p class="text-gray-400 mt-2">Support</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="py-20">
        <div class="max-w-4xl mx-auto text-center px-4">
            <div class="glass rounded-2xl p-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Ready to transform your conversations?
                </h2>
                <p class="text-xl text-gray-400 mb-8">
                    Join thousands of users who trust NexusAI
                </p>
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center space-x-2 px-8 py-3 btn-primary text-white rounded-lg font-semibold">
                        <span>Get Started Now</span>
                        <i class="fas fa-rocket text-sm"></i>
                    </a>
                @else
                    <a href="{{ route('chat.index') }}" class="inline-flex items-center space-x-2 px-8 py-3 btn-primary text-white rounded-lg font-semibold">
                        <span>Go to Dashboard</span>
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                @endguest
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="border-t border-white/10 py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center space-x-6 mb-6">
                <a href="#" class="text-gray-400 hover:text-purple-400 transition">
                    <i class="fab fa-twitter text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-purple-400 transition">
                    <i class="fab fa-github text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-purple-400 transition">
                    <i class="fab fa-discord text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-purple-400 transition">
                    <i class="fab fa-linkedin text-xl"></i>
                </a>
            </div>
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} NexusAI. All rights reserved.</p>
            <p class="text-gray-600 text-xs mt-2">Powered by Google Gemini AI</p>
        </div>
    </footer>
    
    <script>
        // Custom cursor and particle effect
        (function() {
            const cursor = document.querySelector('.custom-cursor');
            const cursorDot = document.querySelector('.custom-cursor-dot');
            
            if (!cursor || !cursorDot) return;
            
            let mouseX = 0, mouseY = 0;
            let cursorX = 0, cursorY = 0;
            let dotX = 0, dotY = 0;
            
            // Track mouse position
            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                
                // Create particles on mouse move
                createParticle(e.clientX, e.clientY);
            });
            
            // Smooth animation for cursor
            function animateCursor() {
                // Smooth follow for main cursor
                cursorX += (mouseX - cursorX) * 0.15;
                cursorY += (mouseY - cursorY) * 0.15;
                
                // Faster follow for dot
                dotX += (mouseX - dotX) * 0.3;
                dotY += (mouseY - dotY) * 0.3;
                
                cursor.style.transform = `translate(${cursorX - 10}px, ${cursorY - 10}px)`;
                cursorDot.style.transform = `translate(${dotX - 3}px, ${dotY - 3}px)`;
                
                requestAnimationFrame(animateCursor);
            }
            
            animateCursor();
            
            // Hide default cursor on interactive elements
            const interactiveElements = document.querySelectorAll('a, button, input, [role="button"]');
            interactiveElements.forEach(el => {
                el.addEventListener('mouseenter', () => {
                    cursor.style.transform = `scale(1.5)`;
                    cursor.style.background = 'rgba(192, 132, 252, 0.7)';
                    cursorDot.style.transform = `scale(0)`;
                });
                el.addEventListener('mouseleave', () => {
                    cursor.style.transform = `scale(1)`;
                    cursor.style.background = 'rgba(139, 92, 246, 0.5)';
                    cursorDot.style.transform = `scale(1)`;
                });
            });
            
            // Particle system
            const colors = [
                '#818cf8', '#a78bfa', '#c084fc', '#e879f9', 
                '#f0abfc', '#38bdf8', '#4ade80', '#fbbf24', 
                '#f472b6', '#2dd4bf', '#fb923c', '#a855f7'
            ];
            
            function createParticle(x, y) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random size between 2px and 6px
                const size = Math.random() * 6 + 2;
                // Random color from array
                const color = colors[Math.floor(Math.random() * colors.length)];
                // Random opacity between 0.4 and 0.9
                const opacity = Math.random() * 0.5 + 0.4;
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.background = color;
                particle.style.borderRadius = '50%';
                particle.style.position = 'fixed';
                particle.style.left = `${x}px`;
                particle.style.top = `${y}px`;
                particle.style.opacity = opacity;
                particle.style.pointerEvents = 'none';
                particle.style.zIndex = '9998';
                
                document.body.appendChild(particle);
                
                // Random velocity
                const vx = (Math.random() - 0.5) * 4;
                const vy = (Math.random() - 0.5) * 4 - 2;
                let opacity_ = opacity;
                let posX = x;
                let posY = y;
                let life = 1;
                
                function animateParticle() {
                    if (life <= 0) {
                        particle.remove();
                        return;
                    }
                    
                    posX += vx;
                    posY += vy;
                    life -= 0.02;
                    opacity_ -= 0.02;
                    
                    particle.style.left = `${posX}px`;
                    particle.style.top = `${posY}px`;
                    particle.style.opacity = opacity_;
                    particle.style.transform = `scale(${life})`;
                    
                    requestAnimationFrame(animateParticle);
                }
                
                requestAnimationFrame(animateParticle);
                
                // Remove particle after 1 second
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.remove();
                    }
                }, 1000);
            }
            
            // Create occasional floating particles even without mouse movement
            setInterval(() => {
                if (Math.random() > 0.7) {
                    const randomX = Math.random() * window.innerWidth;
                    const randomY = Math.random() * window.innerHeight;
                    createParticle(randomX, randomY);
                }
            }, 500);
        })();
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>