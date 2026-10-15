@extends('admin.layouts.app')

@section('title', $testimonial->exists ? 'Edit Testimonial' : 'New Testimonial')

@section('content')
    <form method="POST" enctype="multipart/form-data"
          action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" class="card" style="max-width:900px">
        @csrf
        @if ($testimonial->exists) @method('PUT') @endif
        <div class="card-body row g-3">
            @include('admin.partials.form.input', ['name' => 'name', 'label' => 'Name', 'value' => $testimonial->name, 'required' => true, 'col' => 'col-md-6'])
            @include('admin.partials.form.input', ['name' => 'designation', 'label' => 'Designation / company', 'value' => $testimonial->designation, 'col' => 'col-md-6'])
            @include('admin.partials.form.textarea', ['name' => 'text', 'label' => 'Testimonial', 'value' => $testimonial->text, 'required' => true, 'rows' => 4, 'max' => 2000])
            @include('admin.partials.form.select', ['name' => 'rating', 'label' => 'Rating', 'options' => [5 => '5 stars', 4 => '4 stars', 3 => '3 stars', 2 => '2 stars', 1 => '1 star'], 'value' => $testimonial->rating, 'required' => true, 'col' => 'col-md-4'])
            @include('admin.partials.form.input', ['name' => 'sort_order', 'label' => 'Display order', 'value' => $testimonial->sort_order, 'type' => 'number', 'col' => 'col-md-4', 'help' => 'Lower numbers show first.'])
            <div class="col-md-4">@include('admin.partials.form.status', ['name' => 'is_active', 'value' => $testimonial->is_active])</div>
            @include('admin.partials.form.file', ['name' => 'image', 'label' => 'Photo', 'current' => $testimonial->image_path, 'help' => 'Square image, up to 2 MB.'])
            <div class="col-12">@include('admin.partials.form.actions', ['cancel' => route('admin.testimonials.index')])</div>
        </div>
    </form>
@endsection
