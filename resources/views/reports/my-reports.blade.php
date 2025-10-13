@extends('layouts.app')

@section('title', 'My Reports')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">My Generated Reports</h2>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Report Name</th>
                        <th>Template</th>
                        <th>Format</th>
                        <th>Generated At</th>
                        <th>Downloads</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports ?? [] as $report)
                    <tr>
                        <td>{{ $report->report_name }}</td>
                        <td>{{ $report->template->name ?? 'N/A' }}</td>
                        <td><span class="badge badge-info">{{ strtoupper($report->format) }}</span></td>
                        <td>{{ $report->generated_at->format('d M Y H:i') }}</td>
                        <td>{{ $report->download_count }}</td>
                        <td>
                            <a href="{{ route('reports.download', $report->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-download"></i> Download
                            </a>
                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this report?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No reports generated yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if(isset($reports) && method_exists($reports, 'links'))
            <div class="mt-3">
                {{ $reports->links() }}
            </div>
            @endif
        </div>
    </div>

    <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">Back to Reports</a>
</div>
@endsection
