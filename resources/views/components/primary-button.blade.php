<button {{ $attributes->merge([
    'class' => 'bg-red-700 hover:bg-red-700 text-white font-bold text-xs py-1 px-3 rounded transition leading-tight'
]) }}>
    {{ $slot }}
</button>