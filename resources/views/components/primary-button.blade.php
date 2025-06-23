<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center px-2.5 py-1.5 bg-red-700 border border-transparent text-white text-xs font-bold uppercase tracking-wide rounded shadow-sm hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed'
]) }}>
    {{ $slot }}
</button>