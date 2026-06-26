@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-slate-900/50 border border-white/10 text-white placeholder-slate-400 focus:border-brand-primary focus:ring-brand-primary rounded-xl shadow-sm transition-colors']) }}>
