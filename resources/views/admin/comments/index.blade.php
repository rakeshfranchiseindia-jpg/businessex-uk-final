@extends('admin.layouts.app')

@section('title', 'Article Comments')

@section('content')
    <div class="card">
        <div class="card-body border-bottom">
            @component('admin.partials.search', ['placeholder' => 'Name, email or comment'])
                <select name="status" class="form-select w-auto">
                    <option value="">Any status</option>
                    <option value="1" @selected(request('status') === '1')>Approved</option>
                    <option value="0" @selected(request('status') === '0')>Hidden</option>
                </select>
            @endcomponent
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Comment</th><th>Article</th><th>By</th><th>Status</th><th>Date</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($comments as $comment)
                    <tr>
                        <td style="max-width:380px" class="small">{{ \Illuminate\Support\Str::limit($comment->comment_detail, 160) }}</td>
                        <td class="small" style="max-width:220px">
                            @if ($comment->article)
                                <a href="{{ route('articles.show', $comment->article_id) }}" target="_blank" class="text-decoration-none">{{ \Illuminate\Support\Str::limit($comment->article->article_title, 60) }}</a>
                            @else
                                <span class="text-muted">Deleted article</span>
                            @endif
                        </td>
                        <td class="small">
                            <div>{{ $comment->comment_name }}</div>
                            <div class="text-muted">{{ $comment->comment_email }}</div>
                        </td>
                        <td>@include('admin.partials.active-badge', ['active' => $comment->comment_status, 'on' => 'Approved', 'off' => 'Hidden'])</td>
                        <td class="small text-nowrap">{{ optional($comment->created_at)->format('d M Y') }}</td>
                        <td class="table-actions">
                            <form method="POST" action="{{ route('admin.comments.toggle', $comment) }}">
                                @csrf
                                @if ($comment->comment_status)
                                    <button class="btn btn-sm btn-outline-secondary" title="Hide"><i class="bi bi-eye-slash"></i></button>
                                @else
                                    <button class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check-lg"></i></button>
                                @endif
                            </form>
                            @include('admin.partials.delete', ['action' => route('admin.comments.destroy', $comment), 'confirm' => 'Delete this comment?'])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 6])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $comments])
    </div>
@endsection
