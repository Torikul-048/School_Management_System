<?php

namespace App\Http\Controllers;

use App\Models\ReportTemplate;
use App\Models\SavedReport;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\FeeCollection;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Mark;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $pdfService;

    public function __construct(PDFService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function index()
    {
        $templates = ReportTemplate::with('creator')
            ->active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('reports.index', compact('templates'));
    }

    public function templates()
    {
        $templates = ReportTemplate::with('creator')
            ->active()
            ->get();

        return response()->json($templates);
    }

    public function studentReport(Request $request)
    {
        $request->validate([
            'class_id' => 'nullable|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'status' => 'nullable|in:active,inactive,graduated,transferred',
        ]);

        $query = Student::with('class', 'section', 'parent');

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('admission_number')->get();

        if ($request->format === 'pdf') {
            return $this->pdfService->generateStudentReport($students, $request->all());
        }

        return view('reports.students', compact('students'));
    }

    public function attendanceReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'class_id' => 'nullable|exists:classes,id',
            'student_id' => 'nullable|exists:students,id',
        ]);

        $query = Attendance::with('attendable')
            ->whereBetween('date', [$request->start_date, $request->end_date]);

        if ($request->class_id) {
            $query->whereHas('attendable', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            })->where('attendable_type', 'App\\Models\\Student');
        }

        if ($request->student_id) {
            $query->where('attendable_id', $request->student_id)
                  ->where('attendable_type', 'App\\Models\\Student');
        }

        $attendances = $query->orderBy('date')->get();

        $summary = [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
        ];

        if ($request->format === 'pdf') {
            return $this->pdfService->generateAttendanceReport($attendances, $summary, $request->all());
        }

        return view('reports.attendance', compact('attendances', 'summary'));
    }

    public function feeReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|in:paid,unpaid,partial',
        ]);

        $collections = FeeCollection::with('student', 'feeType', 'collector')
            ->whereBetween('payment_date', [$request->start_date, $request->end_date]);

        if ($request->status) {
            $collections->where('status', $request->status);
        }

        $data = $collections->orderBy('payment_date', 'desc')->get();

        $summary = [
            'total' => $data->sum('paid_amount'),
            'count' => $data->count(),
            'by_method' => $data->groupBy('payment_method')->map->sum('paid_amount'),
        ];

        if ($request->format === 'pdf') {
            return $this->pdfService->generateFeeReport($data, $summary, $request->all());
        }

        return view('reports.fees', compact('data', 'summary'));
    }

    public function examReport(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $exam = Exam::findOrFail($request->exam_id);

        $query = Mark::with('student', 'subject', 'exam')
            ->where('exam_id', $request->exam_id);

        if ($request->class_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $marks = $query->get();

        $studentResults = $marks->groupBy('student_id')->map(function ($studentMarks) {
            $student = $studentMarks->first()->student;
            return [
                'student' => $student,
                'marks' => $studentMarks,
                'total' => $studentMarks->sum('marks_obtained'),
                'percentage' => $studentMarks->avg('percentage'),
                'result' => $studentMarks->every(fn($m) => $m->marks_obtained >= $m->passing_marks) ? 'Pass' : 'Fail',
            ];
        })->sortByDesc('total');

        if ($request->format === 'pdf') {
            return $this->pdfService->generateExamReport($exam, $studentResults, $request->all());
        }

        return view('reports.exam', compact('exam', 'studentResults'));
    }

    public function teacherReport(Request $request)
    {
        $request->validate([
            'department' => 'nullable|string',
            'status' => 'nullable|in:active,inactive,on_leave',
        ]);

        $query = Teacher::with('subjects', 'classes');

        if ($request->department) {
            $query->where('department', $request->department);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $teachers = $query->orderBy('employee_id')->get();

        if ($request->format === 'pdf') {
            return $this->pdfService->generateTeacherReport($teachers, $request->all());
        }

        return view('reports.teachers', compact('teachers'));
    }

    public function financialReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $income = FeeCollection::whereBetween('payment_date', [$request->start_date, $request->end_date])
            ->selectRaw('DATE(payment_date) as date, SUM(paid_amount) as amount')
            ->groupBy('date')
            ->get();

        $expenses = DB::table('expenses')
            ->whereBetween('expense_date', [$request->start_date, $request->end_date])
            ->where('status', 'approved')
            ->selectRaw('DATE(expense_date) as date, category, SUM(amount) as amount')
            ->groupBy(DB::raw('DATE(expense_date)'), 'category')
            ->get();

        $summary = [
            'total_income' => $income->sum('amount'),
            'total_expense' => $expenses->sum('amount'),
            'net' => $income->sum('amount') - $expenses->sum('amount'),
            'expense_by_category' => $expenses->groupBy('category')->map->sum('amount'),
        ];

        if ($request->format === 'pdf') {
            return $this->pdfService->generateFinancialReport($income, $expenses, $summary, $request->all());
        }

        return view('reports.financial', compact('income', 'expenses', 'summary'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:report_templates,id',
            'parameters' => 'nullable|array',
            'format' => 'required|in:pdf,excel,csv',
        ]);

        $template = ReportTemplate::findOrFail($request->template_id);

        // Generate report based on template
        $data = $this->executeReportQuery($template, $request->parameters ?? []);

        // Generate file
        $fileName = $this->generateReportFile($template, $data, $request->format, $request->parameters ?? []);

        // Save report record
        $savedReport = SavedReport::create([
            'report_template_id' => $template->id,
            'user_id' => auth()->id(),
            'report_name' => $template->name . ' - ' . now()->format('Y-m-d H:i'),
            'parameters' => $request->parameters ?? [],
            'filters' => $request->only(['start_date', 'end_date', 'class_id', 'status']),
            'file_path' => $fileName,
            'format' => $request->format,
            'generated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'report' => $savedReport,
            'download_url' => route('reports.download', $savedReport->id),
        ]);
    }

    public function download($id)
    {
        $report = SavedReport::findOrFail($id);

        if (!Storage::exists($report->file_path)) {
            return back()->with('error', 'Report file not found.');
        }

        $report->incrementDownloads();

        return Storage::download($report->file_path);
    }

    public function myReports()
    {
        $reports = SavedReport::with('template')
            ->where('user_id', auth()->id())
            ->orderBy('generated_at', 'desc')
            ->paginate(20);

        return view('reports.my-reports', compact('reports'));
    }

    public function destroy($id)
    {
        $report = SavedReport::findOrFail($id);

        if ($report->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return back()->with('error', 'Unauthorized action.');
        }

        if (Storage::exists($report->file_path)) {
            Storage::delete($report->file_path);
        }

        $report->delete();

        return back()->with('success', 'Report deleted successfully.');
    }

    private function executeReportQuery($template, $parameters)
    {
        // This would execute the report query based on template
        // For now, return empty array - implement based on your needs
        return [];
    }

    private function generateReportFile($template, $data, $format, $parameters)
    {
        $fileName = 'reports/' . $template->slug . '-' . now()->format('YmdHis') . '.' . $format;

        switch ($format) {
            case 'pdf':
                $pdf = $this->pdfService->generateFromTemplate($template, $data, $parameters);
                Storage::put($fileName, $pdf->output());
                break;
            
            case 'excel':
            case 'csv':
                // Implement Excel/CSV generation
                break;
        }

        return $fileName;
    }
}
