<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('isbn', 'like', '%' . $request->search . '%')
                    ->orWhere('author', 'like', '%' . $request->search . '%')
                    ->orWhere('publisher', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $books = $query->paginate(20);
        $categories = BookCategory::active()->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = BookCategory::active()->get();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|exists:book_categories,id',
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:50',
            'total_copies' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'rack_location' => 'nullable|string|max:100',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pdf_file' => 'nullable|mimes:pdf|max:51200',
        ]);

        $validated['available_copies'] = $validated['total_copies'];

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('books/covers', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            $validated['pdf_file'] = $request->file('pdf_file')->store('books/pdfs', 'public');
        }

        $book = Book::create($validated);

        // Generate QR Code (optional - requires ext-gd and simplesoftwareio/simple-qrcode)
        // Uncomment these lines after installing the package
        // $qrCode = QrCode::format('png')->size(200)->generate($book->barcode);
        // $qrPath = 'books/qrcodes/' . $book->barcode . '.png';
        // Storage::disk('public')->put($qrPath, $qrCode);
        // $book->update(['qr_code' => $qrPath]);

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'issues.student', 'issues.teacher']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = BookCategory::active()->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|exists:book_categories,id',
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:50',
            'total_copies' => 'required|integer|min:1',
            'available_copies' => 'required|integer|min:0|lte:total_copies',
            'price' => 'nullable|numeric|min:0',
            'rack_location' => 'nullable|string|max:100',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pdf_file' => 'nullable|mimes:pdf|max:51200',
            'status' => 'required|in:available,unavailable',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('books/covers', 'public');
        }

        if ($request->hasFile('pdf_file')) {
            if ($book->pdf_file) {
                Storage::disk('public')->delete($book->pdf_file);
            }
            $validated['pdf_file'] = $request->file('pdf_file')->store('books/pdfs', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        if ($book->issues()->where('status', 'issued')->exists()) {
            return redirect()->back()->with('error', 'Cannot delete book with active issues!');
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        if ($book->pdf_file) {
            Storage::disk('public')->delete($book->pdf_file);
        }

        if ($book->qr_code) {
            Storage::disk('public')->delete($book->qr_code);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('author')) {
            $query->byAuthor($request->author);
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('isbn', 'like', '%' . $request->keyword . '%');
            });
        }

        $books = $query->paginate(20);
        $categories = BookCategory::active()->get();
        $authors = Book::select('author')->distinct()->orderBy('author')->pluck('author');

        return view('books.search', compact('books', 'categories', 'authors'));
    }

    public function inventory()
    {
        $books = Book::with('category')
            ->selectRaw('books.*, (total_copies - available_copies) as issued_copies')
            ->orderBy('title')
            ->paginate(20);

        return view('books.inventory', compact('books'));
    }

    public function digitalLibrary(Request $request)
    {
        $query = Book::whereNotNull('pdf_file')->with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $books = $query->paginate(20);
        $categories = BookCategory::active()->get();

        return view('books.digital-library', compact('books', 'categories'));
    }

    public function downloadPdf(Book $book)
    {
        if (!$book->pdf_file || !Storage::disk('public')->exists($book->pdf_file)) {
            return redirect()->back()->with('error', 'PDF file not found!');
        }

        return Storage::disk('public')->download($book->pdf_file, $book->title . '.pdf');
    }

    public function scanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $book = Book::where('barcode', $request->barcode)->first();

        if (!$book) {
            return response()->json(['error' => 'Book not found!'], 404);
        }

        return response()->json([
            'success' => true,
            'book' => [
                'id' => $book->id,
                'title' => $book->title,
                'isbn' => $book->isbn,
                'author' => $book->author,
                'barcode' => $book->barcode,
                'available_copies' => $book->available_copies,
                'is_available' => $book->isAvailable(),
            ]
        ]);
    }
}