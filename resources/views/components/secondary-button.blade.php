<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-800/80 hover:bg-slate-700/80 active:bg-slate-800 border border-slate-700/80 rounded-xl font-bold text-xs text-slate-200 uppercase tracking-wider hover:text-white cursor-pointer transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
