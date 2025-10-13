@extends('layouts.app')

@section('title', 'Attendance Report')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">Attendance Report</h2>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Filters</div>
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" required>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Generate</button>
                                <button type="button" class="btn btn-success" onclick="exportPDF()">PDF</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($attendances))
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3>{{ $summary['total_present'] ?? 0 }}</h3>
                    <p>Present</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h3>{{ $summary['total_absent'] ?? 0 }}</h3>
                    <p>Absent</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h3>{{ $summary['total_late'] ?? 0 }}</h3>
                    <p>Late</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3>{{ number_format($summary['avg_percentage'] ?? 0, 2) }}%</h3>
                    <p>Avg Attendance</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Attendance Records</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Late</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $att)
                            <tr>
                                <td>{{ $att->date }}</td>
                                <td>{{ $att->class->name ?? 'N/A' }}</td>
                                <td>{{ $att->subject->name ?? 'N/A' }}</td>
                                <td>{{ $att->present_count }}</td>
                                <td>{{ $att->absent_count }}</td>
                                <td>{{ $att->late_count }}</td>
                                <td>{{ number_format($att->attendance_percentage, 2) }}%</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center">No records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mt-3">
        <div class="col-md-12">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function exportPDF() {
        window.location.href = "{{ route('reports.attendance') }}?" + new URLSearchParams(new FormData(document.querySelector('form'))).toString() + '&format=pdf';
    }
</script>
@endpush
