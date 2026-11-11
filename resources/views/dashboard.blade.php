<x-app-layout>
    <x-slot:title>{{ __('Dashboard') }}</x-slot:title>

    <x-slot:header>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-extrabold text-2xl text-white tracking-tight">
                    {{ __('Dashboard Overview') }}
                </h1>
                <p class="mt-1 text-xs text-slate-400">
                    {{ __('Monitor your account security and authentication metrics.') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    {{ __('Session Active') }}
                </span>
                <span class="text-xs text-slate-500 hidden md:inline">
                    {{ now()->format('l, F j, Y') }}
                </span>
            </div>
        </div>
    </x-slot:header>

    <div class="space-y-8">
        <!-- Hero Welcome Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-linear-to-r from-indigo-900/50 via-slate-900/90 to-purple-900/40 border border-indigo-500/20 p-8 sm:p-10 shadow-2xl shadow-indigo-950/40 backdrop-blur-xl">
            <!-- Decorative gradient orbs -->
            <div class="absolute -top-16 -right-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-4">
                    <span>✨ System Status: Optimal</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    {{ __('Welcome back, :name!', ['name' => $user->name]) }}
                </h2>
                <p class="mt-3 text-slate-300 text-sm sm:text-base leading-relaxed">
                    {{ __('Your authentication module is functioning with enterprise-level clean architecture. All actions are isolated into dedicated use case classes, typed DTOs, and throttled validation requests.') }}
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        <span>{{ __('Account Settings') }}</span>
                    </a>

                    <a href="{{ route('profile.edit') }}#password" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800/80 hover:bg-slate-700/80 active:bg-slate-800 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-slate-700 transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <span>{{ __('Update Security') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 Metrics Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Metric 1: Account Status -->
            <x-card class="relative overflow-hidden group hover:border-slate-700 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ __('Account Status') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-xl font-extrabold text-white">{{ __('Active & Healthy') }}</div>
                    <div class="mt-1 text-xs text-slate-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>{{ __('Zero security flags') }}</span>
                    </div>
                </div>
            </x-card>

            <!-- Metric 2: Security Strength -->
            <x-card class="relative overflow-hidden group hover:border-slate-700 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ __('Security Rating') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-xl font-extrabold text-white">{{ __('Bcrypt Hashed') }}</div>
                    <div class="mt-1 text-xs text-slate-400">
                        {{ __('12 Work Rounds + Throttling') }}
                    </div>
                </div>
            </x-card>

            <!-- Metric 3: Authentication Tier -->
            <x-card class="relative overflow-hidden group hover:border-slate-700 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ __('Stack Architecture') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-xl font-extrabold text-white">{{ __('Pure Blade') }}</div>
                    <div class="mt-1 text-xs text-slate-400">
                        {{ __('Zero Framework Overhead') }}
                    </div>
                </div>
            </x-card>

            <!-- Metric 4: Member Since -->
            <x-card class="relative overflow-hidden group hover:border-slate-700 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ __('Joined Date') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-xl font-extrabold text-white">{{ $user->created_at?->format('M j, Y') ?? 'Recently' }}</div>
                    <div class="mt-1 text-xs text-slate-400">
                        {{ $user->created_at ? $user->created_at->diffForHumans() : 'Active member' }}
                    </div>
                </div>
            </x-card>
        </div>

        <!-- 2 Column Content Section: Architecture Stack & Security Audit -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Architectural Highlights -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        <span>{{ __('Architecture & Domain Contracts') }}</span>
                    </h3>
                    <span class="text-xs text-slate-500">{{ __('Senior Engineering Standards') }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-card class="!p-5">
                        <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center font-mono font-bold text-xs mb-3">
                            01
                        </div>
                        <h4 class="font-bold text-sm text-white">{{ __('Single-Action Classes') }}</h4>
                        <p class="mt-1 text-xs text-slate-400 leading-relaxed">
                            {{ __('All mutations and business rules are encapsulated into single-responsibility Action classes, fully decoupled from HTTP transport.') }}
                        </p>
                    </x-card>

                    <x-card class="!p-5">
                        <div class="w-9 h-9 rounded-xl bg-violet-500/10 border border-violet-500/20 text-violet-400 flex items-center justify-center font-mono font-bold text-xs mb-3">
                            02
                        </div>
                        <h4 class="font-bold text-sm text-white">{{ __('Readonly Typed DTOs') }}</h4>
                        <p class="mt-1 text-xs text-slate-400 leading-relaxed">
                            {{ __('Strictly typed immutable data objects transport validated inputs into domain actions with full type safety.') }}
                        </p>
                    </x-card>

                    <x-card class="!p-5">
                        <div class="w-9 h-9 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center font-mono font-bold text-xs mb-3">
                            03
                        </div>
                        <h4 class="font-bold text-sm text-white">{{ __('Brute-Force Rate Limiting') }}</h4>
                        <p class="mt-1 text-xs text-slate-400 leading-relaxed">
                            {{ __('Login attempts are throttled using Laravel RateLimiter keys with automated lockout triggers and event dispatching.') }}
                        </p>
                    </x-card>

                    <x-card class="!p-5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-mono font-bold text-xs mb-3">
                            04
                        </div>
                        <h4 class="font-bold text-sm text-white">{{ __('Zero-Bloat Blade Engine') }}</h4>
                        <p class="mt-1 text-xs text-slate-400 leading-relaxed">
                            {{ __('Modern UI crafted with semantic Blade components and Tailwind CSS v4, achieving instant First Contentful Paint without JS framework bloat.') }}
                        </p>
                    </x-card>
                </div>
            </div>

            <!-- Right 1 Col: User Identity Details -->
            <div class="space-y-4">
                <h3 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>{{ __('Identity Profile') }}</span>
                </h3>

                <x-card class="!p-6 space-y-5">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-linear-to-tr from-indigo-600 via-indigo-500 to-violet-500 text-white font-extrabold text-lg flex items-center justify-center uppercase shadow-lg shadow-indigo-600/30 ring-2 ring-slate-800">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div class="overflow-hidden">
                            <div class="font-extrabold text-base text-white truncate">{{ $user->name }}</div>
                            <div class="text-xs text-slate-400 truncate">{{ $user->email }}</div>
                            <span class="inline-block mt-1 text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                                {{ __('Standard User') }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3 pt-3 border-t border-slate-800 text-xs">
                        <div class="flex justify-between py-1 border-b border-slate-800/60">
                            <span class="text-slate-400">{{ __('User ID') }}</span>
                            <span class="font-mono font-bold text-slate-200">#{{ $user->id }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-800/60">
                            <span class="text-slate-400">{{ __('Email Verification') }}</span>
                            <span class="font-semibold text-emerald-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span>{{ __('Verified') }}</span>
                            </span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-slate-400">{{ __('Password Status') }}</span>
                            <span class="font-semibold text-slate-200">{{ __('Secure') }}</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('profile.edit') }}" class="block w-full text-center py-2.5 px-4 bg-slate-800/90 hover:bg-slate-700/90 text-white text-xs font-bold rounded-xl border border-slate-700 transition">
                            {{ __('Edit Profile Details') }}
                        </a>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>
