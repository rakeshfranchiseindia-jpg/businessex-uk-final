@extends('admin.layouts.app')

@section('title', 'Payment '.$payment->order_no)

@section('content')
    <a href="{{ route('admin.payments.index') }}" class="btn btn-light mb-3"><i class="bi bi-arrow-left me-1"></i>Back</a>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Payment details
                    <span class="badge text-bg-{{ (int) $payment->payment_status === 1 ? 'success' : ((int) $payment->payment_status === 2 ? 'danger' : 'warning') }}">{{ config('admin.payment_status')[$payment->payment_status] ?? $payment->payment_status }}</span>
                </div>
                <div class="card-body">
                    <dl class="row detail-list mb-0">
                        <dt class="col-sm-4">Order no</dt><dd class="col-sm-8">{{ $payment->order_no }}</dd>
                        <dt class="col-sm-4">Amount</dt><dd class="col-sm-8 fw-semibold">{{ $payment->amount }}</dd>
                        <dt class="col-sm-4">Plan</dt><dd class="col-sm-8">{{ $payment->membership_plan ?: '—' }}</dd>
                        <dt class="col-sm-4">Product details</dt><dd class="col-sm-8">{{ $payment->product_details ?: '—' }}</dd>
                        <dt class="col-sm-4">Profile</dt><dd class="col-sm-8">{{ config('admin.profile_types')[$payment->profile_type] ?? '—' }}{{ $payment->profile_id ? ' #'.$payment->profile_id : '' }}</dd>
                        <dt class="col-sm-4">Payment mode</dt><dd class="col-sm-8">{{ $payment->payment_mode ?: '—' }}</dd>
                        <dt class="col-sm-4">Coupon</dt><dd class="col-sm-8">{{ $payment->coupon_id ? '#'.$payment->coupon_id : '—' }}</dd>
                        <dt class="col-sm-4">Gateway message</dt><dd class="col-sm-8">{{ $payment->status_message ?: '—' }}</dd>
                        <dt class="col-sm-4">Reference (udf)</dt><dd class="col-sm-8 text-break">{{ $payment->udf ?: '—' }}</dd>
                        <dt class="col-sm-4">Created</dt><dd class="col-sm-8">{{ optional($payment->created_at)->format('d M Y, H:i') }}</dd>
                        <dt class="col-sm-4">Updated</dt><dd class="col-sm-8 mb-0">{{ optional($payment->updated_at)->format('d M Y, H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-header">Customer</div>
                <div class="card-body small">
                    <div class="fw-semibold">{{ $payment->name }}</div>
                    <div>{{ $payment->email }}</div>
                    <div>{{ $payment->phone }}</div>
                    <div class="text-muted">{{ collect([$payment->city, $payment->country])->filter()->implode(', ') }}</div>
                    @if ($payment->user && auth('admin')->user()->canAccess('users'))
                        <a href="{{ route('admin.users.show', $payment->user) }}" class="d-inline-block mt-2">View user #{{ $payment->user_id }}</a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-header">Membership</div>
                <div class="card-body small">
                    @if ($membership)
                        <dl class="detail-list mb-0">
                            <dt>Plan</dt><dd>{{ config('admin.membership')[$membership->membership_type] ?? $membership->membership_type }}</dd>
                            <dt>Validity</dt><dd>{{ $membership->activation_date ? \Illuminate\Support\Carbon::parse($membership->activation_date)->format('d M Y') : '—' }} &rarr; {{ $membership->expiry_date ? \Illuminate\Support\Carbon::parse($membership->expiry_date)->format('d M Y') : '—' }}</dd>
                            <dt>Interaction credits</dt><dd>{{ $membership->interaction_credits ?: '—' }}</dd>
                            <dt>Active</dt><dd class="mb-0">@include('admin.partials.active-badge', ['active' => $membership->is_active, 'on' => 'Yes', 'off' => 'No'])</dd>
                        </dl>
                    @else
                        <span class="text-muted">No membership linked to this payment.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
