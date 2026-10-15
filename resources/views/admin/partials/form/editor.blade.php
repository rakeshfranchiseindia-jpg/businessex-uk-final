{{-- Rich text editor (Quill) bound to a hidden input so the form posts HTML. --}}
@php($id = 'editor_'.$name)
<div class="{{ $col ?? 'col-12' }}">
    <label class="form-label fw-medium">{{ $label }}@if (! empty($required))<span class="text-danger">*</span>@endif</label>
    <div id="{{ $id }}" class="bg-white @error($name) border border-danger @enderror">{!! old($name, $value ?? '') !!}</div>
    <input type="hidden" name="{{ $name }}" id="{{ $id }}_input" value="{{ old($name, $value ?? '') }}">
    @error($name)<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>
@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    @endpush
@endonce
@push('scripts')
    <script>
        (function () {
            const quill = new Quill('#{{ $id }}', {
                theme: 'snow',
                modules: { toolbar: [[{ header: [2, 3, 4, false] }], ['bold', 'italic', 'underline', 'strike'], [{ list: 'ordered' }, { list: 'bullet' }], ['blockquote', 'link', 'image'], ['clean']] }
            });
            const input = document.getElementById('{{ $id }}_input');
            input.form.addEventListener('submit', function () {
                input.value = quill.getText().trim().length ? quill.root.innerHTML : '';
            });
        })();
    </script>
@endpush
