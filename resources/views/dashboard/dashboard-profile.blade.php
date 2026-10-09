@extends('layouts.dashboard')

@section('title')
Edit Profile | BusinessX
@endsection
@section('description')
Edit your BusinessX account profile.
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/account-dashboard.css') }}">
@endsection

@section('content')

  <div class="workspace-shell">
    <aside class="workspace-sidebar">
      <a class="workspace-brand" href="{{ route('home') }}" aria-label="BusinessX home"><img src="{{ asset('assets/img/businessx-logo.png?v=20261008') }}" alt="BusinessX"></a>
      <div class="workspace-user"><strong>{{ auth()->user()->name }}</strong><span>{{ \Illuminate\Support\Str::title(auth()->user()->reg_profile ?: 'member') }} account</span></div>
      <nav aria-label="Account navigation">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <a href="{{ route('dashboard.profile') }}" aria-current="page">Edit Profile</a>
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
        <strong>My Account / Edit Profile</strong>
        <form method="post" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="workspace-signout">Sign Out</button>
        </form>
      </header>
      <main class="workspace-content">
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
                <input id="profile-name" name="name" autocomplete="name" value="{{ old('name', $account->name) }}" required>
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
                <input id="profile-designation" name="designation" value="{{ old('designation', $account->designation) }}" required>
                @error('designation') <span role="alert">{{ $message }}</span> @enderror
              </div>
              <div class="workspace-field">
                <label for="profile-company">Company *</label>
                <input id="profile-company" name="company" value="{{ old('company', $account->company_name) }}" autocomplete="organization" required>
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
