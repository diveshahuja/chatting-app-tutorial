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
    <body class="min-h-full font-sans antialiased bg-slate-950 text-slate-100 flex flex-col selection:bg-indigo-500 selection:text-white relative">
        <!-- Ambient Glowing Background Effect -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-[25%] left-[25%] w-[600px] h-[600px] rounded-full bg-linear-to-br from-indigo-600/15 to-purple-600/5 blur-[140px]"></div>
            <div class="absolute top-[40%] -right-[15%] w-[500px] h-[500px] rounded-full bg-linear-to-tl from-violet-600/10 to-indigo-600/10 blur-[130px]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(#1e1e2f_1px,transparent_1px)] [background-size:24px_24px] opacity-25"></div>
        </div>

        <!-- Sticky Floating Navigation Bar -->
        <nav class="sticky top-0 z-50 backdrop-blur-xl bg-slate-900/80 border-b border-slate-800/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <!-- Brand -->
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-xl bg-linear-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <span class="font-bold tracking-tight text-white text-lg flex items-center gap-2">
                                {{ config('app.name', 'Laravel') }}
                                <span class="hidden sm:inline-block text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">APP</span>
                            </span>
                        </a>

                        <!-- Navigation Links -->
                        <div class="hidden sm:flex sm:items-center sm:gap-2">
                            <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold tracking-wide transition flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                                <span>{{ __('Dashboard') }}</span>
                            </a>

                            <a href="{{ route('profile.edit') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold tracking-wide transition flex items-center gap-2 {{ request()->routeIs('profile.edit') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span>{{ __('Profile & Security') }}</span>
                            </a>

                            <a href="{{ route('conversations.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold tracking-wide transition flex items-center gap-2 {{ request()->routeIs('conversations.index') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.502 49.188 49.188 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                </svg>
                                <span>{{ __('Chat') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right side / User profile & Logout -->
                    <div class="hidden sm:flex sm:items-center sm:gap-4">
                        <div class="flex items-center gap-3 pl-4 border-l border-slate-800">
                            <div class="relative">
                                <div class="w-9 h-9 rounded-full bg-linear-to-tr from-indigo-500 to-purple-500 text-white font-bold text-xs flex items-center justify-center uppercase shadow-md shadow-indigo-500/20 ring-2 ring-slate-800">
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                </div>
                                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-slate-900"></span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-semibold text-white leading-tight">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-slate-400 leading-tight truncate max-w-[140px]">{{ Auth::user()->email }}</div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-400 hover:text-rose-400 rounded-lg hover:bg-slate-800/70 border border-transparent hover:border-slate-700/80 transition-all duration-150 cursor-pointer" title="{{ __('Log out of your account') }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                </svg>
                                <span>{{ __('Log Out') }}</span>
                            </button>
                        </form>
                    </div>

                    <!-- Mobile Hamburger Button -->
                    <div class="flex items-center sm:hidden">
                        <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 focus:outline-hidden" aria-label="Toggle navigation">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="hidden sm:hidden border-t border-slate-800 bg-slate-900/95 backdrop-blur-xl px-4 py-4 space-y-3">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-800">
                    <div class="w-10 h-10 rounded-full bg-linear-to-tr from-indigo-500 to-purple-500 text-white font-bold text-sm flex items-center justify-center uppercase">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div>
                        <div class="font-bold text-sm text-white">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-indigo-600/20 text-indigo-300' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('profile.edit') ? 'bg-indigo-600/20 text-indigo-300' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        {{ __('Profile & Security') }}
                    </a>
                    <a href="{{ route('conversations.index') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('conversations.index') ? 'bg-indigo-600/20 text-indigo-300' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        {{ __('Chat') }}
                    </a>
                </div>

                <div class="pt-2 border-t border-slate-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-sm font-semibold text-rose-400 hover:bg-rose-950/30">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Page Heading Slot -->
        @isset($header)
            <header class="border-b border-slate-800/60 bg-slate-900/30 backdrop-blur-xs">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content -->
        <main class="flex-1 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </body>
<script>
    window.authId = {{\Illuminate\Support\Facades\Auth::id()}}
</script>
</html>
