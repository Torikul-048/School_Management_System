@extends('layouts.admin')

@section('title', 'Edit Gallery')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Gallery</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Update gallery details and images</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <form action="{{ route('galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Gallery Title *
                </label>
                <input type="text" name="title" value="{{ old('title', $gallery->title) }}" required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror">
                @error('title')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description
                </label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror">{{ old('description', $gallery->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category and Event Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Category *
                    </label>
                    <select name="category" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('category') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category', $gallery->category) == $cat ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}
                        </option>
                        @endforeach
                    </select>
                    @error('category')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Event Date
                    </label>
                    <input type="date" name="event_date" value="{{ old('event_date', $gallery->event_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('event_date') border-red-500 @enderror">
                    @error('event_date')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Current Cover Image -->
            @if($gallery->cover_image)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Current Cover Image
                </label>
                <img src="{{ asset('storage/' . $gallery->cover_image) }}" alt="Cover" class="w-48 h-32 object-cover rounded-lg">
            </div>
            @endif

            <!-- Cover Image -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ $gallery->cover_image ? 'Replace Cover Image' : 'Cover Image' }}
                </label>
                <input type="file" name="cover_image" accept="image/jpeg,image/jpg,image/png"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('cover_image') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Maximum file size: 5MB. Supported formats: JPEG, JPG, PNG</p>
                @error('cover_image')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Existing Images -->
            @if($gallery->images && count($gallery->images) > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Existing Images (uncheck to remove)
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($gallery->images as $image)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image" class="w-full h-32 object-cover rounded-lg">
                        <label class="absolute top-2 right-2 bg-white dark:bg-gray-700 rounded px-2 py-1 cursor-pointer">
                            <input type="checkbox" name="existing_images[]" value="{{ $image }}" checked
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Add New Images -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Add New Images
                </label>
                <input type="file" name="images[]" accept="image/jpeg,image/jpg,image/png" multiple
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('images.*') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Select multiple images (hold Ctrl/Cmd). Maximum 5MB per image.</p>
                @error('images.*')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status *
                </label>
                <select name="status" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('status') border-red-500 @enderror">
                    <option value="draft" {{ old('status', $gallery->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $gallery->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ old('status', $gallery->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
                @error('status')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options -->
            <div class="mb-6 space-y-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $gallery->is_featured) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        Mark as Featured
                    </span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_public" value="1" {{ old('is_public', $gallery->is_public) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        Public (visible to everyone)
                    </span>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-3">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                    Update Gallery
                </button>
                <a href="{{ route('galleries.index') }}" 
                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
