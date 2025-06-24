<button {{ $attributes->merge([
    'class' => 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-normal text-xs py-0.5 px-2 rounded transition leading-tight'
]) }}>
    {{ $slot }}
</button>