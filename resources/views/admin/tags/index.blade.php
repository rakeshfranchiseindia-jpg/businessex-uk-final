@extends('admin.layouts.app')

@section('title', 'Tags')

@section('content')
    <div class="card">
        <div class="card-body border-bottom d-flex flex-wrap gap-2 justify-content-between">
            @component('admin.partials.search', ['placeholder' => 'Tag name'])
            @endcomponent
            <a href="{{ route('admin.tags.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New tag</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>Status</th><th>Created</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($tags as $tag)
                    <tr>
                        <td class="text-muted">#{{ $tag->tag_id }}</td>
                        <td class="fw-medium">{{ $tag->tag_name }}</td>
                        <td class="small text-muted">{{ $tag->tag_slug }}</td>
                        <td>@include('admin.partials.active-badge', ['active' => $tag->tag_status])</td>
                        <td class="small">{{ optional($tag->created_at)->format('d M Y') }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @include('admin.partials.delete', ['action' => route('admin.tags.destroy', $tag)])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 6])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $tags])
    </div>
@endsection
