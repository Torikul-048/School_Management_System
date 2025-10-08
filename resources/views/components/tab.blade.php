@props(['active' => false])

@php
    $activeClass = $active 
        ? 'border-blue-500 text-blue-600' 
        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
@endphp

<button {{ $attributes->merge(['class' => "whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {$activeClass}"]) }}>
    {{ $slot }}
</button>
