<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Payroll;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    protected $pdfService;

    public function __construct(PDFService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    protected function getTeacher()
    {
        return Teacher::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $teacher = $this->getTeacher();
        
        $salarySlips = Payroll::where('teacher_id', $teacher->id)
            ->with('items')
            ->orderBy('month', 'desc')
            ->orderBy('year', 'desc')
            ->paginate(12);
        
        return view('teacher.salary.index', compact('teacher', 'salarySlips'));
    }

    public function slip($id)
    {
        $teacher = $this->getTeacher();
        
        $payroll = Payroll::where('teacher_id', $teacher->id)
            ->with(['teacher.user', 'items'])
            ->findOrFail($id);
        
        return view('teacher.salary.slip', compact('teacher', 'payroll'));
    }

    public function download($id)
    {
        $teacher = $this->getTeacher();
        
        $payroll = Payroll::where('teacher_id', $teacher->id)
            ->with(['teacher.user', 'items'])
            ->findOrFail($id);
        
        $pdf = $this->pdfService->generate('teacher.salary.slip-pdf', ['payroll' => $payroll]);
        
        return $pdf->download('salary-slip-' . $payroll->month . '-' . $payroll->year . '.pdf');
    }
}
