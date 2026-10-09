<?php
$page = $page ?? 'home';
$registrationRoutes = auth()->check()
  ? [
    'business' => route('dashboard.profiles.create.form', 'business'),
    'investor' => route('dashboard.profiles.create.form', 'investor'),
    'mentor' => route('dashboard.profiles.create.form', 'mentor'),
    'startup' => route('dashboard.profiles.create.form', 'startup'),
  ]
  : [
    'business' => route('business-registration'),
    'investor' => route('investor-registration'),
    'mentor' => route('mentor-registration'),
    'startup' => route('startup-registration'),
  ];
?>
<header class="bx-header">
  <div class="container">
    <a href="{{ route('home') }}" class="bx-logo">
      <img src="{{ asset('assets/img/businessx-logo.png?v=20261008') }}" alt="BusinessX">
    </a>
    <nav class="bx-nav">
      <div class="nav-item"><a href="{{ route('home') }}" class="<?= $page === 'home' ? 'active' : '' ?>">Home</a></div>
      <div class="nav-item">
        <a href="{{ route('business-listing') }}" class="<?= $page === 'listing' ? 'active' : '' ?>">Bx Listing
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="6 9 12 15 18 9"/></svg>
        </a>
        <div class="dropdown-menu">
          <a href="{{ route('business-listing') }}">
            <span class="dd-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg></span>
            <span>Business<span class="dd-sub">Businesses for sale &amp; opportunities</span></span>
          </a>
          <a href="{{ route('investor-listing') }}">
            <span class="dd-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M8 10h8M8 14h8"/></svg></span>
            <span>Investor<span class="dd-sub">Investors looking to invest or buy</span></span>
          </a>
          <a href="{{ route('startup-listing') }}">
            <span class="dd-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg></span>
            <span>Startup<span class="dd-sub">Startups looking for funds</span></span>
          </a>
          <a href="{{ route('mentor-listing') }}">
            <span class="dd-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
            <span>Mentor<span class="dd-sub">Mentors to guide &amp; coach you</span></span>
          </a>
        </div>
      </div>
      <div class="nav-item">
        <a href="{{ $registrationRoutes['business'] }}" class="<?= $page === 'registration' ? 'active' : '' ?>">Registration
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="6 9 12 15 18 9"/></svg>
        </a>
        <div class="dropdown-menu">
          <a href="{{ $registrationRoutes['business'] }}">
            <span class="dd-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg></span>
            <span>Business<span class="dd-sub">Create business profile</span></span>
          </a>
          <a href="{{ $registrationRoutes['investor'] }}">
            <span class="dd-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M8 10h8M8 14h8"/></svg></span>
            <span>Investor<span class="dd-sub">Create investor profile</span></span>
          </a>
          <a href="{{ $registrationRoutes['mentor'] }}">
            <span class="dd-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
            <span>Mentor<span class="dd-sub">Create mentor profile</span></span>
          </a>
          <a href="{{ $registrationRoutes['startup'] }}">
            <span class="dd-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg></span>
            <span>Startup<span class="dd-sub">Create startup profile</span></span>
          </a>
        </div>
      </div>
      <div class="nav-item"><a href="{{ route('pricing') }}" class="<?= $page === 'pricing' ? 'active' : '' ?>">Pricing</a></div>
      <div class="nav-item"><a href="{{ route('article') }}" class="<?= $page === 'article' ? 'active' : '' ?>">Bx Insights</a></div>
    </nav>
    <div class="bx-header-actions">
      @guest
      <a href="{{ route('login') }}" class="btn-login">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Sign In
      </a>
      {{--<a href="{{ route('registration') }}" class="btn-join">Register Free</a> --}}
      @else
      <a href="{{ route('dashboard.index') }}" class="btn-login">Dashboard</a>
      <form class="header-logout-form" method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-join">Sign Out</button>
      </form>
      @endguest
    </div>
    <button class="mobile-toggle" aria-label="Menu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
  </div>
  <div class="mobile-menu">
    <a class="mm-link" href="{{ route('home') }}">Home</a>
    <div class="mm-group">
      <button class="mm-group-btn">Bx Listing
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div class="mm-sub">
        <a href="{{ route('business-listing', array (
  'type' => 'business',
)) }}">Business</a>
        <a href="{{ route('business-listing', array (
  'type' => 'investor',
)) }}">Investor</a>
        <a href="{{ route('business-listing', array (
  'type' => 'startup',
)) }}">Startup</a>
        <a href="{{ route('business-listing', array (
  'type' => 'mentor',
)) }}">Mentor</a>
      </div>
    </div>
    <div class="mm-group">
      <button class="mm-group-btn">Registration
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div class="mm-sub">
        <a href="{{ $registrationRoutes['business'] }}">Business</a>
        <a href="{{ $registrationRoutes['investor'] }}">Investor</a>
        <a href="{{ $registrationRoutes['mentor'] }}">Mentor</a>
        <a href="{{ $registrationRoutes['startup'] }}">Startup</a>
      </div>
    </div>
    <a class="mm-link" href="{{ route('pricing') }}">Pricing</a>
    <a class="mm-link" href="{{ route('article') }}">Bx Insights</a>
    <div class="mm-actions">
    @guest
    <a class="btn-login" href="{{ route('login') }}">Sign In</a>
    <a class="btn-join" href="{{ route('registration') }}">Register Free</a>
    @else
    <a class="btn-login" href="{{ route('dashboard.index') }}">Dashboard</a>
    <form class="header-logout-form" method="post" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn-join">Sign Out</button>
    </form>
    @endguest
    </div>
  </div>
</header>
