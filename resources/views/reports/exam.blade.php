@extends('layouts.app')

@section('title', 'Exam Report')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Exam Performance Report</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <select name="exam_id" class="form-control" required>
                            <option value="">Select Exam</option>
                            @foreach(\App\Models\Exam::all() as $ex)
                            <option value="{{ $ex->id }}" {{ request('exam_id') == $ex->id ? 'selected' : '' }}>{{ $ex->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="class_id" class="form-control">
                            <option value="">All Classes</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Generate</button>
                        <button type="button" class="btn btn-success" onclick="exportPDF()">PDF</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($exam))
    <div class="card">
        <div class="card-header"><h5>{{ $exam->name }} - Results</h5></div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr><th>Student</th><th>Class</th><th>Total Marks</th><th>Percentage</th><th>Grade</th></tr>
                </thead>
                <tbody>
                    @forelse($studentResults as $result)
                    <tr>
                        <td>{{ $result->student_name }}</td>
                        <td>{{ $result->class_name }}</td>
                        <td>{{ $result->total_marks }}</td>
                        <td>{{ number_format($result->percentage, 2) }}%</td>
                        <td><span class="badge badge-success">{{ $result->grade }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">No results</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

@push('scripts')
<script>
function exportPDF() {
    window.location.href = "{{ route('reports.exams') }}?" + new URLSearchParams(new FormData(document.querySelector('form'))).toString() + '&format=pdf';
}
</script>
@endpush
