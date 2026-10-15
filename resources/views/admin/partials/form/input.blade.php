@php($id = $id ?? 'f_'.str_replace(['[', ']'], '_', $name))
<div class="{{ $col ?? 'col-12' }}">
    <label for="{{ $id }}" class="form-label fw-medium">{{ $label }}@if (! empty($required))<span class="text-danger">*</span>@endif</label>
    <input type="{{ $type ?? 'text' }}" id="{{ $id }}" name="{{ $name }}" value="{{ old($name, $value ?? '') }}"
           class="form-control @error($name) is-invalid @enderror" @if (! empty($required)) required @endif
           @isset($max) maxlength="{{ $max }}" @endisset @isset($placeholder) placeholder="{{ $placeholder }}" @endisset {!! $attrs ?? '' !!}>
    @isset($help)<div class="form-text">{{ $help }}</div>@endisset
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
