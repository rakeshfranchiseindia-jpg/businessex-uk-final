@extends('admin.layouts.app')

@section('title', 'Edit '.$config['label'].' Profile #'.$profile->getKey())

@php
    $longFields = ['company_summary', 'mentor_profile_summary', 'incubator_profile_summary', 'prof_summary', 'seller_intro', 'startup_intro', 'inv_intro', 'mentor_intro', 'lender_intro', 'incubator_intro'];
@endphp

@section('content')
    <form method="POST" action="{{ route('admin.profiles.update', [$config['slug'], $profile->getKey()]) }}" class="card">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                @foreach ($config['editable'] as $field => $label)
                    @if (in_array($field, $longFields, true))
                        @include('admin.partials.form.textarea', ['name' => $field, 'label' => $label, 'value' => $profile->$field, 'rows' => 4])
                    @else
                        @include('admin.partials.form.input', [
                            'name' => $field,
                            'label' => $label,
                            'value' => $profile->$field,
                            'col' => 'col-md-6',
                            'type' => $field === $config['email'] ? 'email' : 'text',
                            'required' => in_array($field, [$config['name'], $config['email']], true),
                        ])
                    @endif
                @endforeach
            </div>
            @include('admin.partials.form.actions', ['cancel' => route('admin.profiles.show', [$config['slug'], $profile->getKey()]), 'label' => 'Save changes'])
        </div>
    </form>
@endsection
