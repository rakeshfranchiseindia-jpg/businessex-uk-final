@extends('admin.layouts.app')

@section('title', 'Coupons')

@section('content')
    <div class="card">
        <div class="card-body border-bottom d-flex flex-wrap gap-2 justify-content-between">
            @component('admin.partials.search', ['placeholder' => 'Coupon code'])
            @endcomponent
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New coupon</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Code</th><th>Discount</th><th>Applies to</th><th>Validity</th><th class="text-center">Redeemed</th><th>Platform</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($coupons as $coupon)
                    @php
                        $types = collect(explode(',', (string) $coupon->profile_type))->filter()->map(fn ($id) => config('admin.profile_types')[$id] ?? $id);
                        $expired = $coupon->end_date && $coupon->end_date->isPast();
                    @endphp
                    <tr>
                        <td><code class="fs-6">{{ $coupon->coupon_code }}</code></td>
                        <td>{{ (int) $coupon->discount_type === 1 ? $coupon->discount_amount.'%' : number_format($coupon->discount_amount).' flat' }}</td>
                        <td class="small">
                            {{ $types->isNotEmpty() ? $types->implode(', ') : 'All profiles' }}
                            @if ($coupon->user_type)<div class="text-muted">{{ config('admin.coupon_user_type')[$coupon->user_type] ?? '' }}</div>@endif
                        </td>
                        <td class="small text-nowrap {{ $expired ? 'text-danger' : '' }}">
                            {{ optional($coupon->start_date)->format('d M Y') }} &ndash; {{ optional($coupon->end_date)->format('d M Y') }}
                            @if ($expired)<div>Expired</div>@endif
                        </td>
                        <td class="text-center">{{ (int) $coupon->redemption_number }}{{ $coupon->max_redemption ? ' / '.$coupon->max_redemption : '' }}</td>
                        <td class="small">{{ config('admin.platform')[$coupon->platform] ?? '—' }}</td>
                        <td>@include('admin.partials.active-badge', ['active' => $coupon->is_active])</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @include('admin.partials.delete', ['action' => route('admin.coupons.destroy', $coupon)])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 8])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $coupons])
    </div>
@endsection
