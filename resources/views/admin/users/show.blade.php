@extends('admin.layouts.app')

@section('title', 'User #'.$user->user_id)
@section('subtitle', $user->name.' · '.$user->email)

@section('content')
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="ms-auto"
              data-confirm="{{ $user->is_active ? 'Deactivate this user account?' : 'Activate this user account?' }}">
            @csrf
            @if ($user->is_active)
                <button class="btn btn-outline-danger"><i class="bi bi-person-slash me-1"></i>Deactivate account</button>
            @else
                <button class="btn btn-success"><i class="bi bi-person-check me-1"></i>Activate account</button>
            @endif
        </form>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Account</div>
                <div class="card-body">
                    <dl class="detail-list mb-0">
                        <dt>Name</dt><dd>{{ $user->name ?: '—' }}</dd>
                        <dt>Email</dt><dd>{{ $user->email }} @if ($user->email_verified_at)<i class="bi bi-patch-check-fill text-success" title="Verified"></i>@endif</dd>
                        <dt>Mobile</dt><dd>{{ $user->mobile ?: '—' }}</dd>
                        <dt>Company</dt><dd>{{ $user->company_name ?: '—' }} @if ($user->designation)<span class="text-muted">· {{ $user->designation }}</span>@endif</dd>
                        <dt>Location</dt><dd>{{ $user->location ?: '—' }}</dd>
                        <dt>Registration source</dt><dd>{{ config('admin.reg_source')[$user->reg_source] ?? '—' }}</dd>
                        <dt>Status</dt><dd>@include('admin.partials.active-badge', ['active' => $user->is_active])</dd>
                        <dt>Registered</dt><dd>{{ optional($user->created_at)->format('d M Y, H:i') }}</dd>
                        <dt>Last login</dt><dd class="mb-0">{{ optional($user->last_login_at)->format('d M Y, H:i') ?? 'Never' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">Profiles ({{ $profiles->count() }})</div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Type</th><th>Profile</th><th>Status</th><th>Created</th><th></th></tr></thead>
                        <tbody>
                        @forelse ($profiles as $item)
                            <tr>
                                <td>{{ $item->label }}</td>
                                <td>
                                    <div class="fw-medium">{{ $item->title ?: '#'.$item->row->profile_id }}</div>
                                    <div class="small text-muted">{{ $item->row->profile_str }}</div>
                                </td>
                                <td>@include('admin.partials.profile-status', ['status' => $item->status])</td>
                                <td class="small">{{ optional($item->row->created_at)->format('d M Y') }}</td>
                                <td class="text-end">
                                    @if ($item->record && auth('admin')->user()->canAccess('profiles'))
                                        <a href="{{ route('admin.profiles.show', [$item->slug, $item->row->profile_id]) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            @include('admin.partials.empty', ['colspan' => 5, 'message' => 'This user has not created a profile yet.'])
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Payments ({{ $user->payments->count() }})</div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Order</th><th>Plan</th><th class="text-end">Amount</th><th>Status</th><th>Date</th></tr></thead>
                        <tbody>
                        @forelse ($user->payments as $payment)
                            <tr>
                                <td>
                                    @if (auth('admin')->user()->canAccess('payments'))
                                        <a href="{{ route('admin.payments.show', $payment) }}">{{ $payment->order_no }}</a>
                                    @else
                                        {{ $payment->order_no }}
                                    @endif
                                </td>
                                <td class="small">{{ $payment->membership_plan ?: $payment->product_details }}</td>
                                <td class="text-end">{{ $payment->amount }}</td>
                                <td><span class="badge text-bg-{{ (int) $payment->payment_status === 1 ? 'success' : ((int) $payment->payment_status === 2 ? 'danger' : 'warning') }}">{{ config('admin.payment_status')[$payment->payment_status] ?? $payment->payment_status }}</span></td>
                                <td class="small">{{ optional($payment->created_at)->format('d M Y') }}</td>
                            </tr>
                        @empty
                            @include('admin.partials.empty', ['colspan' => 5, 'message' => 'No payments.'])
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Memberships ({{ $user->memberships->count() }})</div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Profile type</th><th>Plan</th><th>Credits</th><th>Valid</th><th>Active</th></tr></thead>
                        <tbody>
                        @forelse ($user->memberships as $membership)
                            <tr>
                                <td>{{ config('admin.profile_types')[$membership->profile_type] ?? '—' }} #{{ $membership->profile_id }}</td>
                                <td>{{ config('admin.membership')[$membership->membership_type] ?? $membership->membership_type }}</td>
                                <td class="small">{{ $membership->interaction_credits ?: '—' }}</td>
                                <td class="small">{{ $membership->activation_date ? \Illuminate\Support\Carbon::parse($membership->activation_date)->format('d M Y') : '—' }} &rarr; {{ $membership->expiry_date ? \Illuminate\Support\Carbon::parse($membership->expiry_date)->format('d M Y') : '—' }}</td>
                                <td>@include('admin.partials.active-badge', ['active' => $membership->is_active, 'on' => 'Yes', 'off' => 'No'])</td>
                            </tr>
                        @empty
                            @include('admin.partials.empty', ['colspan' => 5, 'message' => 'No memberships.'])
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
