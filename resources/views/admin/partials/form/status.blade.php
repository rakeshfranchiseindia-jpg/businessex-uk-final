@php($current = (string) old($name, $value ?? 1))
<div class="{{ $col ?? 'col-12' }}">
    <label class="form-label fw-medium d-block">{{ $label ?? 'Status' }}</label>
    <div class="btn-group" role="group">
        <input type="radio" class="btn-check" name="{{ $name }}" id="{{ $name }}_1" value="1" @checked($current === '1')>
        <label class="btn btn-outline-success" for="{{ $name }}_1">{{ $on ?? 'Active' }}</label>
        <input type="radio" class="btn-check" name="{{ $name }}" id="{{ $name }}_0" value="0" @checked($current === '0')>
        <label class="btn btn-outline-secondary" for="{{ $name }}_0">{{ $off ?? 'Inactive' }}</label>
    </div>
</div>
