<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-brand-primary/90 focus:bg-brand-primary/90 active:bg-brand-primary/80 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 focus:ring-offset-slate-900 transition ease-in-out duration-150 shadow-[0_0_15px_rgba(0,98,255,0.3)]']) }}>
    {{ $slot }}
</button>
