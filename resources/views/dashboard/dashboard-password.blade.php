@extends('layouts.dashboard')

@section('title')
Change Password | BusinessX
@endsection
@section('description')
Change your BusinessX account password.
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/account-dashboard.css') }}">
  <style>
    .password-field-wrap { position: relative; }
    .password-field-wrap input { padding-right: 46px; }
    .password-toggle { position: absolute; top: 0; right: 0; display: grid; place-items: center; width: 42px; height: 42px; color: var(--gray-500); }
    .password-toggle:hover { color: var(--navy-700); }
    .password-toggle svg { width: 18px; height: 18px; }
    .password-hint { margin-top: 12px; color: var(--gray-500); font-size: 11px; line-height: 1.5; }
    .workspace-status.error { border-left-color: var(--red-500); background: #FEF2F2; }
    .password-error { margin-top: 6px; color: var(--red-500); font-size: 12px; }
    .password-toggle:focus-visible { outline: 2px solid var(--gold-500); outline-offset: 2px; }
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
            <h1>Change Password</h1>
            <p>Keep your account secure with a strong password.</p>
          </div>
          <a class="account-primary-action" href="{{ route('dashboard.index') }}">Back to Dashboard</a>
        </div>

        <section class="workspace-panel" aria-labelledby="password-form-title">
          <h2 id="password-form-title">Update Password</h2>
          @if (session('password_status'))
            <p class="workspace-status" role="status">{{ session('password_status') }}</p>
          @endif
          @if ($errors->any())
            <p class="workspace-status error" role="alert">Please correct the password fields below.</p>
          @endif
          <form id="password-form" method="post" action="{{ route('dashboard.password.update') }}">
            @csrf
            @method('put')
            <div class="workspace-form-grid">
              <div class="workspace-field full">
                <label for="old-password">Old Password *</label>
                <div class="password-field-wrap">
                  <input id="old-password" name="oldPassword" type="password" autocomplete="current-password" required>
                  <button class="password-toggle" type="button" aria-label="Show old password" aria-pressed="false" data-password-target="old-password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                </div>
                @error('oldPassword') <span class="password-error" role="alert">{{ $message }}</span> @enderror
              </div>
              <div class="workspace-field">
                <label for="new-password">New Password *</label>
                <div class="password-field-wrap">
                  <input id="new-password" name="newPassword" type="password" autocomplete="new-password" minlength="8" pattern="(?=.*[A-Za-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}" aria-describedby="password-hint" required>
                  <button class="password-toggle" type="button" aria-label="Show new password" aria-pressed="false" data-password-target="new-password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                </div>
                @error('newPassword') <span class="password-error" role="alert">{{ $message }}</span> @enderror
              </div>
              <div class="workspace-field">
                <label for="confirm-password">Confirm Password *</label>
                <div class="password-field-wrap">
                  <input id="confirm-password" name="confirmPassword" type="password" autocomplete="new-password" required>
                  <button class="password-toggle" type="button" aria-label="Show confirmation password" aria-pressed="false" data-password-target="confirm-password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                </div>
                @error('confirmPassword') <span class="password-error" role="alert">{{ $message }}</span> @enderror
              </div>
            </div>
            <p class="password-hint" id="password-hint">Use at least 8 characters, including a letter, a number, and a symbol.</p>
            <button class="workspace-submit" type="submit">Update Password</button>
          </form>
        </section>
      </main>
    </div>
  </div>

  

  <script>
    document.querySelectorAll('.password-toggle').forEach(button => {
      button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.passwordTarget);
        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        button.setAttribute('aria-pressed', String(visible));
        button.setAttribute('aria-label', `${visible ? 'Hide' : 'Show'} ${button.dataset.passwordTarget.replace('-', ' ')}`);
      });
    });

  </script>

@endsection
