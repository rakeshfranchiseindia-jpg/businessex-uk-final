@php($id = 'f_'.$name)
<div class="{{ $col ?? 'col-12' }}">
    <label for="{{ $id }}" class="form-label fw-medium">{{ $label }}</label>
    @if (! empty($current))
        <div class="mb-2 d-flex align-items-center gap-2">
            @if (empty($isFile))
                <img src="{{ preg_match('#^https?://#', $current) ? $current : asset($current) }}" alt="" class="thumb" style="width:80px;height:56px" onerror="this.style.display='none'">
            @endif
            <a href="{{ preg_match('#^https?://#', $current) ? $current : asset($current) }}" target="_blank" class="small text-truncate" style="max-width:260px">{{ basename($current) }}</a>
        </div>
    @endif
    <input type="file" id="{{ $id }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" accept="{{ $accept ?? 'image/*' }}">
    <div class="form-text">{{ $help ?? (empty($current) ? 'JPG, PNG or WebP, up to 4 MB.' : 'Upload a new file to replace the current one.') }}</div>
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
