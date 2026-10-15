@php($id = $id ?? 'f_'.$name)
<div class="{{ $col ?? 'col-12' }}">
    <label for="{{ $id }}" class="form-label fw-medium">{{ $label }}@if (! empty($required))<span class="text-danger">*</span>@endif</label>
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows ?? 3 }}" class="form-control @error($name) is-invalid @enderror"
              @if (! empty($required)) required @endif @isset($max) maxlength="{{ $max }}" @endisset>{{ old($name, $value ?? '') }}</textarea>
    @isset($help)<div class="form-text">{{ $help }}</div>@endisset
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
