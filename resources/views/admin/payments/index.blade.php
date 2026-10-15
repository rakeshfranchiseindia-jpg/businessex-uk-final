@extends('admin.layouts.app')

@section('title', 'Payments')
@section('subtitle', 'Online membership and service payments')

@section('content')
    <div class="card">
        <div class="card-body border-bottom d-flex flex-wrap gap-3 justify-content-between align-items-center">
            @component('admin.partials.search', ['placeholder' => 'Order no, name, email, phone'])
                <select name="status" class="form-select w-auto">
                    <option value="">Any status</option>
                    @foreach (config('admin.payment_status') as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === (string) $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="profile_type" class="form-select w-auto">
                    <option value="">Any profile</option>
                    @foreach (config('admin.profile_types') as $value => $label)
                        <option value="{{ $value }}" @selected(request('profile_type') === (string) $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control w-auto">
                <input type="date" name="to" value="{{ request('to') }}" class="form-control w-auto">
            @endcomponent
            <div class="text-end">
                <div class="small text-muted">Successful total (filtered)</div>
                <div class="fs-5 fw-bold text-navy">{{ number_format($total, 2) }}</div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Order</th><th>Customer</th><th>Profile</th><th>Plan / product</th><th class="text-end">Amount</th><th>Mode</th><th>Assigned by</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td>
                            @if ($payment->row_type === 'payment')
                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="fw-semibold text-decoration-none">{{ $payment->order_no }}</a>
                            @elseif (isset($profileSlugs[$payment->profile_type]))
                                <a href="{{ route('admin.profiles.show', [$profileSlugs[$payment->profile_type], $payment->profile_id]) }}" class="fw-semibold text-decoration-none">{{ $payment->order_no }}</a>
                            @else
                                {{ $payment->order_no }}
                            @endif
                        </td>
                        <td class="small">
                            <div>{{ $payment->name }}</div>
                            <div class="text-muted">{{ $payment->email }}</div>
                        </td>
                        <td class="small">{{ config('admin.profile_types')[$payment->profile_type] ?? '—' }}{{ $payment->profile_id ? ' #'.$payment->profile_id : '' }}</td>
                        <td class="small" style="max-width:220px">{{ $payment->plan_label ?: '—' }}</td>
                        <td class="text-end fw-medium">{{ $payment->amount }}</td>
                        <td class="small">{{ $payment->payment_mode ?: '—' }}</td>
                        <td class="small">
                            @if ($payment->assigned_by && isset($admins[$payment->assigned_by]))
                                {{ $admins[$payment->assigned_by]->admin_name }} ({{ $admins[$payment->assigned_by]->admin_email }})
                            @else
                                Self
                            @endif
                        </td>
                        <td><span class="badge text-bg-{{ (int) $payment->payment_status === 1 ? 'success' : ((int) $payment->payment_status === 2 ? 'danger' : 'warning') }}">{{ config('admin.payment_status')[$payment->payment_status] ?? $payment->payment_status }}</span></td>
                        <td class="small text-nowrap">{{ \Illuminate\Support\Carbon::parse($payment->created_at)->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 9])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $payments])
    </div>
@endsection
