<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $news = News::with('creator')->latest()->paginate(10);
            \Log::info('News Index: Loaded ' . $news->total() . ' news items');
            return view('news.index', compact('news'));
        } catch (\Exception $e) {
            \Log::error('News Index Error: ' . $e->getMessage());
            return view('news.index')->with('news', News::paginate(10))->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
                'date' => 'required|date',
                'is_active' => 'boolean',
            ]);

            $validated['created_by'] = Auth::id();
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;

            $news = News::create($validated);
            
            \Log::info('News Created: ' . $news->title . ' (ID: ' . $news->id . ')');

            return redirect()->route('news.index')
                ->with('success', 'News created successfully! Title: ' . $news->title);
        } catch (\Exception $e) {
            \Log::error('News Store Error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create news: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        return view('news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'date' => 'required|date',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $news->update($validated);

        return redirect()->route('news.index')
            ->with('success', 'News updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'News deleted successfully!');
    }
}
