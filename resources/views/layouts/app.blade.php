<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NexusAI — Chatbot Cerdas')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 
                            400: '#a78bfa', 
                            500: '#8b5cf6', 
                            600: '#7c3aed', 
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95'
                        },
                        dark: {  
                            900: '#0f0c29', 
                            800: '#1a1738', 
                            700: '#24243e', 
                            600: '#2d2a4e',
                            500: '#3b3768'
                        }
                    },
                    fontFamily: {
                        sans: ['"DM Sans"', 'sans-serif'],
                        display: ['"Syne"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    keyframes: {
                        fadeIn: { 
                            '0%': { opacity: '0', transform: 'translateY(20px)' }, 
                            '100%': { opacity: '1', transform: 'translateY(0)' } 
                        },
                        slideUp: { 
                            '0%': { transform: 'translateY(12px)', opacity: '0' }, 
                            '100%': { transform: 'translateY(0)', opacity: '1' } 
                        },
                        pulse: {
                            '0%, 100%': { opacity: '0.3' },
                            '50%': { opacity: '0.6' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease forwards',
                        'slide-up': 'slideUp 0.4s ease forwards',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'float': 'float 6s ease-in-out infinite'
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { 
            font-family: 'DM Sans', sans-serif; 
            background: linear-gradient(135deg, #0f0c29 0%, #1a1738 50%, #24243e 100%);
            color: #e2e8f0; 
            min-height: 100vh;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(31, 41, 55, 0.3); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { 
            background: linear-gradient(135deg, #8b5cf6, #6d28d9); 
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover { background: #a78bfa; }
        
        /* Glassmorphism Effect */
        .glass {
            background: rgba(26, 23, 56, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }
        
        .glass-card {
            background: rgba(26, 23, 56, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(139, 92, 246, 0.15);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            border-color: rgba(139, 92, 246, 0.4);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.3);
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #a78bfa 0%, #c084fc 50%, #e879f9 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .gradient-text-primary {
            background: linear-gradient(135deg, #818cf8 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        }
        
        .gradient-bg-hover {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
            transition: all 0.3s ease;
        }
        
        .gradient-bg-hover:hover {
            background: linear-gradient(135deg, #c084fc 0%, #8b5cf6 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.3);
        }
        
        /* Mesh Background Effect */
        .mesh-bg {
            position: relative;
            overflow: hidden;
        }
        
        .mesh-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(at 15% 25%, rgba(139, 92, 246, 0.08) 0, transparent 50%),
                radial-gradient(at 85% 75%, rgba(192, 132, 252, 0.06) 0, transparent 50%),
                radial-gradient(at 50% 50%, rgba(99, 102, 241, 0.05) 0, transparent 60%);
            pointer-events: none;
        }
        
        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.4);
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
        
        /* Card Styles */
        .feature-card {
            background: rgba(26, 23, 56, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(139, 92, 246, 0.15);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            border-color: rgba(139, 92, 246, 0.4);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.3);
        }
        
        /* Icon Styles */
        .feature-icon {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, rgba(192, 132, 252, 0.15) 100%);
            border: 1px solid rgba(139, 92, 246, 0.25);
        }
        
        /* Code Styles */
        .prose-ai p { margin-bottom: 0.65rem; line-height: 1.75; }
        .prose-ai code { 
            font-family: 'JetBrains Mono', monospace; 
            background: rgba(139, 92, 246, 0.15); 
            padding: 2px 6px; 
            border-radius: 4px; 
            font-size: 0.85em; 
            color: #c084fc; 
        }
        .prose-ai pre { 
            background: rgba(26, 23, 56, 0.8); 
            border: 1px solid rgba(139, 92, 246, 0.2); 
            border-radius: 10px; 
            padding: 1rem; 
            overflow-x: auto; 
            margin: 0.75rem 0; 
        }
        .prose-ai pre code { background: none; padding: 0; color: #e2e8f0; }
        .prose-ai ul { list-style: disc; padding-left: 1.25rem; margin-bottom: 0.65rem; }
        .prose-ai ol { list-style: decimal; padding-left: 1.25rem; margin-bottom: 0.65rem; }
        
        /* Alpine.js */
        [x-cloak] { display: none !important; }
        
        /* Animation Classes */
        .animate-fade-in { animation: fadeIn 0.6s ease forwards; }
        .animate-slide-up { animation: slideUp 0.4s ease forwards; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            from { transform: translateY(12px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
    @stack('styles')
</head>
<body class="h-full mesh-bg">
    <!-- Floating Particles Background Effect -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-violet-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-float" style="animation-delay: 4s;"></div>
    </div>
    
    <!-- Main Content -->
    <div class="relative z-10">
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>