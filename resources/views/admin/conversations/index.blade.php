@extends('admin.layouts.app')

@section('title', 'Conversations')
@section('subtitle', 'Contact requests between members (insta-response)')

@section('content')
    <ul class="nav nav-tabs nav-tabs-status mb-3">
        @foreach (['' => 'All', 'business' => 'Business', 'investor' => 'Investor', 'mentor' => 'Mentor', 'startup' => 'Startup'] as $value => $label)
            <li class="nav-item">
                <a class="nav-link {{ (string) request('type') === $value ? 'active' : '' }}" href="{{ route('admin.conversations.index', array_filter(['type' => $value])) }}">{{ $label }}</a>
            </li>
        @endforeach
    </ul>
    <div class="card">
        <div class="card-body border-bottom">
            @component('admin.partials.search', ['placeholder' => 'Sender name, email or profile ID'])
                @if (request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
            @endcomponent
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>ID</th><th>From</th><th>To (profile owner)</th><th>Profile</th><th class="text-center">Messages</th><th class="text-center">Proposals</th><th>Last activity</th><th></th></tr></thead>
                <tbody>
                @forelse ($conversations as $conversation)
                    @php($slug = $conversation->profile_type)
                    <tr>
                        <td class="text-muted">#{{ $conversation->id }}</td>
                        <td class="small">
                            <div class="fw-medium">{{ $conversation->sender_name ?: optional($conversation->sender)->name }}</div>
                            <div class="text-muted">{{ $conversation->sender_email ?: optional($conversation->sender)->email }}</div>
                        </td>
                        <td class="small">
                            <div class="fw-medium">{{ optional($conversation->owner)->name ?? '—' }}</div>
                            <div class="text-muted">{{ optional($conversation->owner)->email }}</div>
                        </td>
                        <td class="small">
                            @if (in_array($slug, \App\Support\Admin\ProfileTypes::slugs(), true) && auth('admin')->user()->canAccess('profiles'))
                                <a href="{{ route('admin.profiles.show', [$slug, $conversation->profile_id]) }}">{{ ucfirst($slug) }} #{{ $conversation->profile_id }}</a>
                            @else
                                {{ ucfirst((string) $slug) }} #{{ $conversation->profile_id }}
                            @endif
                        </td>
                        <td class="text-center">{{ $conversation->messages_count }}</td>
                        <td class="text-center">{{ $conversation->proposals_count }}</td>
                        <td class="small text-nowrap">{{ optional($conversation->updated_at)->format('d M Y, H:i') }}</td>
                        <td class="table-actions"><a href="{{ route('admin.conversations.show', $conversation) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 8])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $conversations])
    </div>
@endsection
