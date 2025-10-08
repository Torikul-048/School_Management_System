@props(['type' => 'primary', 'size' => 'md', 'href' => null])

@php
    $types = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white',
        'info' => 'bg-blue-500 hover:bg-blue-600 text-white',
        'light' => 'bg-white hover:bg-gray-100 text-gray-700 border border-gray-300',
        'dark' => 'bg-gray-800 hover:bg-gray-900 text-white',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2',
        'lg' => 'px-6 py-3 text-lg',
    ];
    
    $typeClass = $types[$type] ?? $types['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    
    $baseClass = "inline-flex items-center justify-center font-medium rounded-lg transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {$typeClass} {$sizeClass}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClass]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $baseClass]) }}>
        {{ $slot }}
    </button>
@endif
