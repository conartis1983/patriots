<button {{ $attributes->merge([
    'class' => 'bg-red-600 hover:bg-red-700 text-white font-normal text-xs py-0.5 px-2 rounded transition leading-tight border-none'
]) }}>
    {{ $slot }}
</button>