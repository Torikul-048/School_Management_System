@extends('layouts.admin')

@section('title', $gallery->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('galleries.index') }}" 
               class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                ← Back to Galleries
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $gallery->title }}</h1>
        
        <div class="flex flex-wrap items-center gap-3 mt-3">
            <span class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 text-sm font-semibold rounded">
                {{ ucfirst($gallery->category) }}
            </span>
            
            @if($gallery->is_featured)
            <span class="px-3 py-1 bg-yellow-500 text-white text-sm font-semibold rounded">
                Featured
            </span>
            @endif
            
            <span class="px-3 py-1 text-sm font-semibold rounded
                @if($gallery->status == 'published') bg-green-100 text-green-800
                @elseif($gallery->status == 'draft') bg-gray-100 text-gray-800
                @else bg-red-100 text-red-800
                @endif">
                {{ ucfirst($gallery->status) }}
            </span>

            @if($gallery->event_date)
            <span class="flex items-center gap-1 text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ $gallery->event_date->format('F d, Y') }}
            </span>
            @endif

            <span class="flex items-center gap-1 text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                {{ $gallery->views_count }} views
            </span>
        </div>
    </div>

    <!-- Description -->
    @if($gallery->description)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $gallery->description }}</p>
    </div>
    @endif

    <!-- Gallery Images -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">
            Photos ({{ $gallery->image_count }})
        </h2>

        @if($gallery->images && count($gallery->images) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($gallery->images as $image)
            <div class="relative group">
                <img src="{{ asset('storage/' . $image) }}" 
                     alt="Gallery Image"
                     class="w-full h-64 object-cover rounded-lg cursor-pointer hover:opacity-90 transition"
                     onclick="openLightbox('{{ asset('storage/' . $image) }}')">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                    </svg>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No images in this gallery</p>
        @endif
    </div>

    <!-- Gallery Info -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Gallery Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600 dark:text-gray-400">Created by:</span>
                <span class="text-gray-800 dark:text-white font-medium ml-2">
                    {{ $gallery->creator->name ?? 'Unknown' }}
                </span>
            </div>
            <div>
                <span class="text-gray-600 dark:text-gray-400">Created on:</span>
                <span class="text-gray-800 dark:text-white font-medium ml-2">
                    {{ $gallery->created_at->format('F d, Y') }}
                </span>
            </div>
            <div>
                <span class="text-gray-600 dark:text-gray-400">Total Photos:</span>
                <span class="text-gray-800 dark:text-white font-medium ml-2">
                    {{ $gallery->image_count }}
                </span>
            </div>
            <div>
                <span class="text-gray-600 dark:text-gray-400">Visibility:</span>
                <span class="text-gray-800 dark:text-white font-medium ml-2">
                    {{ $gallery->is_public ? 'Public' : 'Private' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-3 mb-6">
        <a href="{{ route('galleries.edit', $gallery) }}" 
           class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
            Edit Gallery
        </a>
        <form action="{{ route('galleries.destroy', $gallery) }}" method="POST" class="inline"
              onsubmit="return confirm('Are you sure you want to delete this gallery? All images will be permanently removed.');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                Delete Gallery
            </button>
        </form>
    </div>

    <!-- Related Galleries -->
    @if($relatedGalleries->count() > 0)
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Related Galleries</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedGalleries as $related)
            <a href="{{ route('galleries.show', $related) }}" 
               class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
                <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
                    @if($related->cover_image)
                    <img src="{{ asset('storage/' . $related->cover_image) }}" 
                         alt="{{ $related->title }}"
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                    <div class="absolute bottom-2 right-2 px-2 py-1 bg-black bg-opacity-75 text-white text-xs rounded">
                        {{ $related->image_count }} photos
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white line-clamp-2">
                        {{ $related->title }}
                    </h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center" onclick="closeLightbox()">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300">
        &times;
    </button>
    <img id="lightbox-img" src="" alt="Full size image" class="max-w-full max-h-full p-4">
</div>

@push('scripts')
<script>
function openLightbox(imageSrc) {
    document.getElementById('lightbox').classList.remove('hidden');
    document.getElementById('lightbox-img').src = imageSrc;
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeLightbox();
    }
});
</script>
@endpush
@endsection
