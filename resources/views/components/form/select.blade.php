@props(['label', 'name', 'required' => false, 'options' => [], 'placeholder' => 'Select an option'])

<div {{ $attributes->merge(['class' => 'mb-4']) }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <select 
        id="{{ $name }}" 
        name="{{ $name }}" 
        @if($required) required @endif
        {{ $attributes->except('class')->merge() }}
        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
    >
        @if($placeholder && empty($slot->toHtml()))
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @if(!empty($slot->toHtml()))
            {{ $slot }}
        @else
            @foreach($options as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        @endif
    </select>
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
