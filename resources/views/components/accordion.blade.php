@props(['title' => null, 'open' => false])

<div x-data="{ open: {{ $open ? 'true' : 'false' }} }" {{ $attributes->merge(['class' => 'border border-gray-200 rounded-lg']) }}>
    <button @click="open = !open" type="button" class="flex items-center justify-between w-full px-4 py-3 text-left bg-gray-50 hover:bg-gray-100">
        <span class="font-medium text-gray-900">{{ $title }}</span>
        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div x-show="open" x-transition class="px-4 py-3 border-t border-gray-200">
        {{ $slot }}
    </div>
</div>
