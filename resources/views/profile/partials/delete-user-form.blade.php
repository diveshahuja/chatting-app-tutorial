<section class="space-y-6">
    <header class="flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>
        <div>
            <h2 class="text-base font-bold text-rose-400 tracking-tight">
                {{ __('Delete Account') }}
            </h2>
            <p class="text-xs text-slate-400">
                {{ __('Once your account is deleted, all resources and data will be permanently wiped.') }}
            </p>
        </div>
    </header>

    <div class="p-4 rounded-xl bg-rose-950/20 border border-rose-900/30 text-xs text-rose-300 leading-relaxed">
        {{ __('Please be certain. This operation is permanent and irreversible. All personal information, sessions, and associated credentials will be permanently erased.') }}
    </div>

    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4 max-w-xl">
        @csrf
        @method('delete')

        <div>
            <x-input-label for="delete_account_password" :value="__('Enter your password to confirm deletion')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <x-text-input id="delete_account_password" name="password" type="password" class="pl-10" placeholder="{{ __('Your account password') }}" />
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="pt-2">
            <x-danger-button onclick="return confirm('{{ __('Are you completely sure you want to permanently delete your account? This action cannot be reversed.') }}')">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
                <span>{{ __('Delete Account Permanently') }}</span>
            </x-danger-button>
        </div>
    </form>
</section>
