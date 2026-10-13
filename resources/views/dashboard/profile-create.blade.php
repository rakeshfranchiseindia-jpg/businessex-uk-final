@extends('layouts.dashboard')

@section('title')
Create Profile | BusinessX
@endsection

@section('description')
Choose the kind of profile you want to create.
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/account-dashboard.css') }}">
<style>
  .profile-create-options { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-top: 22px; }
  .profile-create-option { display: grid; gap: 8px; padding: 22px; border: 1px solid var(--gray-200); border-radius: 8px; background: var(--white); color: var(--navy-800); }
  .profile-create-option:hover { border-color: #1769d2; box-shadow: 0 4px 16px rgba(23, 105, 210, .1); }
  .profile-create-option strong { font-size: 16px; }
  .profile-create-option span { color: var(--gray-500); font-size: 13px; line-height: 1.5; }
  @media (max-width: 700px) { .profile-create-options { grid-template-columns: 1fr; } }
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
          <h1>Create Profile</h1>
          <p>Choose the profile type you want to add to your account.</p>
        </div>
        <a class="account-primary-action" href="{{ route('dashboard.index') }}">Back to Dashboard</a>
      </div>
      <div class="profile-create-options">
        @foreach ([
          'business' => ['Business', 'Promote a business, sale or growth opportunity.'],
          'startup' => ['Startup', 'Introduce your startup and its funding needs.'],
          'investor' => ['Investor', 'Share your investment focus and preferences.'],
          'mentor' => ['Mentor', 'Present your expertise and mentoring support.'],
        ] as $type => [$label, $description])
          <a class="profile-create-option" href="{{ route('dashboard.profiles.create.form', $type) }}">
            <strong>{{ $label }} Profile</strong>
            <span>{{ $description }}</span>
          </a>
        @endforeach
      </div>
    </main>
  </div>
</div>
@endsection
