@extends('layouts.app')

@section('title', 'Teacher Report')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Teacher Report</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <select name="department" class="form-control">
                            <option value="">All Departments</option>
                            <option value="Science">Science</option>
                            <option value="Arts">Arts</option>
                            <option value="Commerce">Commerce</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="on_leave">On Leave</option>
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

    <div class="card">
        <div class="card-header"><h5>Teacher List ({{ count($teachers ?? []) }})</h5></div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr><th>Employee ID</th><th>Name</th><th>Department</th><th>Designation</th><th>Experience</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($teachers ?? [] as $teacher)
                    <tr>
                        <td>{{ $teacher->employee_id }}</td>
                        <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                        <td>{{ $teacher->department }}</td>
                        <td>{{ $teacher->designation }}</td>
                        <td>{{ $teacher->experience_years }} years</td>
                        <td><span class="badge badge-{{ $teacher->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($teacher->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">No teachers</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

@push('scripts')
<script>
function exportPDF() {
    window.location.href = "{{ route('reports.teachers') }}?" + new URLSearchParams(new FormData(document.querySelector('form'))).toString() + '&format=pdf';
}
</script>
@endpush
