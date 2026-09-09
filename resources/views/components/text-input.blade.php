@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-white placeholder-slate-400 focus:border-brand-primary focus:ring-brand-primary rounded-xl shadow-sm transition-colors']) }}>
