@props(['type' => 'success', 'timeout' => 4000])

@php
    $colors = [
        'success' => 'bg-green-200 text-green-800',
        'error' => 'bg-red-200 text-red-800',
        'info' => 'bg-blue-200 text-blue-800',
        // beliebig erweiterbar
    ];
@endphp

@if ($slot->isNotEmpty())
    <div id="alert-message" class="mb-4 p-2 rounded text-xs text-center {{ $colors[$type] ?? $colors['info'] }}">
        {{ $slot }}
    </div>
    <script>
        setTimeout(function() {
            let box = document.getElementById('alert-message');
            if (box) box.style.display = 'none';
        }, {{ $timeout }});
    </script>
@endif