@extends('admin.layouts.app')

@section('title', $seo->exists ? 'Edit SEO Entry' : 'New SEO Entry')

@section('content')
    <form method="POST" action="{{ $seo->exists ? route('admin.seo.update', $seo) : route('admin.seo.store') }}" class="card" style="max-width:900px">
        @csrf
        @if ($seo->exists) @method('PUT') @endif
        <div class="card-body row g-3">
            @include('admin.partials.form.select', ['name' => 'profile_type', 'label' => 'Profile type', 'options' => config('admin.profile_types'), 'value' => $seo->profile_type, 'required' => true, 'col' => 'col-md-6'])
            @include('admin.partials.form.select', ['name' => 'cat_id', 'label' => 'Industry category', 'options' => $categories, 'value' => $seo->cat_id, 'placeholder' => 'All industries', 'col' => 'col-md-6'])
            @include('admin.partials.form.input', ['name' => 'title', 'label' => 'Page title', 'value' => $seo->title, 'required' => true, 'max' => 500])
            @include('admin.partials.form.textarea', ['name' => 'keyword', 'label' => 'Keywords', 'value' => $seo->keyword, 'rows' => 2])
            @include('admin.partials.form.textarea', ['name' => 'meta_description', 'label' => 'Meta description', 'value' => $seo->meta_description, 'rows' => 2, 'max' => 1000])
            @include('admin.partials.form.textarea', ['name' => 'description', 'label' => 'Page description (shown on listing page)', 'value' => $seo->description, 'rows' => 5])
            <div class="col-12">@include('admin.partials.form.actions', ['cancel' => route('admin.seo.index')])</div>
        </div>
    </form>
@endsection
