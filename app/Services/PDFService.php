<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class PDFService
{
    public function generateStudentReport($students, $filters)
    {
        $data = [
            'students' => $students,
            'filters' => $filters,
            'generated_at' => now()->format('d M Y, h:i A'),
        ];

        $pdf = Pdf::loadView('pdfs.student-report', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('student-report-' . now()->format('YmdHis') . '.pdf');
    }

    public function generateAttendanceReport($attendances, $summary, $filters)
    {
        $data = [
            'attendances' => $attendances,
            'summary' => $summary,
            'filters' => $filters,
            'generated_at' => now()->format('d M Y, h:i A'),
        ];

        $pdf = Pdf::loadView('pdfs.attendance-report', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('attendance-report-' . now()->format('YmdHis') . '.pdf');
    }

    public function generateFeeReport($collections, $summary, $filters)
    {
        $data = [
            'collections' => $collections,
            'summary' => $summary,
            'filters' => $filters,
            'generated_at' => now()->format('d M Y, h:i A'),
        ];

        $pdf = Pdf::loadView('pdfs.fee-report', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('fee-report-' . now()->format('YmdHis') . '.pdf');
    }

    public function generateExamReport($exam, $studentResults, $filters)
    {
        $data = [
            'exam' => $exam,
            'studentResults' => $studentResults,
            'filters' => $filters,
            'generated_at' => now()->format('d M Y, h:i A'),
        ];

        $pdf = Pdf::loadView('pdfs.exam-report', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('exam-report-' . now()->format('YmdHis') . '.pdf');
    }

    public function generateTeacherReport($teachers, $filters)
    {
        $data = [
            'teachers' => $teachers,
            'filters' => $filters,
            'generated_at' => now()->format('d M Y, h:i A'),
        ];

        $pdf = Pdf::loadView('pdfs.teacher-report', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('teacher-report-' . now()->format('YmdHis') . '.pdf');
    }

    public function generateFinancialReport($income, $expenses, $summary, $filters)
    {
        $data = [
            'income' => $income,
            'expenses' => $expenses,
            'summary' => $summary,
            'filters' => $filters,
            'generated_at' => now()->format('d M Y, h:i A'),
        ];

        $pdf = Pdf::loadView('pdfs.financial-report', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('financial-report-' . now()->format('YmdHis') . '.pdf');
    }

    public function generateFromTemplate($template, $data, $parameters)
    {
        $viewData = [
            'template' => $template,
            'data' => $data,
            'parameters' => $parameters,
            'generated_at' => now()->format('d M Y, h:i A'),
        ];

        $pdf = Pdf::loadView('pdfs.custom-report', $viewData);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    public function generateStudentCard($student)
    {
        $data = [
            'student' => $student,
            'generated_at' => now()->format('d M Y'),
        ];

        $pdf = Pdf::loadView('pdfs.student-card', $data);
        $pdf->setPaper([0, 0, 226.77, 153.07]); // ID card size (54mm x 86mm)
        
        return $pdf->download('student-card-' . $student->admission_number . '.pdf');
    }

    public function generateAdmitCard($student, $exam)
    {
        $data = [
            'student' => $student,
            'exam' => $exam,
            'generated_at' => now()->format('d M Y'),
        ];

        $pdf = Pdf::loadView('pdfs.admit-card', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('admit-card-' . $student->admission_number . '-' . $exam->id . '.pdf');
    }

    public function generateMarksheet($student, $exam, $marks)
    {
        $data = [
            'student' => $student,
            'exam' => $exam,
            'marks' => $marks,
            'total' => $marks->sum('marks_obtained'),
            'percentage' => $marks->avg('percentage'),
            'result' => $marks->every(fn($m) => $m->marks_obtained >= $m->passing_marks) ? 'Pass' : 'Fail',
            'generated_at' => now()->format('d M Y'),
        ];

        $pdf = Pdf::loadView('pdfs.marksheet', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('marksheet-' . $student->admission_number . '-' . $exam->id . '.pdf');
    }

    public function generateFeeReceipt($feeCollection)
    {
        $data = [
            'collection' => $feeCollection,
            'student' => $feeCollection->student,
            'generated_at' => now()->format('d M Y, h:i A'),
        ];

        $pdf = Pdf::loadView('pdfs.fee-receipt', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('fee-receipt-' . $feeCollection->receipt_number . '.pdf');
    }

    public function generateSalarySlip($salary)
    {
        $data = [
            'salary' => $salary,
            'teacher' => $salary->teacher,
            'generated_at' => now()->format('d M Y'),
        ];

        $pdf = Pdf::loadView('pdfs.salary-slip', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('salary-slip-' . $salary->teacher->employee_id . '-' . $salary->month . '.pdf');
    }

    public function generateCertificate($student, $type = 'character')
    {
        $data = [
            'student' => $student,
            'type' => $type,
            'issued_at' => now()->format('d M Y'),
        ];

        $view = match($type) {
            'character' => 'pdfs.character-certificate',
            'bonafide' => 'pdfs.bonafide-certificate',
            'transfer' => 'pdfs.transfer-certificate',
            default => 'pdfs.character-certificate',
        };

        $pdf = Pdf::loadView($view, $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download($type . '-certificate-' . $student->admission_number . '.pdf');
    }

    public function generateLeaveApplication($leave)
    {
        $data = [
            'leave' => $leave,
            'applicant' => $leave->applicable,
            'generated_at' => now()->format('d M Y, h:i A'),
        ];

        $pdf = Pdf::loadView('pdfs.leave-application', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('leave-application-' . $leave->id . '.pdf');
    }

    public function generateTimeTable($class, $schedules)
    {
        $data = [
            'class' => $class,
            'schedules' => $schedules,
            'generated_at' => now()->format('d M Y'),
        ];

        $pdf = Pdf::loadView('pdfs.timetable', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('timetable-' . $class->name . '.pdf');
    }
}
