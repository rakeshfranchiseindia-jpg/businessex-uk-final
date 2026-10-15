@extends('admin.layouts.app')

@section('title', $tag->exists ? 'Edit Tag' : 'New Tag')

@section('content')
    <form method="POST" action="{{ $tag->exists ? route('admin.tags.update', $tag) : route('admin.tags.store') }}" class="card" style="max-width:640px">
        @csrf
        @if ($tag->exists) @method('PUT') @endif
        <div class="card-body row g-3">
            @include('admin.partials.form.input', ['name' => 'tag_name', 'label' => 'Tag name', 'value' => $tag->tag_name, 'required' => true, 'max' => 255, 'help' => 'The URL slug is generated automatically.'])
            @include('admin.partials.form.status', ['name' => 'tag_status', 'value' => $tag->tag_status])
            <div class="col-12">@include('admin.partials.form.actions', ['cancel' => route('admin.tags.index')])</div>
        </div>
    </form>
@endsection
