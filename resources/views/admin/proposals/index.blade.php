@extends('admin.layouts.app')

@section('title', 'Proposals')
@section('subtitle', 'Proposals sent between members')

@section('content')
    <div class="card">
        <div class="card-body border-bottom">
            @component('admin.partials.search', ['placeholder' => 'Title or ID'])
                <select name="status" class="form-select w-auto">
                    <option value="">Any status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            @endcomponent
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>ID</th><th>Title</th><th>From</th><th>To</th><th>Profile</th><th class="text-end">Amount</th><th>Status</th><th>Sent</th><th></th></tr></thead>
                <tbody>
                @forelse ($proposals as $proposal)
                    <tr>
                        <td class="text-muted">#{{ $proposal->id }}</td>
                        <td style="max-width:260px"><a href="{{ route('admin.proposals.show', $proposal) }}" class="fw-semibold text-decoration-none">{{ $proposal->title }}</a></td>
                        <td class="small">{{ optional($proposal->sender)->name ?? '#'.$proposal->sender_user_id }}</td>
                        <td class="small">{{ optional($proposal->recipient)->name ?? '#'.$proposal->recipient_user_id }}</td>
                        <td class="small">{{ ucfirst((string) $proposal->profile_type) }} #{{ $proposal->profile_id }}</td>
                        <td class="text-end">{{ $proposal->amount !== null ? number_format((float) $proposal->amount, 2) : '—' }}</td>
                        <td>@include('admin.partials.proposal-status', ['status' => $proposal->status])</td>
                        <td class="small text-nowrap">{{ optional($proposal->created_at)->format('d M Y') }}</td>
                        <td class="table-actions"><a href="{{ route('admin.proposals.show', $proposal) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 9])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $proposals])
    </div>
@endsection
