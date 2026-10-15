@extends('admin.layouts.app')

@section('title', 'Proposal #'.$proposal->id)
@section('subtitle', $proposal->title)

@section('content')
    <a href="{{ route('admin.proposals.index') }}" class="btn btn-light mb-3"><i class="bi bi-arrow-left me-1"></i>Back</a>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ $proposal->title }}
                    @include('admin.partials.proposal-status', ['status' => $proposal->status])
                </div>
                <div class="card-body">
                    <div style="white-space:pre-wrap">{{ $proposal->description }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <dl class="detail-list mb-0">
                        <dt>Amount</dt><dd>{{ $proposal->amount !== null ? number_format((float) $proposal->amount, 2) : '—' }}</dd>
                        <dt>From</dt><dd>{{ optional($proposal->sender)->name }} <span class="text-muted">{{ optional($proposal->sender)->email }}</span></dd>
                        <dt>To</dt><dd>{{ optional($proposal->recipient)->name }} <span class="text-muted">{{ optional($proposal->recipient)->email }}</span></dd>
                        <dt>Profile</dt><dd>{{ ucfirst((string) $proposal->profile_type) }} #{{ $proposal->profile_id }}</dd>
                        <dt>Sent</dt><dd>{{ optional($proposal->created_at)->format('d M Y, H:i') }}</dd>
                        <dt>Responded</dt><dd>{{ optional($proposal->responded_at)->format('d M Y, H:i') ?? '—' }}</dd>
                        @if ($proposal->conversation)
                            <dt>Conversation</dt><dd class="mb-0"><a href="{{ route('admin.conversations.show', $proposal->conversation) }}">#{{ $proposal->conversation_id }}</a></dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
