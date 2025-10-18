<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::with('creator');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'event_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $galleries = $query->paginate(12);
        
        $categories = ['general', 'sports', 'cultural', 'academic', 'others'];

        return view('galleries.index', compact('galleries', 'categories'));
    }

    public function create()
    {
        $categories = ['general', 'sports', 'cultural', 'academic', 'others'];
        return view('galleries.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:general,sports,cultural,academic,others',
            'event_date' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_public'] = $request->has('is_public');

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $coverImageName = time() . '_cover_' . Str::slug($request->title) . '.' . $coverImage->getClientOriginalExtension();
            $coverImage->move(public_path('storage/galleries'), $coverImageName);
            $validated['cover_image'] = 'galleries/' . $coverImageName;
        }

        // Handle multiple images upload
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imageName = time() . '_' . $index . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/galleries'), $imageName);
                $imagePaths[] = 'galleries/' . $imageName;
            }
        }
        $validated['images'] = $imagePaths;

        Gallery::create($validated);

        return redirect()->route('galleries.index')
            ->with('success', 'Gallery created successfully.');
    }

    public function show(Gallery $gallery)
    {
        $gallery->load('creator');
        $gallery->incrementViews();

        // Get related galleries
        $relatedGalleries = Gallery::where('category', $gallery->category)
            ->where('id', '!=', $gallery->id)
            ->where('status', 'published')
            ->limit(6)
            ->get();

        return view('galleries.show', compact('gallery', 'relatedGalleries'));
    }

    public function edit(Gallery $gallery)
    {
        $categories = ['general', 'sports', 'cultural', 'academic', 'others'];
        return view('galleries.edit', compact('gallery', 'categories'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:general,sports,cultural,academic,others',
            'event_date' => 'nullable|date',
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'is_featured' => 'boolean',
            'is_public' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'existing_images' => 'nullable|array',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_public'] = $request->has('is_public');

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($gallery->cover_image && file_exists(public_path('storage/' . $gallery->cover_image))) {
                unlink(public_path('storage/' . $gallery->cover_image));
            }

            $coverImage = $request->file('cover_image');
            $coverImageName = time() . '_cover_' . Str::slug($request->title) . '.' . $coverImage->getClientOriginalExtension();
            $coverImage->move(public_path('storage/galleries'), $coverImageName);
            $validated['cover_image'] = 'galleries/' . $coverImageName;
        }

        // Handle multiple images upload
        $existingImages = $request->input('existing_images', []);
        $imagePaths = [];

        // Keep existing images that weren't removed
        if ($gallery->images) {
            foreach ($gallery->images as $existingImage) {
                if (in_array($existingImage, $existingImages)) {
                    $imagePaths[] = $existingImage;
                } else {
                    // Delete removed images
                    if (file_exists(public_path('storage/' . $existingImage))) {
                        unlink(public_path('storage/' . $existingImage));
                    }
                }
            }
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imageName = time() . '_' . $index . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/galleries'), $imageName);
                $imagePaths[] = 'galleries/' . $imageName;
            }
        }
        $validated['images'] = $imagePaths;

        $gallery->update($validated);

        return redirect()->route('galleries.index')
            ->with('success', 'Gallery updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        // Delete cover image
        if ($gallery->cover_image && file_exists(public_path('storage/' . $gallery->cover_image))) {
            unlink(public_path('storage/' . $gallery->cover_image));
        }

        // Delete all images
        if ($gallery->images) {
            foreach ($gallery->images as $image) {
                if (file_exists(public_path('storage/' . $image))) {
                    unlink(public_path('storage/' . $image));
                }
            }
        }

        $gallery->delete();

        return redirect()->route('galleries.index')
            ->with('success', 'Gallery deleted successfully.');
    }
}
