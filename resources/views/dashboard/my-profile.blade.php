@extends('layouts.dashboard')

@section('title')
{{ $dashboardTitle }} | BusinessX
@endsection

@section('description')
View and manage your {{ strtolower($profileLabel) }} profile.
@endsection

@section('head')
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
</style>
@endsection

@section('content')
<div class="workspace-shell">
  <aside class="workspace-sidebar">
    <a class="workspace-brand" href="{{ route('home') }}" aria-label="BusinessX home"><img src="{{ asset('assets/img/businessx-logo.png?v=20261008') }}" alt="BusinessX"></a>
    <div class="workspace-user"><strong>{{ auth()->user()->name }}</strong><span>{{ \Illuminate\Support\Str::title(auth()->user()->reg_profile ?: 'member') }} account</span></div>
    <nav aria-label="Account navigation">
      <a href="{{ route('dashboard.index') }}">Dashboard</a>
      <a href="{{ route('dashboard.profile') }}">Edit Profile</a>
      <a href="{{ route('dashboard.password') }}">Change Password</a>
      <a href="{{ route('dashboard.my-plan') }}">My Plan</a>
      <span class="workspace-nav-heading">My Interaction</span>
      <a href="{{ route('dashboard.inbox') }}">BX Inbox</a>
      <a href="{{ route('dashboard.proposals.sent') }}">Proposal sent</a>
      <a href="{{ route('dashboard.proposals.received') }}">Proposal received</a>
      <a href="{{ route('dashboard.instant-response') }}">Instant Response</a>
      <a href="#dashboard-live-chat" data-open-live-chat>Live Chat</a>
    </nav>
  </aside>

  <div class="workspace-main">
    <header class="workspace-topbar">
      <strong>My Account / {{ $profileLabel }} Profile</strong>
      <form method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="workspace-signout">Sign Out</button>
      </form>
    </header>
    <main class="workspace-content">
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
                    <div class="workspace-field {{ $wide ? 'full' : '' }}">
                      <label for="{{ $fieldId }}">{{ $field['label'] }}</label>
                      <input id="{{ $fieldId }}" name="{{ $fieldName }}{{ !empty($field['multiple']) ? '[]' : '' }}" type="file" accept="{{ $field['accept'] ?? '' }}" @if (!empty($field['multiple'])) multiple @endif>
                      @if ($profileMedia->has($fieldName))
                        <div class="owned-profile-existing-media">
                          <span>Current files:</span>
                          @foreach ($profileMedia->get($fieldName) as $media)
                            <a href="{{ $media->is_public ? ($media->url ?: Storage::disk($media->disk)->url($media->object_key)) : route('profile-media.download', ['media' => $media->id]) }}" target="_blank" rel="noopener noreferrer">{{ $media->original_name }}</a>
                          @endforeach
                        </div>
                      @elseif ($profile->{$fieldName} ?? null)
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
                  @if ($item['is_url'])
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

  function activateOwnedProfileTab(tab, focus = false) {
    ownedProfileTabs.forEach(item => {
      const selected = item === tab;
      item.setAttribute('aria-selected', String(selected));
      item.tabIndex = selected ? 0 : -1;
    });
    ownedProfilePanels.forEach(panel => {
      panel.hidden = panel.dataset.ownedProfilePanel !== tab.dataset.ownedProfileTab;
    });
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

  ownedProfileForm?.addEventListener('submit', event => {
    const invalidField = [...ownedProfileForm.querySelectorAll(':invalid')][0];
    if (!invalidField) return;
    event.preventDefault();
    const panel = invalidField.closest('[data-owned-profile-panel]');
    const tab = panel && ownedProfileTabs.find(item => item.dataset.ownedProfileTab === panel.dataset.ownedProfilePanel);
    if (tab) activateOwnedProfileTab(tab);
    invalidField.reportValidity();
  });
</script>
@endpush
@endsection
