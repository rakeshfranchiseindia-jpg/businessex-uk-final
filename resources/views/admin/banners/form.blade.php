@extends('admin.layouts.app')

@section('title', $banner->exists ? 'Edit Banner' : 'New Banner')

@php
    $widths = array_combine(config('admin.banner_width'), config('admin.banner_width'));
    $heights = array_combine(config('admin.banner_height'), config('admin.banner_height'));
    // Keep legacy sizes that are not in the standard list selectable.
    if ($banner->width && ! isset($widths[$banner->width])) { $widths[$banner->width] = $banner->width; }
    if ($banner->height && ! isset($heights[$banner->height])) { $heights[$banner->height] = $banner->height; }
@endphp

@section('content')
    <form method="POST" action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" class="card" style="max-width:900px">
        @csrf
        @if ($banner->exists) @method('PUT') @endif
        <div class="card-body row g-3">
            @include('admin.partials.form.select', ['name' => 'page', 'label' => 'Page', 'options' => config('admin.banner_page'), 'value' => $banner->page, 'required' => true, 'col' => 'col-md-6'])
            @include('admin.partials.form.select', ['name' => 'location', 'label' => 'Location', 'options' => config('admin.banner_location'), 'value' => $banner->location, 'required' => true, 'col' => 'col-md-6'])
            @include('admin.partials.form.input', ['name' => 'dfp_id', 'label' => 'DFP ID', 'value' => $banner->dfp_id, 'required' => true, 'col' => 'col-md-6', 'placeholder' => 'div-gpt-ad-...'])
            @include('admin.partials.form.input', ['name' => 'dfp_slot', 'label' => 'DFP slot', 'value' => $banner->dfp_slot, 'required' => true, 'col' => 'col-md-6', 'placeholder' => '/1234567/slot_name'])
            @include('admin.partials.form.select', ['name' => 'width', 'label' => 'Width (px)', 'options' => $widths, 'value' => $banner->width, 'required' => true, 'col' => 'col-md-6'])
            @include('admin.partials.form.select', ['name' => 'height', 'label' => 'Height (px)', 'options' => $heights, 'value' => $banner->height, 'required' => true, 'col' => 'col-md-6'])
            @include('admin.partials.form.status', ['name' => 'is_active', 'value' => $banner->is_active])
            <div class="col-12">@include('admin.partials.form.actions', ['cancel' => route('admin.banners.index')])</div>
        </div>
    </form>
@endsection
