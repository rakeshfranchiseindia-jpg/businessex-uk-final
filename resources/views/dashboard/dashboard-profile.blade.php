@extends('layouts.dashboard')

@section('title')
Edit Profile | BusinessX
@endsection
@section('description')
Edit your BusinessX account profile.
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/account-dashboard.css') }}">
@endsection

@section('content')

  <div class="dash-shell">
    @include('dashboard.partials.sidebar')

    <div class="dash-main">
      @include('dashboard.partials.header')

      <main class="account-dashboard">
        <div class="page-title-row">
          <div>
            <h1>Edit Profile</h1>
            <p>Update the contact details associated with your account.</p>
          </div>
          <a class="account-primary-action" href="{{ route('dashboard.index') }}">Back to Dashboard</a>
        </div>

        <section class="workspace-panel" aria-labelledby="profile-form-title">
          <h2 id="profile-form-title">Personal Information</h2>
          @if (session('profile_status'))
            <p class="workspace-status" role="status">{{ session('profile_status') }}</p>
          @endif
          @if (session('profile_error'))
            <p class="workspace-status" role="alert">{{ session('profile_error') }}</p>
          @endif
          <form id="profile-form" method="post" action="{{ route('dashboard.profile.update') }}">
            @csrf
            @method('put')
            <div class="workspace-form-grid">
              <div class="workspace-field">
                <label for="profile-name">Name *</label>
                <input id="profile-name" name="name" autocomplete="name" pattern="[A-Za-z\s]+" title="Name should only contain letters and spaces" value="{{ old('name', $account->name) }}" required>
                @error('name') <span role="alert">{{ $message }}</span> @enderror
              </div>
              <div class="workspace-field workspace-field-locked">
                <label for="profile-email">Email <span class="workspace-lock-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span></label>
                <input id="profile-email" type="email" value="{{ $account->email }}" readonly disabled aria-describedby="profile-email-locked-hint">
                <p id="profile-email-locked-hint" class="workspace-locked-hint">Your registered email can't be changed here. Contact support if you need to update it.</p>
              </div>
              <div class="workspace-field workspace-field-locked">
                <label for="profile-phone">Mobile <span class="workspace-lock-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span></label>
                <div class="phone-input-group">
                  @include('components.phone-country-code', ['disabled' => true])
                  <input id="profile-phone" type="tel" value="{{ $account->mobile }}" readonly disabled aria-describedby="profile-phone-locked-hint">
                </div>
                <p id="profile-phone-locked-hint" class="workspace-locked-hint">Your registered mobile number can't be changed here. Contact support if you need to update it.</p>
              </div>
              <div class="workspace-field">
                <label for="profile-location">Location *</label>
                <input id="profile-location" name="location" autocomplete="address-level2" value="{{ old('location', $account->location) }}" required>
                @error('location') <span role="alert">{{ $message }}</span> @enderror
              </div>
              <div class="workspace-field">
                <label for="profile-designation">Designation *</label>
                <input id="profile-designation" name="designation" pattern="[A-Za-z\s]+" title="Designation should only contain letters and spaces" value="{{ old('designation', $account->designation) }}" required>
                @error('designation') <span role="alert">{{ $message }}</span> @enderror
              </div>
              <div class="workspace-field">
                <label for="profile-company">Company *</label>
                <input id="profile-company" name="company" pattern="[A-Za-z\s]+" title="Company should only contain letters and spaces" value="{{ old('company', $account->company_name) }}" autocomplete="organization" required>
                @error('company') <span role="alert">{{ $message }}</span> @enderror
              </div>
            </div>
            <button class="workspace-submit" type="submit">Save Changes</button>
          </form>
        </section>
      </main>
    </div>
  </div>

  

@endsection
