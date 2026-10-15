@extends('admin.layouts.app')

@section('title', 'Conversation #'.$conversation->id)
@section('subtitle', ucfirst((string) $conversation->profile_type).' profile #'.$conversation->profile_id)

@section('content')
    <a href="{{ route('admin.conversations.index') }}" class="btn btn-light mb-3"><i class="bi bi-arrow-left me-1"></i>Back</a>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Messages ({{ $conversation->messages->count() }})</div>
                <div class="card-body" style="background:#f9fafc">
                    @forelse ($conversation->messages as $message)
                        @php($fromOwner = (int) $message->sender_user_id === (int) $conversation->owner_user_id)
                        <div class="d-flex mb-3 {{ $fromOwner ? 'justify-content-end' : '' }}">
                            <div class="p-3 rounded-3 shadow-sm {{ $fromOwner ? 'bg-primary-subtle' : 'bg-white' }}" style="max-width:80%">
                                <div class="small fw-semibold mb-1">{{ optional($message->sender)->name ?? 'User #'.$message->sender_user_id }}</div>
                                <div style="white-space:pre-wrap">{{ $message->message }}</div>
                                <div class="small text-muted mt-1">{{ optional($message->created_at)->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No messages in this conversation.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">Participants</div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item">
                        <div class="text-muted">From</div>
                        <div class="fw-medium">{{ $conversation->sender_name ?: optional($conversation->sender)->name }}</div>
                        <div>{{ $conversation->sender_email ?: optional($conversation->sender)->email }}</div>
                        <div>{{ $conversation->sender_phone }}</div>
                    </li>
                    <li class="list-group-item">
                        <div class="text-muted">To (profile owner)</div>
                        <div class="fw-medium">{{ optional($conversation->owner)->name ?? '—' }}</div>
                        <div>{{ optional($conversation->owner)->email }}</div>
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-header">Proposals ({{ $conversation->proposals->count() }})</div>
                <ul class="list-group list-group-flush small">
                    @forelse ($conversation->proposals as $proposal)
                        <li class="list-group-item d-flex justify-content-between">
                            <a href="{{ route('admin.proposals.show', $proposal) }}">{{ $proposal->title }}</a>
                            <span class="badge text-bg-light">{{ ucfirst($proposal->status) }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No proposals.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
