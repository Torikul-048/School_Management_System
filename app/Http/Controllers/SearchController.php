<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Event;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return redirect()->back()->with('error', 'Please enter a search term');
        }

        // Search across multiple models
        $results = [
            'students' => $this->searchStudents($query),
            'teachers' => $this->searchTeachers($query),
            'classes' => $this->searchClasses($query),
            'subjects' => $this->searchSubjects($query),
            'events' => $this->searchEvents($query),
            'announcements' => $this->searchAnnouncements($query),
        ];

        $totalResults = collect($results)->sum(fn($items) => $items->count());

        return view('search.results', compact('query', 'results', 'totalResults'));
    }

    private function searchStudents($query)
    {
        return Student::where(function($q) use ($query) {
            $q->where('first_name', 'LIKE', "%{$query}%")
              ->orWhere('last_name', 'LIKE', "%{$query}%")
              ->orWhere('admission_number', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('phone', 'LIKE', "%{$query}%");
        })
        ->with('class')
        ->limit(10)
        ->get();
    }

    private function searchTeachers($query)
    {
        return Teacher::where(function($q) use ($query) {
            $q->where('first_name', 'LIKE', "%{$query}%")
              ->orWhere('last_name', 'LIKE', "%{$query}%")
              ->orWhere('employee_id', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('phone', 'LIKE', "%{$query}%")
              ->orWhere('subject_specialization', 'LIKE', "%{$query}%");
        })
        ->limit(10)
        ->get();
    }

    private function searchClasses($query)
    {
        return Classes::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->with('academicYear')
            ->limit(10)
            ->get();
    }

    private function searchSubjects($query)
    {
        return Subject::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->with('class')
            ->limit(10)
            ->get();
    }

    private function searchEvents($query)
    {
        return Event::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->where('start_date', '>=', now()->subMonths(3))
            ->limit(10)
            ->get();
    }

    private function searchAnnouncements($query)
    {
        return Announcement::where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->where('status', 'published')
            ->latest()
            ->limit(10)
            ->get();
    }
}
