@extends('admin.layouts.app')

@section('title', $short->exists ? 'Edit BEX Short' : 'New BEX Short')

@section('content')
    <form method="POST" enctype="multipart/form-data"
          action="{{ $short->exists ? route('admin.bex-shorts.update', $short) : route('admin.bex-shorts.store') }}">
        @csrf
        @if ($short->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body row g-3">
                        @include('admin.partials.form.input', ['name' => 'bex_title', 'label' => 'Title', 'value' => $short->bex_title, 'required' => true, 'max' => 100])
                        @include('admin.partials.form.editor', ['name' => 'bex_description', 'label' => 'Description', 'value' => $short->bex_description, 'required' => true])
                        @include('admin.partials.form.input', ['name' => 'reference_page_name', 'label' => 'Reference page name', 'value' => $short->reference_page_name, 'col' => 'col-md-6', 'max' => 150])
                        @include('admin.partials.form.input', ['name' => 'reference_page_link', 'label' => 'Reference page link', 'value' => $short->reference_page_link, 'col' => 'col-md-6', 'type' => 'url', 'max' => 250])
                    </div>
                </div>
                @include('admin.partials.seo-fields', ['model' => $short])
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body row g-3">
                        @include('admin.partials.form.status', ['name' => 'status', 'value' => $short->status, 'on' => 'Published', 'off' => 'Draft'])
                        @include('admin.partials.form.select', ['name' => 'author_id', 'label' => 'Author', 'options' => $authors, 'value' => $short->author_id, 'placeholder' => 'Select author'])
                        @include('admin.partials.form.input', ['name' => 'associated_tag', 'label' => 'Tags', 'value' => $short->associated_tag, 'help' => 'Comma separated', 'attrs' => 'list="tag-options"'])
                        @include('admin.partials.form.file', ['name' => 'image', 'label' => 'Image', 'current' => $short->image_path])
                    </div>
                </div>
                @include('admin.partials.form.actions', ['cancel' => route('admin.bex-shorts.index'), 'label' => $short->exists ? 'Update' : 'Create'])
            </div>
        </div>
        <datalist id="tag-options">@foreach ($tags as $tag)<option value="{{ $tag }}">@endforeach</datalist>
    </form>
@endsection
