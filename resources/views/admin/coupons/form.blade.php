@extends('admin.layouts.app')

@section('title', $coupon->exists ? 'Edit Coupon' : 'New Coupon')

@section('content')
    <form method="POST" action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" class="card" style="max-width:900px">
        @csrf
        @if ($coupon->exists) @method('PUT') @endif
        <div class="card-body row g-3">
            @include('admin.partials.form.input', ['name' => 'coupon_code', 'label' => 'Coupon code', 'value' => $coupon->coupon_code, 'required' => true, 'col' => 'col-md-6', 'attrs' => 'style="text-transform:uppercase"'])
            @include('admin.partials.form.select', ['name' => 'platform', 'label' => 'Platform', 'options' => config('admin.platform'), 'value' => $coupon->platform, 'required' => true, 'col' => 'col-md-6'])
            @include('admin.partials.form.select', ['name' => 'discount_type', 'label' => 'Discount type', 'options' => config('admin.discount_type'), 'value' => $coupon->discount_type, 'required' => true, 'col' => 'col-md-6'])
            @include('admin.partials.form.input', ['name' => 'discount_amount', 'label' => 'Discount amount', 'value' => $coupon->discount_amount, 'required' => true, 'type' => 'number', 'col' => 'col-md-6', 'help' => 'Percentage (1-100) or flat amount.'])
            @include('admin.partials.form.input', ['name' => 'start_date', 'label' => 'Start date', 'value' => optional($coupon->start_date)->format('Y-m-d'), 'required' => true, 'type' => 'date', 'col' => 'col-md-6'])
            @include('admin.partials.form.input', ['name' => 'end_date', 'label' => 'End date', 'value' => optional($coupon->end_date)->format('Y-m-d'), 'required' => true, 'type' => 'date', 'col' => 'col-md-6'])
            @include('admin.partials.form.select', ['name' => 'user_type', 'label' => 'User type', 'options' => config('admin.coupon_user_type'), 'value' => $coupon->user_type, 'placeholder' => 'All users', 'col' => 'col-md-6'])
            @include('admin.partials.form.input', ['name' => 'max_redemption', 'label' => 'Max redemptions', 'value' => $coupon->max_redemption, 'type' => 'number', 'col' => 'col-md-6', 'help' => 'Leave empty for unlimited.'])
            @include('admin.partials.form.select', ['name' => 'profile_type[]', 'label' => 'Profile types', 'options' => config('admin.profile_types'), 'value' => array_filter(explode(',', (string) $coupon->profile_type)), 'multiple' => true, 'col' => 'col-md-6', 'help' => 'Ctrl/Cmd-click to select several. None = all.'])
            @include('admin.partials.form.select', ['name' => 'membership[]', 'label' => 'Membership plans', 'options' => config('admin.membership'), 'value' => array_filter(explode(',', (string) $coupon->membership), 'strlen'), 'multiple' => true, 'col' => 'col-md-6', 'help' => 'None = all plans.'])
            @include('admin.partials.form.status', ['name' => 'is_active', 'value' => $coupon->is_active])
            <div class="col-12">@include('admin.partials.form.actions', ['cancel' => route('admin.coupons.index')])</div>
        </div>
    </form>
@endsection
