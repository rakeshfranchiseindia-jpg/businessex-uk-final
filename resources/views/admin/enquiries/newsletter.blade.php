@extends('admin.layouts.app')

@section('title', 'Newsletter Subscribers')

@section('content')
    <div class="card">
        <div class="card-body border-bottom">
            @component('admin.partials.search', ['placeholder' => 'Email, name, phone or city'])
                <select name="status" class="form-select w-auto">
                    <option value="">Any status</option>
                    @foreach (config('admin.newsletter_status') as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            @endcomponent
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Email</th><th>Name</th><th>Phone</th><th>City</th><th>Status</th><th>Subscribed</th></tr></thead>
                <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ $row->email }}</td>
                        <td class="small">{{ $row->name ?: '—' }}</td>
                        <td class="small">{{ $row->phone ?: '—' }}</td>
                        <td class="small">{{ $row->city ?: '—' }}</td>
                        <td>
                            <span class="badge text-bg-{{ ['S' => 'success', 'P' => 'warning', 'U' => 'secondary'][$row->status] ?? 'light' }}">{{ config('admin.newsletter_status')[$row->status] ?? $row->status }}</span>
                            @if ($row->unsubscribe_reason)<div class="small text-muted">{{ $row->unsubscribe_reason }}</div>@endif
                        </td>
                        <td class="small text-nowrap">{{ optional($row->created_at)->format('d M Y') }}</td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 6])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $rows])
    </div>
@endsection
