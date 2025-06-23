<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center px-2.5 py-1.5 bg-neutral-200 border border-neutral-300 text-xs font-bold uppercase tracking-wide rounded shadow-sm hover:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed'
]) }}>
    {{ $slot }}
</button>