@props(['icon' => null, 'title', 'value', 'color' => 'blue', 'trend' => null, 'link' => null])

@php
    $colors = [
        'blue' => 'from-blue-500 to-blue-600',
        'green' => 'from-green-500 to-green-600',
        'purple' => 'from-purple-500 to-purple-600',
        'yellow' => 'from-yellow-500 to-yellow-600',
        'red' => 'from-red-500 to-red-600',
        'indigo' => 'from-indigo-500 to-indigo-600',
    ];
    $gradientClass = $colors[$color] ?? $colors['blue'];
@endphp

<div {{ $attributes->merge(['class' => "bg-gradient-to-br {$gradientClass} rounded-lg shadow-lg p-6 text-white"]) }}>
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-white/80 text-sm font-medium">{{ $title }}</p>
            <h3 class="text-3xl font-bold mt-2">{{ $value }}</h3>
            @if($trend)
                <p class="text-white/70 text-xs mt-2">{{ $trend }}</p>
            @endif
        </div>
        @if($icon)
            <div class="bg-white/20 rounded-full p-3">
                {!! $icon !!}
            </div>
        @endif
    </div>
    @if($link)
        <a href="{{ $link }}" class="inline-flex items-center mt-4 text-sm text-white/80 hover:text-white">
            View Details
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    @endif
</div>
