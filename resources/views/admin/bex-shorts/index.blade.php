@extends('admin.layouts.app')

@section('title', 'BEX Shorts')

@section('content')
    <div class="card">
        <div class="card-body border-bottom d-flex flex-wrap gap-2 justify-content-between">
            @component('admin.partials.search', ['placeholder' => 'Title or ID'])
            @endcomponent
            <a href="{{ route('admin.bex-shorts.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New BEX short</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th></th><th>Title</th><th>Reference</th><th>Author</th><th>Status</th><th>Created</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($shorts as $short)
                    <tr>
                        <td style="width:60px">
                            @if ($short->image_path)
                                <img src="{{ preg_match('#^https?://#', $short->image_path) ? $short->image_path : asset($short->image_path) }}" class="thumb" alt="" onerror="this.style.visibility='hidden'">
                            @endif
                        </td>
                        <td style="max-width:360px">
                            <a href="{{ route('admin.bex-shorts.edit', $short) }}" class="fw-semibold text-decoration-none">{{ $short->bex_title }}</a>
                            <div class="small text-muted text-truncate">{{ \Illuminate\Support\Str::limit(strip_tags($short->bex_description), 90) }}</div>
                        </td>
                        <td class="small">
                            @if ($short->reference_page_link)
                                <a href="{{ $short->reference_page_link }}" target="_blank" rel="noopener">{{ $short->reference_page_name ?: 'Link' }}</a>
                            @else
                                {{ $short->reference_page_name ?: '—' }}
                            @endif
                        </td>
                        <td class="small">{{ $short->author->author_name ?? '—' }}</td>
                        <td>@include('admin.partials.active-badge', ['active' => $short->status, 'on' => 'Published', 'off' => 'Draft'])</td>
                        <td class="small text-nowrap">{{ optional($short->created_at)->format('d M Y') }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.bex-shorts.edit', $short) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @include('admin.partials.delete', ['action' => route('admin.bex-shorts.destroy', $short)])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 7])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $shorts])
    </div>
@endsection
