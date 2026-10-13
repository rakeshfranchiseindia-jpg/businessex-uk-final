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
              <div class="workspace-field">
                <label for="profile-email">Email *</label>
                <input id="profile-email" name="email" type="email" autocomplete="email" value="{{ old('email', $account->email) }}" required>
                @error('email') <span role="alert">{{ $message }}</span> @enderror
              </div>
              <div class="workspace-field">
                <label for="profile-phone">Mobile *</label>
                <div class="phone-input-group">
                  @include('components.phone-country-code')
                  <input id="profile-phone" name="phone" type="tel" autocomplete="tel" value="{{ old('phone', $account->mobile) }}" required>
                </div>
                @error('phone') <span role="alert">{{ $message }}</span> @enderror
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
