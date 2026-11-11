<x-app-layout>
    <x-slot:title>{{ __('Profile Settings') }}</x-slot:title>

    <x-slot:header>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-extrabold text-2xl text-white tracking-tight">
                    {{ __('Profile & Security Settings') }}
                </h1>
                <p class="mt-1 text-xs text-slate-400">
                    {{ __('Manage your personal identification, security credentials, and account lifecycle.') }}
                </p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-white bg-slate-800/60 border border-slate-700/80 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>{{ __('Back to Dashboard') }}</span>
                </a>
            </div>
        </div>
    </x-slot:header>

    <div class="space-y-8 max-w-4xl mx-auto">
        <!-- Profile Information Card -->
        <x-card>
            @include('profile.partials.update-profile-information-form')
        </x-card>

        <!-- Password Update Card -->
        <x-card id="password">
            @include('profile.partials.update-password-form')
        </x-card>

        <!-- Danger Zone Card -->
        <x-card class="border-rose-900/40 bg-rose-950/10">
            @include('profile.partials.delete-user-form')
        </x-card>
    </div>
</x-app-layout>
