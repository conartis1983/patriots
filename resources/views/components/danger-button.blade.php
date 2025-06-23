<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center px-2.5 py-1.5 bg-red-600 border border-transparent text-xs font-bold uppercase tracking-wide rounded shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed'
]) }}>
    {{ $slot }}
</button>