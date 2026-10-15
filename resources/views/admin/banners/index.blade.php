@extends('admin.layouts.app')

@section('title', 'Banners')
@section('subtitle', 'Google Ad Manager (DFP) ad slots')

@section('content')
    <div class="card">
        <div class="card-body border-bottom d-flex flex-wrap gap-2 justify-content-between">
            <form method="GET" class="d-flex gap-2">
                <select name="page_id" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">All pages</option>
                    @foreach (config('admin.banner_page') as $value => $label)
                        <option value="{{ $value }}" @selected(request('page_id') === (string) $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New banner</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Page</th><th>Location</th><th>DFP ID</th><th>Slot</th><th>Size</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($banners as $banner)
                    <tr>
                        <td class="fw-medium">{{ config('admin.banner_page')[$banner->page] ?? $banner->page }}</td>
                        <td>{{ config('admin.banner_location')[$banner->location] ?? $banner->location }}</td>
                        <td class="small text-break" style="max-width:220px">{{ $banner->dfp_id }}</td>
                        <td class="small text-break" style="max-width:220px">{{ $banner->dfp_slot }}</td>
                        <td class="small text-nowrap">{{ $banner->width }} &times; {{ $banner->height }}</td>
                        <td>@include('admin.partials.active-badge', ['active' => $banner->is_active])</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @include('admin.partials.delete', ['action' => route('admin.banners.destroy', $banner)])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 7])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $banners])
    </div>
@endsection
