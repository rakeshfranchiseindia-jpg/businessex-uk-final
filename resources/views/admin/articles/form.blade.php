@extends('admin.layouts.app')

@section('title', $article->exists ? 'Edit Article' : 'New Article')

@section('content')
    <form method="POST" enctype="multipart/form-data"
          action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}">
        @csrf
        @if ($article->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body row g-3">
                        @include('admin.partials.form.input', ['name' => 'article_title', 'label' => 'Title', 'value' => $article->article_title, 'required' => true, 'max' => 255])
                        @include('admin.partials.form.textarea', ['name' => 'short_desc', 'label' => 'Short description', 'value' => $article->short_desc, 'required' => true, 'rows' => 3])
                        @include('admin.partials.form.editor', ['name' => 'article_content', 'label' => 'Content', 'value' => $article->article_content, 'required' => true])
                    </div>
                </div>
                @include('admin.partials.seo-fields', ['model' => $article])
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body row g-3">
                        @include('admin.partials.form.status', ['name' => 'article_status', 'value' => $article->article_status, 'on' => 'Published', 'off' => 'Draft'])
                        @include('admin.partials.form.select', ['name' => 'author_id', 'label' => 'Author', 'options' => $authors, 'value' => $article->author_id, 'placeholder' => 'Select author'])
                        @include('admin.partials.form.input', ['name' => 'article_tags', 'label' => 'Tags', 'value' => $article->article_tags, 'help' => 'Comma separated, e.g. Franchise, Funding', 'attrs' => 'list="tag-options"'])
                        @include('admin.partials.form.file', ['name' => 'image', 'label' => 'Main image', 'current' => $article->image_path])
                        @include('admin.partials.form.file', ['name' => 'listing_image', 'label' => 'Listing (thumbnail) image', 'current' => $article->listing_image_path])
                    </div>
                </div>
                @include('admin.partials.form.actions', ['cancel' => route('admin.articles.index'), 'label' => $article->exists ? 'Update article' : 'Create article'])
            </div>
        </div>
        <datalist id="tag-options">@foreach ($tags as $tag)<option value="{{ $tag }}">@endforeach</datalist>
    </form>
@endsection
