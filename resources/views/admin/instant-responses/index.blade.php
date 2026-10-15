@extends('admin.layouts.app')

@section('title', 'Instant Responses')
@section('subtitle', 'Auto-reply messages members have set up on their own profiles')

@section('content')
    <div class="card">
        <div class="card-body border-bottom">
            @component('admin.partials.search', ['placeholder' => 'Name, email or message'])
                <select name="status" class="form-select w-auto">
                    <option value="">Any status</option>
                    <option value="1" @selected(request('status') === '1')>Enabled</option>
                    <option value="0" @selected(request('status') === '0')>Disabled</option>
                </select>
            @endcomponent
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Member</th><th>Message</th><th>Status</th><th>Last updated</th><th></th></tr></thead>
                <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td class="small">
                            <div class="fw-medium">{{ $row->user->name ?? '—' }}</div>
                            <div class="text-muted">{{ $row->user->email ?? '—' }}</div>
                        </td>
                        <td class="small" style="max-width:420px">{{ $row->message }}</td>
                        <td>
                            @include('admin.partials.active-badge', ['active' => $row->enabled, 'on' => 'Enabled', 'off' => 'Disabled'])
                            @if ($row->locked_by_admin)
                                <span class="badge bg-danger ms-1" title="Member cannot re-enable this themselves">Locked</span>
                            @endif
                        </td>
                        <td class="small text-nowrap">{{ optional($row->updated_at)->format('d M Y, H:i') }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.instant-responses.toggle', $row) }}" data-confirm="{{ $row->enabled ? 'Disable this member\'s instant response? They will not be able to re-enable it themselves.' : 'Re-enable this member\'s instant response?' }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-secondary">{{ $row->enabled ? 'Disable' : 'Enable' }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 5])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $rows])
    </div>
@endsection
