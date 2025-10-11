<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookIssue;
use App\Models\LibrarySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LibraryController extends Controller
{
    public function dashboard()
    {
        $totalBooks = Book::count();
        $totalCategories = BookCategory::count();
        $availableBooks = Book::where('status', 'available')
            ->where('available_copies', '>', 0)
            ->count();
        $issuedBooks = BookIssue::where('status', 'issued')->count();
        $overdueBooks = BookIssue::overdue()->count();
        $totalFineCollected = BookIssue::where('fine_paid', true)->sum('fine_amount');
        $pendingFines = BookIssue::where('fine_paid', false)
            ->where('fine_amount', '>', 0)
            ->sum('fine_amount');

        // Recent issues
        $recentIssues = BookIssue::with(['book', 'student', 'teacher'])
            ->latest()
            ->limit(10)
            ->get();

        // Popular books
        $popularBooks = Book::withCount('issues')
            ->orderBy('issues_count', 'desc')
            ->limit(10)
            ->get();

        // Category-wise distribution
        $categoryStats = BookCategory::withCount('books')
            ->having('books_count', '>', 0)
            ->get();

        // Monthly issue stats
        $dbDriver = DB::connection()->getDriverName();
        if ($dbDriver === 'sqlite') {
            $monthlyIssues = BookIssue::selectRaw('strftime("%Y-%m", issue_date) as month, COUNT(*) as count')
                ->where('issue_date', '>=', Carbon::now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } else {
            $monthlyIssues = BookIssue::selectRaw('DATE_FORMAT(issue_date, "%Y-%m") as month, COUNT(*) as count')
                ->where('issue_date', '>=', Carbon::now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        return view('library.dashboard', compact(
            'totalBooks',
            'totalCategories',
            'availableBooks',
            'issuedBooks',
            'overdueBooks',
            'totalFineCollected',
            'pendingFines',
            'recentIssues',
            'popularBooks',
            'categoryStats',
            'monthlyIssues'
        ));
    }

    public function categories()
    {
        $categories = BookCategory::withCount('books')->paginate(20);
        return view('library.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('library.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:book_categories,code',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        BookCategory::create($validated);

        return redirect()->route('library.categories')->with('success', 'Category created successfully!');
    }

    public function editCategory(BookCategory $category)
    {
        return view('library.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, BookCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:book_categories,code,' . $category->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $category->update($validated);

        return redirect()->route('library.categories')->with('success', 'Category updated successfully!');
    }

    public function destroyCategory(BookCategory $category)
    {
        if ($category->books()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete category with existing books!');
        }

        $category->delete();

        return redirect()->route('library.categories')->with('success', 'Category deleted successfully!');
    }

    public function settings()
    {
        $settings = LibrarySetting::getSettings();
        return view('library.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'max_books_per_student' => 'required|integer|min:1',
            'max_books_per_teacher' => 'required|integer|min:1',
            'student_issue_days' => 'required|integer|min:1',
            'teacher_issue_days' => 'required|integer|min:1',
            'fine_per_day' => 'required|numeric|min:0',
            'max_renewal_times' => 'required|integer|min:0',
        ]);

        $settings = LibrarySetting::first();
        
        if ($settings) {
            $settings->update($validated);
        } else {
            LibrarySetting::create($validated);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    public function reports(Request $request)
    {
        $reportType = $request->input('report_type', 'issue_summary');
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        $data = [];

        switch ($reportType) {
            case 'issue_summary':
                $data = [
                    'total_issues' => BookIssue::whereBetween('issue_date', [$dateFrom, $dateTo])->count(),
                    'total_returns' => BookIssue::whereBetween('return_date', [$dateFrom, $dateTo])->count(),
                    'active_issues' => BookIssue::where('status', 'issued')->count(),
                    'overdue_issues' => BookIssue::overdue()->count(),
                ];
                break;

            case 'fine_collection':
                $data = [
                    'total_fines' => BookIssue::whereBetween('return_date', [$dateFrom, $dateTo])
                        ->sum('fine_amount'),
                    'collected_fines' => BookIssue::whereBetween('return_date', [$dateFrom, $dateTo])
                        ->where('fine_paid', true)
                        ->sum('fine_amount'),
                    'pending_fines' => BookIssue::where('fine_paid', false)
                        ->where('fine_amount', '>', 0)
                        ->sum('fine_amount'),
                ];
                break;

            case 'book_usage':
                $data = Book::withCount(['issues' => function ($query) use ($dateFrom, $dateTo) {
                    $query->whereBetween('issue_date', [$dateFrom, $dateTo]);
                }])
                    ->orderBy('issues_count', 'desc')
                    ->limit(20)
                    ->get();
                break;

            case 'category_wise':
                $data = BookCategory::withCount(['books', 'books as issued_books' => function ($query) {
                    $query->whereHas('issues', function ($q) {
                        $q->where('status', 'issued');
                    });
                }])->get();
                break;
        }

        return view('library.reports', compact('reportType', 'dateFrom', 'dateTo', 'data'));
    }

    public function statistics()
    {
        // Overall statistics
        $stats = [
            'books' => [
                'total' => Book::count(),
                'available' => Book::where('status', 'available')->count(),
                'unavailable' => Book::where('status', 'unavailable')->count(),
                'total_copies' => Book::sum('total_copies'),
                'available_copies' => Book::sum('available_copies'),
            ],
            'issues' => [
                'total' => BookIssue::count(),
                'issued' => BookIssue::where('status', 'issued')->count(),
                'returned' => BookIssue::where('status', 'returned')->count(),
                'overdue' => BookIssue::overdue()->count(),
                'lost' => BookIssue::where('status', 'lost')->count(),
            ],
            'fines' => [
                'total' => BookIssue::sum('fine_amount'),
                'collected' => BookIssue::where('fine_paid', true)->sum('fine_amount'),
                'pending' => BookIssue::where('fine_paid', false)->sum('fine_amount'),
            ],
        ];

        // Yearly comparison
        $currentYear = Carbon::now()->year;
        $yearlyComparison = [];
        for ($i = 0; $i < 3; $i++) {
            $year = $currentYear - $i;
            $yearlyComparison[$year] = [
                'issues' => BookIssue::whereYear('issue_date', $year)->count(),
                'returns' => BookIssue::whereYear('return_date', $year)->count(),
                'fines' => BookIssue::whereYear('return_date', $year)->sum('fine_amount'),
            ];
        }

        return view('library.statistics', compact('stats', 'yearlyComparison'));
    }
}