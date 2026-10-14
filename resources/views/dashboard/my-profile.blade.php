@extends('layouts.dashboard')

@section('title')
{{ $dashboardTitle }} | BusinessX
@endsection

@section('description')
View and manage your {{ strtolower($profileLabel) }} profile.
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/account-dashboard.css') }}">
<style>
  .owned-profile-fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 24px; }
  .owned-profile-field { min-width: 0; padding: 14px 0; border-bottom: 1px solid var(--gray-100); }
  .owned-profile-field dt { margin-bottom: 5px; color: var(--gray-500); font-size: 12px; }
  .owned-profile-field dd { color: var(--navy-800); font-size: 14px; overflow-wrap: anywhere; }
  .owned-profile-field a { color: var(--gold-600); text-decoration: underline; }
  .owned-profile-step { margin: 22px 0 10px; color: var(--navy-800); font-size: 15px; }
  .owned-profile-errors { margin-bottom: 18px; padding: 12px 16px; border-left: 3px solid #dc2626; background: #fef2f2; color: #991b1b; }
  .owned-profile-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 20px; }
  .owned-profile-actions .account-primary-action { border: 0; cursor: pointer; }
  .owned-profile-tabs { display: flex; gap: 8px; overflow-x: auto; margin: 0 -22px 20px; padding: 0 22px 12px; border-bottom: 1px solid var(--gray-200); }
  .owned-profile-tabs button { flex: 0 0 auto; padding: 12px 16px; border: 0; border-radius: 4px; background: var(--gray-50); color: var(--gray-700); cursor: pointer; font: inherit; font-size: 13px; font-weight: 600; }
  .owned-profile-tabs button[aria-selected="true"] { background: #1769d2; color: #fff; }
  .owned-profile-tab-panel[hidden] { display: none; }
  .owned-profile-checkboxes { grid-column: 1 / -1; padding: 14px; border: 1px solid var(--gray-200); border-radius: 6px; }
  .owned-profile-checkboxes legend { padding: 0 6px; color: var(--gray-700); font-size: 12px; font-weight: 600; }
  .owned-profile-checkboxes label { display: inline-flex; align-items: center; gap: 8px; margin: 4px 18px 4px 0; color: var(--gray-700); font-size: 13px; }
  .owned-profile-existing-media { grid-column: 1 / -1; margin-top: 8px; }
  .owned-profile-existing-media a { display: inline-block; margin-right: 12px; color: var(--navy-700); text-decoration: underline; }
  @media (max-width: 700px) { .owned-profile-fields { grid-template-columns: 1fr; } }
  .owned-profile-image-link { display: inline-block; }
  .owned-profile-image-preview {
    width: 96px;
    height: 96px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid var(--gray-200, #e5e7eb);
    background: #fff;
  }
  .file-upload-control { position: relative; }
  .file-upload-preview[hidden] { display: none; }
  .file-upload-preview {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    padding: 6px 8px;
    border: 1px solid var(--gray-200, #e5e7eb);
    border-radius: 8px;
    background: var(--gray-50, #f9fafb);
    max-width: 100%;
  }
  .file-upload-thumb { position: relative; flex-shrink: 0; display: inline-flex; min-width: 36px; min-height: 36px; }
  .file-upload-preview img[hidden],
  .file-upload-thumb-fallback[hidden] { display: none; }
  .file-upload-preview img,
  .file-upload-thumb-fallback {
    width: 36px;
    height: 36px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid var(--gray-200, #e5e7eb);
    background: #fff;
  }
  .file-upload-thumb-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9px;
    color: var(--gray-500, #6b7280);
    text-align: center;
    padding: 2px;
  }
  .file-upload-preview-name {
    flex: 1;
    min-width: 0;
    font-size: 13px;
    color: var(--gray-700, #374151);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .file-upload-remove {
    position: absolute;
    top: -6px;
    right: -6px;
    flex-shrink: 0;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid #fff;
    border-radius: 50%;
    background: var(--gray-700, #4b5563);
    color: #fff;
    font-size: 11px;
    line-height: 1;
    cursor: pointer;
  }
  .file-upload-remove:hover { background: var(--red-500, #ef4444); color: #fff; }
</style>
@endsection

@section('content')
<div class="dash-shell">
  @include('dashboard.partials.sidebar')

  <div class="dash-main">
    @include('dashboard.partials.header')

    <main class="account-dashboard">
      <div class="page-title-row">
        <div>
          <h1>{{ $edit ? ($profileType === 'business' ? 'Manage Business Information' : 'Manage '.$profileLabel.' Profile') : 'View '.$profileLabel.' Profile' }}</h1>
          <p>Only you can view and update this profile from your account.</p>
        </div>
        <a class="account-primary-action" href="{{ route('dashboard.index') }}">Back to My Profiles</a>
      </div>

      <section class="workspace-panel" aria-labelledby="owned-profile-title">
        @if (session('profile_record_status'))
          <p class="workspace-status" role="status">{{ session('profile_record_status') }}</p>
        @endif
        @if ($errors->any())
          <div class="owned-profile-errors" role="alert">
            <p>Please correct the highlighted fields.</p>
            <ul>@foreach ($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul>
          </div>
        @endif

        @if ($edit)
          <form method="post" action="{{ route('dashboard.profiles.update', ['type' => $profileType, 'id' => $profileId]) }}" enctype="multipart/form-data" data-owned-profile-form novalidate>
            @csrf
            @method('put')
            <input type="hidden" name="active_step" value="{{ old('active_step', 0) }}" data-active-step-input>
            @php
              $activeProfileTab = 0;
              foreach ($profileSteps as $stepIndex => $step) {
                foreach ($step['fields'] as $field) {
                  $fieldHasError = isset($field['name']) && $errors->has($field['name']);
                  if (($field['type'] ?? null) === 'checkboxes') {
                    $fieldHasError = collect($field['options'])->contains(fn ($option) => $errors->has($option['name']));
                  }
                  if ($fieldHasError) {
                    $activeProfileTab = $stepIndex;
                    break 2;
                  }
                }
              }
            @endphp
            <div class="owned-profile-tabs" role="tablist" aria-label="{{ $profileLabel }} profile sections">
              @foreach ($profileSteps as $stepIndex => $step)
                <button type="button" id="owned-profile-tab-{{ $stepIndex }}" role="tab" aria-controls="owned-profile-panel-{{ $stepIndex }}" aria-selected="{{ $stepIndex === $activeProfileTab ? 'true' : 'false' }}" tabindex="{{ $stepIndex === $activeProfileTab ? '0' : '-1' }}" data-owned-profile-tab="{{ $stepIndex }}">{{ $step['title'] }}</button>
              @endforeach
            </div>
            @foreach ($profileSteps as $stepIndex => $step)
              <section class="owned-profile-tab-panel" id="owned-profile-panel-{{ $stepIndex }}" role="tabpanel" aria-labelledby="owned-profile-tab-{{ $stepIndex }}" data-owned-profile-panel="{{ $stepIndex }}" @if ($stepIndex !== $activeProfileTab) hidden @endif>
                <h2 class="owned-profile-step">{{ $step['title'] }}</h2>
                <div class="workspace-form-grid">
                @foreach ($step['fields'] as $field)
                  @php
                    $fieldName = $field['name'] ?? null;
                    $fieldType = $field['type'] ?? 'text';
                    $fieldId = 'owned-profile-' . ($fieldName ?? $stepIndex);
                    $fieldValue = $fieldName ? old($fieldName, $field['selected_value'] ?? '') : '';
                    $wide = !empty($field['wide']) || $fieldType === 'textarea';
                  @endphp
                  @if ($fieldType === 'checkboxes')
                    <fieldset class="owned-profile-checkboxes">
                      <legend>{{ $field['label'] }}</legend>
                      @foreach ($field['options'] as $option)
                        <label>
                          <input type="checkbox" name="{{ $option['name'] }}" value="1" @checked(old($option['name'], $profile->{$option['name']} ?? false))>
                          {{ $option['label'] }}
                        </label>
                      @endforeach
                    </fieldset>
                  @elseif ($fieldType === 'file')
                    @php
                      $existingMedia = $profileMedia->has($fieldName) ? $profileMedia->get($fieldName)->last() : null;
                      $existingIsImage = $existingMedia && str_starts_with($existingMedia->mime_type, 'image/');
                      $existingUrl = $existingMedia
                          ? ($existingMedia->is_public ? ($existingMedia->url ?: Storage::disk($existingMedia->disk)->url($existingMedia->object_key)) : route('profile-media.download', ['media' => $existingMedia->id]))
                          : null;
                    @endphp
                    <div class="workspace-field {{ $wide ? 'full' : '' }}">
                      <label for="{{ $fieldId }}">{{ $field['label'] }}</label>
                      <div class="file-upload-control" data-file-upload-control data-field-label="{{ $field['label'] }}" @if ($existingMedia) data-existing-media-id="{{ $existingMedia->id }}" @endif>
                        <div class="file-upload-preview" data-file-upload-preview data-state="{{ $existingMedia ? 'existing' : 'empty' }}" @if (!$existingMedia) hidden @endif>
                          <span class="file-upload-thumb">
                            <img src="{{ $existingUrl }}" alt="{{ $existingMedia->original_name ?? '' }}" data-file-preview-image @if (!$existingIsImage) hidden @endif>
                            <span class="file-upload-thumb-fallback" data-file-preview-fallback @if (!$existingMedia || $existingIsImage) hidden @endif>{{ $existingMedia ? strtoupper(pathinfo($existingMedia->original_name, PATHINFO_EXTENSION)) : '' }}</span>
                            <button type="button" class="file-upload-remove" data-file-upload-remove aria-label="Remove {{ $field['label'] }}">&times;</button>
                          </span>
                          <span class="file-upload-preview-name" data-file-preview-name>{{ $existingMedia->original_name ?? '' }}</span>
                        </div>
                        <input id="{{ $fieldId }}" name="{{ $fieldName }}{{ !empty($field['multiple']) ? '[]' : '' }}" type="file" accept="{{ $field['accept'] ?? '' }}" data-max-size-mb="1" data-file-input @if (!empty($field['multiple'])) multiple @endif>
                        <p class="upload-hint">Max 1MB · {{ strtoupper(str_replace(['.', ','], ['', ', '], $field['accept'] ?? '')) }}</p>
                      </div>
                      @if (!$existingMedia && ($profile->{$fieldName} ?? null))
                        <div class="owned-profile-existing-media">A previously uploaded file is attached to this profile.</div>
                      @endif
                      @error($fieldName)<span class="password-error" role="alert">{{ $message }}</span>@enderror
                    </div>
                  @else
                  <div class="workspace-field {{ $wide ? 'full' : '' }}">
                    <label for="{{ $fieldId }}">{{ $field['label'] }} @if (!empty($field['required'])) * @endif</label>
                    @if ($fieldType === 'select')
                      <select id="{{ $fieldId }}" name="{{ $fieldName }}" @required(!empty($field['required']))>
                        <option value="">{{ $field['placeholder'] ?? 'Select an option' }}</option>
                        @foreach ($field['form_options'] as $option)
                          <option value="{{ $option['value'] }}" @selected((string) $fieldValue === (string) $option['value'])>{{ $option['label'] }}</option>
                        @endforeach
                      </select>
                    @elseif ($fieldType === 'textarea')
                      <textarea id="{{ $fieldId }}" name="{{ $fieldName }}" rows="4" @required(!empty($field['required']))>{{ $fieldValue }}</textarea>
                    @else
                      @if ($fieldType === 'tel')
                        <div class="phone-input-group">
                          @include('components.phone-country-code')
                          <input id="{{ $fieldId }}" name="{{ $fieldName }}" type="tel" value="{{ $fieldValue }}" @required(!empty($field['required']))>
                        </div>
                      @else
                        <input id="{{ $fieldId }}" name="{{ $fieldName }}" type="{{ in_array($fieldType, ['email', 'number', 'url'], true) ? $fieldType : 'text' }}" value="{{ $fieldValue }}" @required(!empty($field['required'])) @if ($fieldType === 'number') min="0" step="any" @endif>
                      @endif
                    @endif
                    @error($fieldName)<span class="password-error" role="alert">{{ $message }}</span>@enderror
                  </div>
                  @endif
                @endforeach
                </div>
              </section>
            @endforeach
            <div class="owned-profile-actions">
              <button class="account-primary-action" type="submit">Save Profile</button>
              <a class="account-primary-action" href="{{ route('dashboard.profiles.show', ['type' => $profileType, 'id' => $profileId]) }}">Cancel</a>
            </div>
          </form>
        @else
          <h2 id="owned-profile-title">{{ $profileLabel }} details</h2>
          <dl class="owned-profile-fields">
            @forelse ($profileItems as $item)
              <div class="owned-profile-field">
                <dt>{{ $item['label'] }}</dt>
                <dd>
                  @if (!empty($item['is_image']))
                    <a href="{{ $item['value'] }}" target="_blank" rel="noopener noreferrer" class="owned-profile-image-link">
                      <img src="{{ $item['value'] }}" alt="{{ $item['label'] }}" class="owned-profile-image-preview">
                    </a>
                  @elseif ($item['is_url'])
                    <a href="{{ $item['value'] }}" target="_blank" rel="noopener noreferrer">{{ $item['value'] }}</a>
                  @else
                    {{ $item['value'] }}
                  @endif
                </dd>
              </div>
            @empty
              <p>No profile details have been added yet.</p>
            @endforelse
          </dl>
          <div class="owned-profile-actions">
            <a class="account-primary-action" href="{{ route('dashboard.profiles.edit', ['type' => $profileType, 'id' => $profileId]) }}">Manage Profile</a>
          </div>
        @endif
      </section>
    </main>
  </div>
</div>
@push('scripts')
<script>
  const ownedProfileTabs = [...document.querySelectorAll('[data-owned-profile-tab]')];
  const ownedProfilePanels = [...document.querySelectorAll('[data-owned-profile-panel]')];
  const ownedProfileForm = document.querySelector('[data-owned-profile-form]');
  const activeStepInput = document.querySelector('[data-active-step-input]');

  function activateOwnedProfileTab(tab, focus = false) {
    ownedProfileTabs.forEach(item => {
      const selected = item === tab;
      item.setAttribute('aria-selected', String(selected));
      item.tabIndex = selected ? 0 : -1;
    });
    ownedProfilePanels.forEach(panel => {
      panel.hidden = panel.dataset.ownedProfilePanel !== tab.dataset.ownedProfileTab;
    });
    if (activeStepInput) activeStepInput.value = tab.dataset.ownedProfileTab;
    if (focus) tab.focus();
  }

  ownedProfileTabs.forEach((tab, index) => {
    tab.addEventListener('click', () => activateOwnedProfileTab(tab));
    tab.addEventListener('keydown', event => {
      const next = event.key === 'ArrowRight' ? (index + 1) % ownedProfileTabs.length
        : event.key === 'ArrowLeft' ? (index - 1 + ownedProfileTabs.length) % ownedProfileTabs.length
        : event.key === 'Home' ? 0
        : event.key === 'End' ? ownedProfileTabs.length - 1
        : -1;
      if (next >= 0) {
        event.preventDefault();
        activateOwnedProfileTab(ownedProfileTabs[next], true);
      }
    });
  });

  // Save only ever touches the section (tab) the visitor is currently on —
  // each tab's required fields are validated and submitted independently,
  // so switching to Attachments and saving a photo never gets blocked by
  // an unrelated field (e.g. a missing phone number) sitting on another
  // tab the visitor isn't even looking at right now.
  ownedProfileForm?.addEventListener('submit', event => {
    const activePanel = ownedProfilePanels.find(panel => !panel.hidden);
    const invalidField = activePanel && [...activePanel.querySelectorAll(':invalid')][0];
    if (!invalidField) return;
    event.preventDefault();
    invalidField.reportValidity();
  });

  // Each file field shows one preview at a time, directly above its own
  // "Choose File" control: the already-saved image (state="existing") by
  // default, or the just-picked pending file (state="pending") once the
  // visitor selects something new in that same box — the new one visually
  // replaces the old one immediately, and the actual swap/delete on the
  // server happens together when "Save Profile" is submitted.
  ownedProfileForm?.querySelectorAll('input[type="file"][data-max-size-mb]').forEach(input => {
    const control = input.closest('[data-file-upload-control]');
    const preview = control?.querySelector('[data-file-upload-preview]');
    const previewImage = preview?.querySelector('[data-file-preview-image]');
    const previewFallback = preview?.querySelector('[data-file-preview-fallback]');
    const previewName = preview?.querySelector('[data-file-preview-name]');
    const removeButton = preview?.querySelector('[data-file-upload-remove]');
    const existingMediaId = control?.dataset.existingMediaId || null;
    const fieldLabel = control?.dataset.fieldLabel || 'File';
    if (!control || !preview) return;

    function showPending(file) {
      preview.dataset.state = 'pending';
      preview.hidden = false;
      if (previewName) previewName.textContent = file.name;
      if (file.type.startsWith('image/')) {
        if (previewImage) {
          if (previewImage.src.startsWith('blob:')) URL.revokeObjectURL(previewImage.src);
          previewImage.src = URL.createObjectURL(file);
          previewImage.hidden = false;
        }
        if (previewFallback) previewFallback.hidden = true;
      } else {
        if (previewImage) previewImage.hidden = true;
        if (previewFallback) {
          previewFallback.textContent = file.name.split('.').pop()?.toUpperCase() || 'FILE';
          previewFallback.hidden = false;
        }
      }
    }

    function revertToEmpty() {
      preview.dataset.state = 'empty';
      preview.hidden = true;
      if (previewImage) {
        if (previewImage.src.startsWith('blob:')) URL.revokeObjectURL(previewImage.src);
        previewImage.src = '';
        previewImage.hidden = true;
      }
      if (previewFallback) previewFallback.hidden = true;
      if (previewName) previewName.textContent = '';
    }

    input.addEventListener('change', () => {
      const maxBytes = Number(input.dataset.maxSizeMb) * 1024 * 1024;
      const oversized = [...input.files].find(file => file.size > maxBytes);
      if (oversized) {
        input.setCustomValidity(`"${oversized.name}" is ${(oversized.size / 1024 / 1024).toFixed(2)}MB — files must be ${input.dataset.maxSizeMb}MB or smaller.`);
        input.reportValidity();
        input.value = '';
        return;
      }

      input.setCustomValidity('');
      const file = input.files[0];
      if (!file) return;
      showPending(file);
    });

    removeButton?.addEventListener('click', () => {
      if (preview.dataset.state === 'pending') {
        // Just cancels the pending selection — nothing saved changes yet.
        input.value = '';
        input.setCustomValidity('');
        revertToEmpty();
        return;
      }

      // Removing the already-saved file: stage it for deletion (actually
      // deleted server-side on Save), then clear the box for a fresh pick.
      if (existingMediaId && ownedProfileForm) {
        const marker = document.createElement('input');
        marker.type = 'hidden';
        marker.name = 'delete_media_ids[]';
        marker.value = existingMediaId;
        ownedProfileForm.appendChild(marker);
      }
      revertToEmpty();
    });
  });
</script>
@endpush
@endsection
