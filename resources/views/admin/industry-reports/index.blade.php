@extends('admin.layouts.app')

@section('title', 'Industry Reports')

@section('content')
    <div class="card">
        <div class="card-body border-bottom d-flex flex-wrap gap-2 justify-content-between">
            @component('admin.partials.search', ['placeholder' => 'Title or ID'])
                <select name="status" class="form-select w-auto">
                    <option value="">Any status</option>
                    @foreach (config('admin.content_status') as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === (string) $value)>{{ $label }}</option>
                    @endforeach
                </select>
            @endcomponent
            <a href="{{ route('admin.industry-reports.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New report</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Title</th><th>Sector</th><th>Author</th><th>PDF</th><th class="text-center">Views</th><th>Status</th><th>Created</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($reports as $report)
                    <tr>
                        <td style="max-width:360px">
                            <a href="{{ route('admin.industry-reports.edit', $report) }}" class="fw-semibold text-decoration-none">{{ $report->industryreport_title }}</a>
                            <div class="small text-muted text-truncate">{{ $report->industryreport_home_title }}</div>
                        </td>
                        <td class="small">{{ $report->sector->category_name ?? '—' }}</td>
                        <td class="small">{{ $report->author->author_name ?? '—' }}</td>
                        <td>
                            @if ($report->industryreport_pdf_path)
                                <a href="{{ preg_match('#^https?://#', $report->industryreport_pdf_path) ? $report->industryreport_pdf_path : asset($report->industryreport_pdf_path) }}" target="_blank" title="Open PDF"><i class="bi bi-file-earmark-pdf text-danger fs-5"></i></a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format((int) $report->industryreport_views) }}</td>
                        <td>@include('admin.partials.active-badge', ['active' => $report->industryreport_status, 'on' => 'Published', 'off' => 'Draft'])</td>
                        <td class="small text-nowrap">{{ optional($report->created_at)->format('d M Y') }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.industry-reports.edit', $report) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @include('admin.partials.delete', ['action' => route('admin.industry-reports.destroy', $report)])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 8])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $reports])
    </div>
@endsection
