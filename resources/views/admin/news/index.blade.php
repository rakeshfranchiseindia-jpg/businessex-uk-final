@extends('admin.layouts.app')

@section('title', 'News')

@section('content')
    <div class="card">
        <div class="card-body border-bottom d-flex flex-wrap gap-2 justify-content-between">
            @component('admin.partials.search', ['placeholder' => 'Title, tag or ID'])
                <select name="status" class="form-select w-auto">
                    <option value="">Any status</option>
                    @foreach (config('admin.content_status') as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === (string) $value)>{{ $label }}</option>
                    @endforeach
                </select>
            @endcomponent
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New news</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th></th><th>Title</th><th>Author</th><th class="text-center">Views</th><th>Status</th><th>Created</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($news as $item)
                    @php($image = $item->listing_image_path ?: $item->image_path)
                    <tr>
                        <td style="width:60px">
                            @if ($image)
                                <img src="{{ preg_match('#^https?://#', $image) ? $image : asset($image) }}" class="thumb" alt="" onerror="this.style.visibility='hidden'">
                            @endif
                        </td>
                        <td style="max-width:380px">
                            <a href="{{ route('admin.news.edit', $item) }}" class="fw-semibold text-decoration-none">{{ $item->news_title }}</a>
                            <div class="small text-muted text-truncate">{{ $item->short_desc }}</div>
                        </td>
                        <td class="small">{{ $item->author->author_name ?? '—' }}</td>
                        <td class="text-center">{{ number_format((int) $item->news_views) }}</td>
                        <td>@include('admin.partials.active-badge', ['active' => $item->news_status, 'on' => 'Published', 'off' => 'Draft'])</td>
                        <td class="small text-nowrap">{{ optional($item->created_at)->format('d M Y') }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @include('admin.partials.delete', ['action' => route('admin.news.destroy', $item)])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 7])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $news])
    </div>
@endsection
