@extends('layouts.admin')

@section('title', 'Create Gallery')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Create New Gallery</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Upload photos and create a new gallery album</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Gallery Title *
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required
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
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
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
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
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
                    <input type="date" name="event_date" value="{{ old('event_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('event_date') border-red-500 @enderror">
                    @error('event_date')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Cover Image -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Cover Image
                </label>
                <input type="file" name="cover_image" accept="image/jpeg,image/jpg,image/png"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('cover_image') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Maximum file size: 5MB. Supported formats: JPEG, JPG, PNG</p>
                @error('cover_image')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gallery Images -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Gallery Images *
                </label>
                <input type="file" name="images[]" accept="image/jpeg,image/jpg,image/png" multiple required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('images.*') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Select multiple images (hold Ctrl/Cmd). Maximum 5MB per image. Supported formats: JPEG, JPG, PNG</p>
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
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
                @error('status')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options -->
            <div class="mb-6 space-y-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        Mark as Featured (will be highlighted on homepage)
                    </span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}
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
                    Create Gallery
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
