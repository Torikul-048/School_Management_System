@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">Reports</h2>
            <p class="text-muted">Generate and download various reports</p>
        </div>
    </div>

    <!-- Report Templates -->
    <div class="row">
        @foreach($templates ?? [] as $template)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-{{ $template->category == 'financial' ? 'success' : ($template->category == 'academic' ? 'primary' : 'info') }}  text-white">
                    <h5 class="card-title mb-0">{{ $template->name }}</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $template->description }}</p>
                    <p class="text-muted"><small><i class="fas fa-tag"></i> {{ ucfirst($template->category) }}</small></p>
                </div>
                <div class="card-footer">
                    @if($template->slug == 'student-list-report')
                        <a href="{{ route('reports.students') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-file-alt"></i> Generate Report
                        </a>
                    @elseif($template->slug == 'daily-attendance-report')
                        <a href="{{ route('reports.attendance') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-file-alt"></i> Generate Report
                        </a>
                    @elseif($template->slug == 'fee-collection-report')
                        <a href="{{ route('reports.fees') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-file-alt"></i> Generate Report
                        </a>
                    @elseif($template->slug == 'student-performance-report')
                        <a href="{{ route('reports.exams') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-file-alt"></i> Generate Report
                        </a>
                    @elseif($template->slug == 'teacher-list-report')
                        <a href="{{ route('reports.teachers') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-file-alt"></i> Generate Report
                        </a>
                    @elseif($template->slug == 'monthly-income-report' || $template->slug == 'expense-report')
                        <a href="{{ route('reports.financial') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-file-alt"></i> Generate Report
                        </a>
                    @else
                        <form action="{{ route('reports.generate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="template_id" value="{{ $template->id }}">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-file-alt"></i> Generate Report
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- My Reports -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">My Reports</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('reports.my-reports') }}" class="btn btn-outline-primary">
                        <i class="fas fa-history"></i> View My Generated Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
