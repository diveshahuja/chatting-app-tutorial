<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} - Enterprise Clean Architecture Auth</title>

        @fonts

        <!-- Scripts / Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full font-sans antialiased bg-slate-950 text-slate-100 flex flex-col justify-between selection:bg-indigo-500 selection:text-white relative overflow-x-hidden">
        <!-- Ambient Glowing Mesh Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-[30%] left-[15%] w-[700px] h-[700px] rounded-full bg-linear-to-br from-indigo-600/20 via-violet-600/15 to-purple-600/5 blur-[140px]"></div>
            <div class="absolute top-[35%] -right-[15%] w-[600px] h-[600px] rounded-full bg-linear-to-tl from-purple-600/15 via-indigo-600/10 to-transparent blur-[140px]"></div>
            <div class="absolute -bottom-[20%] left-[10%] w-[500px] h-[500px] rounded-full bg-linear-to-tr from-cyan-600/10 to-indigo-600/10 blur-[120px]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#1e1e2f_1px,transparent_1px)] [background-size:24px_24px] opacity-25"></div>
        </div>

        <!-- Sticky Floating Header -->
        <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-linear-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-white flex items-center gap-2">
                    {{ config('app.name', 'Laravel') }}
                    <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">AUTH</span>
                </span>
            </a>

            <nav class="flex items-center gap-3 text-xs font-semibold">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150">
                        <span>{{ __('Dashboard') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-3.5 py-2 text-slate-300 hover:text-white hover:bg-slate-800/60 rounded-xl transition">
                        {{ __('Sign In') }}
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-linear-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150">
                            <span>{{ __('Register') }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endif
                @endauth
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="max-w-7xl mx-auto px-6 py-12 sm:py-20 flex flex-col items-center text-center">
            <!-- Feature Announcement Pill -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 mb-8 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                <span>{{ __('Clean Architecture • Pure Blade • PHP 8.4') }}</span>
            </div>

            <!-- Hero Headline -->
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-white max-w-4xl leading-[1.1]">
                {{ __('Enterprise Authentication.') }}<br>
                <span class="bg-linear-to-r from-indigo-400 via-purple-300 to-violet-400 bg-clip-text text-transparent">
                    {{ __('Zero Framework Overhead.') }}
                </span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-400 max-w-2xl leading-relaxed">
                {{ __('A senior-engineered Laravel authentication module built with pure Blade templates and Tailwind CSS. Decoupled use cases, single-action classes, typed DTOs, and brute-force throttled security.') }}
            </p>

            <!-- Call to Actions -->
            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-3.5 bg-linear-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm rounded-xl shadow-xl shadow-indigo-600/35 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150 flex items-center gap-2">
                        <span>{{ __('Open Dashboard') }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-6 py-3.5 bg-linear-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm rounded-xl shadow-xl shadow-indigo-600/35 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150 flex items-center gap-2">
                        <span>{{ __('Create Free Account') }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-3.5 bg-slate-900/80 hover:bg-slate-800/80 text-white font-bold text-sm rounded-xl border border-slate-700/80 hover:border-slate-600 transition flex items-center gap-2">
                        <span>{{ __('Sign In') }}</span>
                    </a>
                @endauth
            </div>

            <!-- Live Architecture Highlights Grid -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 text-left w-full max-w-6xl">
                <!-- Highlight 1 -->
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 backdrop-blur-xl shadow-xl shadow-black/30 hover:border-indigo-500/40 transition-all duration-200">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-sm mb-4">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-white">{{ __('Single-Action Classes') }}</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">{{ __('Business logic and events live in dedicated Actions (use cases) for clean code and seamless testing.') }}</p>
                </div>

                <!-- Highlight 2 -->
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 backdrop-blur-xl shadow-xl shadow-black/30 hover:border-indigo-500/40 transition-all duration-200">
                    <div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 text-violet-400 flex items-center justify-center font-bold text-sm mb-4">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-2.18-4.34a6 6 0 1 0 0 8.49m0-8.49L12 9" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-white">{{ __('Strongly-Typed DTOs') }}</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">{{ __('Readonly PHP 8.4 data objects isolate HTTP requests from domain actions with guaranteed type safety.') }}</p>
                </div>

                <!-- Highlight 3 -->
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 backdrop-blur-xl shadow-xl shadow-black/30 hover:border-indigo-500/40 transition-all duration-200">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-sm mb-4">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-white">{{ __('Brute-Force Rate Limiting') }}</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">{{ __('IP and email based throttle keys protect login endpoints against credential stuffing and brute-force attacks.') }}</p>
                </div>

                <!-- Highlight 4 -->
                <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 backdrop-blur-xl shadow-xl shadow-black/30 hover:border-indigo-500/40 transition-all duration-200">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm mb-4">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-white">{{ __('Pure Blade & Tailwind 4') }}</h3>
                    <p class="mt-2 text-xs text-slate-400 leading-relaxed">{{ __('Lightning fast server-rendered Blade templates without frontend runtime bundle overhead.') }}</p>
                </div>
            </div>
        </main>

        <!-- Minimalist Footer -->
        <footer class="w-full max-w-7xl mx-auto px-6 py-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. {{ __('Engineered with Clean Architecture.') }}
            </div>
            <div class="flex items-center gap-6 text-slate-400">
                <a href="{{ route('login') }}" class="hover:text-white transition">{{ __('Sign In') }}</a>
                <a href="{{ route('register') }}" class="hover:text-white transition">{{ __('Create Account') }}</a>
                <a href="https://laravel.com" target="_blank" rel="noreferrer" class="hover:text-white transition">{{ __('Laravel 13') }}</a>
            </div>
        </footer>
    </body>
</html>
