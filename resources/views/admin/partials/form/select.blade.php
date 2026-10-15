@php
    $id = $id ?? 'f_'.str_replace(['[', ']'], '_', $name);
    $multiple = ! empty($multiple);
    $key = rtrim($name, '[]');
    $selected = old($key, $value ?? ($multiple ? [] : ''));
    $selected = $multiple ? array_map('strval', (array) $selected) : (string) $selected;
@endphp
<div class="{{ $col ?? 'col-12' }}">
    <label for="{{ $id }}" class="form-label fw-medium">{{ $label }}@if (! empty($required))<span class="text-danger">*</span>@endif</label>
    <select id="{{ $id }}" name="{{ $name }}" class="form-select @error($key) is-invalid @enderror" @if ($multiple) multiple size="{{ min(count($options), 7) }}" @endif @if (! empty($required)) required @endif>
        @unless ($multiple)
            <option value="">{{ $placeholder ?? 'Select' }}</option>
        @endunless
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($multiple ? in_array((string) $optionValue, $selected, true) : (string) $optionValue === $selected)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @isset($help)<div class="form-text">{{ $help }}</div>@endisset
    @error($key)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
