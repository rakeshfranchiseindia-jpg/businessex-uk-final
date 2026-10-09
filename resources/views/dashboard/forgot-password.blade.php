@extends('layouts.app')

@section('title')
Forgot Password | BusinessX
@endsection
@section('description')
Request a password reset for your BusinessX account.
@endsection

@section('head')
<style>
    body { min-height: 100vh; display: flex; flex-direction: column; background: var(--gray-50); font-family: 'Inter', sans-serif; }
    .auth-header { background: var(--white); color: var(--navy-900); border-bottom: 1px solid var(--gray-200); }
    .auth-header .container { height: 72px; display: flex; align-items: center; justify-content: space-between; }
    .auth-logo { display: inline-flex; align-items: center; }
    .auth-logo img { display: block; width: 211px; height: auto; }
    .auth-login { padding: 9px 14px; border: 1px solid var(--gray-300); border-radius: 5px; color: var(--navy-800); font-size: 13px; font-weight: 600; }
    .auth-login:hover { color: var(--gold-400); border-color: var(--gold-400); }
    .reset-main { flex: 1; display: grid; place-items: center; padding: 52px 24px; }
    .reset-panel { width: 100%; max-width: 460px; padding: 36px; border: 1px solid var(--gray-200); border-radius: 8px; background: var(--white); box-shadow: 0 8px 30px rgba(16,40,74,.06); }
    .reset-panel h1 { margin-bottom: 8px; color: var(--navy-900); font-size: 25px; line-height: 1.25; font-weight: 700; }
    .reset-panel .intro { margin-bottom: 26px; color: var(--gray-600); font-size: 14px; line-height: 1.6; }
    .reset-form label { display: block; margin-bottom: 8px; color: var(--gray-800); font-size: 13px; font-weight: 600; }
    .reset-form input { width: 100%; height: 48px; padding: 0 13px; border: 1px solid var(--gray-300); border-radius: 5px; background: var(--white); color: var(--gray-900); font-size: 14px; }
    .reset-form input::placeholder { color: var(--gray-400); }
    .reset-form input:focus { outline: 2px solid rgba(19,141,227,.2); border-color: var(--gold-500); }
    .reset-submit { width: 100%; min-height: 48px; margin-top: 18px; padding: 12px 16px; border-radius: 5px; background: var(--gold-500); color: var(--navy-900); font-size: 14px; font-weight: 700; transition: background .18s ease; }
    .reset-submit:hover { background: var(--gold-400); }
    .reset-submit:focus-visible, .auth-login:focus-visible, .return-login:focus-visible, .auth-footer a:focus-visible { outline: 2px solid var(--gold-600); outline-offset: 3px; }
    .form-status { margin-top: 14px; padding: 11px 12px; border-left: 3px solid var(--green-500); border-radius: 3px; background: var(--green-100); color: var(--gray-800); font-size: 13px; line-height: 1.5; }
    .form-errors { margin-top: 14px; padding: 11px 12px; border-left: 3px solid var(--red-500); border-radius: 3px; background: #FEF2F2; color: #991B1B; font-size: 13px; line-height: 1.5; }
    .form-status[hidden] { display: none; }
    .continue-reset { display: inline-flex; margin-top: 12px; color: var(--gold-600); font-size: 13px; font-weight: 700; }
    .continue-reset:hover { text-decoration: underline; }
    .return-login { display: block; margin-top: 20px; color: var(--gray-600); font-size: 13px; text-align: center; }
    .return-login a { color: var(--gold-600); font-weight: 700; }
    .return-login a:hover { text-decoration: underline; }
    .auth-footer { padding: 18px 0; border-top: 1px solid var(--gray-200); background: var(--white); color: var(--gray-500); font-size: 11px; }
    .auth-footer .container { display: flex; justify-content: space-between; align-items: center; gap: 16px; }
    .auth-footer nav { display: flex; flex-wrap: wrap; gap: 16px; }
    .auth-footer a:hover { color: var(--gold-600); }
    @media (max-width: 560px) {
      .auth-header .container { height: 64px; }
      .auth-logo img { width: 180px; height: auto; }
      .reset-main { padding: 32px 16px; }
      .reset-panel { padding: 26px 22px; }
      .auth-footer .container { flex-direction: column; text-align: center; }
      .auth-footer nav { justify-content: center; gap: 10px 14px; }
    }
  </style>
@endsection

@section('content')

  <header class="auth-header">
    <div class="container">
      <a class="auth-logo" href="{{ route('home') }}" aria-label="BusinessX home"><img src="{{ asset('assets/img/businessx-logo.png?v=20261008') }}" alt="BusinessX"></a>
      <a class="auth-login" href="{{ route('login') }}">Login</a>
    </div>
  </header>

  <main class="reset-main">
    <section class="reset-panel" aria-labelledby="reset-title">
      <h1 id="reset-title">Forgot Password</h1>
      <p class="intro">Enter your registered email address to reset your password.</p>
      @if (session('status'))
        <p class="form-status" role="status">{{ session('status') }}</p>
      @endif
      @if ($errors->any())
        <div class="form-errors" role="alert">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif
      <form class="reset-form" id="reset-form" method="post" action="{{ route('password.email') }}">
        @csrf
        <label for="reset-email">Email Address</label>
        <input id="reset-email" name="email" type="email" value="{{ old('email') }}" placeholder="Enter Your Email ID" autocomplete="email" required>
        <button class="reset-submit" type="submit">Send Reset Link</button>
      </form>
      <p class="return-login">Remember your password? <a href="{{ route('login') }}">Back to Login</a></p>
    </section>
  </main>

  

@endsection
