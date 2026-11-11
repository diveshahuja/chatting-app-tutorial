@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 bg-slate-950/70 border border-slate-700/80 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/15 rounded-xl text-sm text-white placeholder-slate-500 transition duration-150 outline-hidden disabled:opacity-50']) }}>
