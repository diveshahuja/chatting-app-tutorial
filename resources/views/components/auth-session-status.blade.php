@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-3 p-4 text-xs font-semibold text-emerald-300 bg-emerald-950/40 border border-emerald-500/30 rounded-xl shadow-lg shadow-emerald-950/30']) }} role="status">
        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>
        <span class="leading-relaxed">{{ $status }}</span>
    </div>
@endif
