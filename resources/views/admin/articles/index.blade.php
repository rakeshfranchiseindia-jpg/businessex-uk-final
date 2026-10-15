@extends('admin.layouts.app')

@section('title', 'Articles')

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
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New article</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th></th><th>Title</th><th>Author</th><th class="text-center">Views</th><th class="text-center">Comments</th><th>Status</th><th>Created</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($articles as $article)
                    @php($image = $article->listing_image_path ?: $article->image_path)
                    <tr>
                        <td style="width:60px">
                            @if ($image)
                                <img src="{{ preg_match('#^https?://#', $image) ? $image : asset($image) }}" class="thumb" alt="" onerror="this.style.visibility='hidden'">
                            @endif
                        </td>
                        <td style="max-width:380px">
                            <a href="{{ route('admin.articles.edit', $article) }}" class="fw-semibold text-decoration-none">{{ $article->article_title }}</a>
                            <div class="small text-muted text-truncate">{{ $article->article_tags }}</div>
                        </td>
                        <td class="small">{{ $article->author->author_name ?? '—' }}</td>
                        <td class="text-center">{{ number_format((int) $article->article_views) }}</td>
                        <td class="text-center">{{ $article->comments_count }}</td>
                        <td>@include('admin.partials.active-badge', ['active' => $article->article_status, 'on' => 'Published', 'off' => 'Draft'])</td>
                        <td class="small text-nowrap">{{ optional($article->created_at)->format('d M Y') }}</td>
                        <td class="table-actions">
                            <a href="{{ route('articles.show', $article->article_id) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="View on site"><i class="bi bi-box-arrow-up-right"></i></a>
                            <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @include('admin.partials.delete', ['action' => route('admin.articles.destroy', $article), 'confirm' => 'Delete this article and its comments?'])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 8])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $articles])
    </div>
@endsection
