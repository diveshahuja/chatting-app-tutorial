<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>

        @fonts

        <!-- Scripts / Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-full font-sans antialiased text-slate-900 dark:text-slate-100 bg-slate-950 selection:bg-indigo-500 selection:text-white relative flex flex-col justify-between overflow-x-hidden">
        <!-- Ambient Glowing Background Effect -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-[30%] left-[20%] w-[650px] h-[650px] rounded-full bg-linear-to-br from-indigo-600/20 to-purple-600/10 blur-[130px]"></div>
            <div class="absolute -bottom-[20%] right-[10%] w-[550px] h-[550px] rounded-full bg-linear-to-tl from-violet-600/20 to-pink-600/10 blur-[120px]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#1e1e2f_1px,transparent_1px)] [background-size:24px_24px] opacity-25"></div>
        </div>

        <!-- Top Header Navigation -->
        <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-linear-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-white flex items-center gap-1.5">
                    {{ config('app.name', 'Laravel') }}
                    <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 font-semibold tracking-wide">AUTH</span>
                </span>
            </a>

            <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-400 hover:text-white transition flex items-center gap-1.5 py-1.5 px-3 rounded-lg hover:bg-slate-800/60">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Back to home</span>
            </a>
        </header>

        <!-- Main Card Area -->
        <main class="w-full max-w-md mx-auto px-4 sm:px-0 py-8 my-auto">
            <div class="relative">
                <!-- Card Glow Backdrop -->
                <div class="absolute -inset-1 rounded-3xl bg-linear-to-r from-indigo-500/20 via-purple-500/20 to-pink-500/20 blur-xl opacity-75"></div>

                <!-- Card Body -->
                <div class="relative backdrop-blur-2xl bg-slate-900/90 border border-slate-800/80 p-8 sm:p-10 rounded-3xl shadow-2xl shadow-black/60">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <!-- Footer Security Note -->
        <footer class="w-full max-w-7xl mx-auto px-6 py-6 text-center text-xs text-slate-500 flex flex-wrap items-center justify-center gap-4">
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
                <span>256-bit SSL Encrypted</span>
            </span>
            <span>•</span>
            <span>Rate-limited Protection</span>
            <span>•</span>
            <span>Clean Architecture</span>
        </footer>
    </body>
</html>
