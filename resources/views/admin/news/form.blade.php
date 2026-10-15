@extends('admin.layouts.app')

@section('title', $news->exists ? 'Edit News' : 'New News')

@section('content')
    <form method="POST" enctype="multipart/form-data"
          action="{{ $news->exists ? route('admin.news.update', $news) : route('admin.news.store') }}">
        @csrf
        @if ($news->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body row g-3">
                        @include('admin.partials.form.input', ['name' => 'news_title', 'label' => 'Title', 'value' => $news->news_title, 'required' => true, 'max' => 255])
                        @include('admin.partials.form.textarea', ['name' => 'short_desc', 'label' => 'Short description', 'value' => $news->short_desc, 'required' => true, 'max' => 255, 'rows' => 2])
                        @include('admin.partials.form.editor', ['name' => 'news_content', 'label' => 'Content', 'value' => $news->news_content, 'required' => true])
                    </div>
                </div>
                @include('admin.partials.seo-fields', ['model' => $news])
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body row g-3">
                        @include('admin.partials.form.status', ['name' => 'news_status', 'value' => $news->news_status, 'on' => 'Published', 'off' => 'Draft'])
                        @include('admin.partials.form.select', ['name' => 'author_id', 'label' => 'Author', 'options' => $authors, 'value' => $news->author_id, 'placeholder' => 'Select author'])
                        @include('admin.partials.form.input', ['name' => 'news_tags', 'label' => 'Tags', 'value' => $news->news_tags, 'help' => 'Comma separated', 'attrs' => 'list="tag-options"'])
                        @include('admin.partials.form.file', ['name' => 'image', 'label' => 'Main image', 'current' => $news->image_path])
                        @include('admin.partials.form.file', ['name' => 'listing_image', 'label' => 'Listing (thumbnail) image', 'current' => $news->listing_image_path])
                    </div>
                </div>
                @include('admin.partials.form.actions', ['cancel' => route('admin.news.index'), 'label' => $news->exists ? 'Update news' : 'Create news'])
            </div>
        </div>
        <datalist id="tag-options">@foreach ($tags as $tag)<option value="{{ $tag }}">@endforeach</datalist>
    </form>
@endsection
