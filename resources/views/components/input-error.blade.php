@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'space-y-1.5 mt-2']) }} role="alert">
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-2 text-xs font-semibold text-rose-300 bg-rose-950/40 border border-rose-800/50 px-3 py-1.5 rounded-lg shadow-xs">
                <svg class="w-4 h-4 shrink-0 text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 7.5h.01" />
                </svg>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
