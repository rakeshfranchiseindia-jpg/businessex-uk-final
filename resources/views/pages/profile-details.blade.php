@extends('layouts.app')

@section('title')
This is a testing headline. | BusinessX
@endsection
@section('description')
Business profile and investment opportunity for Kreate Technologies pvt ltd.
@endsection

@section('head')
<style>
    body { background: var(--gray-50); }
    .profile-header { background: var(--white); color: var(--navy-900); border-bottom: 1px solid var(--gray-200); }
    .profile-header .container { min-height: 72px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
    .profile-logo img { width: 211px; height: auto; }
    .profile-nav { display: flex; align-items: center; gap: 26px; font-size: 13px; font-weight: 600; }
    .profile-nav a { color: var(--navy-800); }
    .profile-nav a:hover, .profile-nav a[aria-current="page"] { color: var(--gold-400); }
    .profile-actions { display: flex; align-items: center; gap: 10px; }
    .profile-actions a { padding: 9px 14px; border-radius: 6px; font-size: 13px; font-weight: 700; }
    .profile-login { color: var(--navy-800); border: 1px solid var(--gray-300); }
    .profile-register { color: var(--navy-900); background: var(--gold-400); }
    .profile-main { padding: 26px 0 64px; }
    .breadcrumbs { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; color: var(--gray-500); font-size: 12px; margin-bottom: 22px; }
    .breadcrumbs a:hover { color: var(--gold-600); }
    .breadcrumbs .current { color: var(--gray-700); font-weight: 600; }
    .profile-intro { padding: 32px 36px; background: var(--white); border: 1px solid var(--gray-200); border-top: 3px solid var(--gold-500); border-radius: 8px 8px 0 0; }
    .intro-topline { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 10px; }
    .profile-type, .verified-label { display: inline-flex; align-items: center; gap: 6px; padding: 5px 9px; border-radius: 999px; font-size: 11px; font-weight: 700; }
    .profile-type { color: #166534; background: #DCFCE7; }
    .verified-label { color: var(--gold-600); background: var(--gold-100); }
    .profile-intro h1 { max-width: 850px; color: var(--navy-900); font-size: clamp(26px, 4vw, 36px); line-height: 1.18; font-weight: 700; margin-bottom: 9px; }
    .profile-intro > p { color: var(--gray-600); font-size: 15px; max-width: 740px; }
    .intro-meta { display: flex; flex-wrap: wrap; gap: 18px; margin-top: 18px; color: var(--gray-500); font-size: 13px; }
    .intro-meta span { display: inline-flex; align-items: center; gap: 7px; }
    .intro-meta svg { width: 15px; height: 15px; color: var(--gold-600); }
    .section-nav { position: sticky; top: 0; z-index: 20; display: flex; overflow-x: auto; background: var(--white); border: 1px solid var(--gray-200); border-top: 0; box-shadow: var(--shadow-sm); }
    .section-nav a { flex: 1; min-width: 112px; padding: 14px 18px; text-align: center; color: var(--gray-600); border-bottom: 2px solid transparent; font-size: 13px; font-weight: 600; white-space: nowrap; }
    .section-nav a:hover { color: var(--navy-900); background: var(--gray-25); border-bottom-color: var(--gold-500); }
    .profile-layout { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 24px; align-items: start; margin-top: 24px; }
    .profile-content { min-width: 0; }
    .profile-section { padding: 26px; margin-bottom: 16px; background: var(--white); border: 1px solid var(--gray-200); border-radius: 8px; scroll-margin-top: 86px; }
    .section-title { display: flex; align-items: center; gap: 10px; padding-bottom: 17px; margin-bottom: 19px; border-bottom: 1px solid var(--gray-100); }
    .section-title .section-icon { display: grid; place-items: center; width: 34px; height: 34px; color: var(--navy-700); background: var(--gold-100); border-radius: 6px; }
    .section-title svg { width: 18px; height: 18px; }
    .section-title h2 { color: var(--navy-900); font-size: 18px; font-weight: 700; }
    .summary-copy { color: var(--gray-600); font-size: 14px; line-height: 1.75; }
    .summary-copy p + p { margin-top: 12px; }
    .field-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 24px; }
    .field { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; padding: 14px 0; border-bottom: 1px solid var(--gray-100); font-size: 13px; }
    .field:nth-last-child(-n+2) { border-bottom: 0; }
    .field dt { color: var(--gray-500); }
    .field dd { color: var(--gray-800); text-align: right; font-weight: 600; }
    .field dd.locked { display: inline-flex; justify-content: flex-end; align-items: center; gap: 6px; color: var(--gray-400); font-weight: 500; }
    .field dd.locked svg { width: 13px; height: 13px; }
    .profile-sidebar { position: sticky; top: 84px; }
    .investment-card { padding: 22px; background: var(--navy-900); color: var(--white); border-radius: 8px; }
    .investment-card .eyebrow { margin-bottom: 8px; color: var(--gold-400); font-size: 11px; }
    .investment-card h2 { color: var(--white); font-size: 25px; line-height: 1.2; }
    .investment-card .equity { display: inline-block; margin-top: 8px; color: rgba(255,255,255,.68); font-size: 13px; }
    .investment-card .reason { margin-top: 17px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,.14); color: rgba(255,255,255,.78); font-size: 13px; }
    .contact-button { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 20px; padding: 12px 16px; color: var(--navy-900); background: var(--gold-400); border-radius: 6px; font-size: 14px; font-weight: 700; }
    .contact-button:hover { background: var(--gold-300); }
    .contact-button svg { width: 17px; height: 17px; }
    .sidebar-note { margin-top: 12px; color: var(--gray-500); font-size: 11px; line-height: 1.5; text-align: center; }
    .profile-image { margin-top: 16px; overflow: hidden; background: var(--white); border: 1px solid var(--gray-200); border-radius: 8px; }
    .profile-image img { width: 100%; aspect-ratio: 4 / 3; object-fit: cover; }
    .profile-image figcaption { padding: 10px 13px; color: var(--gray-500); font-size: 11px; }
    .site-footer { padding: 20px 0; color: var(--gray-700); background: var(--white); border-top: 1px solid var(--gray-200); font-size: 12px; }
    .site-footer .container { display: flex; justify-content: space-between; align-items: center; gap: 16px; }
    .site-footer a:hover { color: var(--gold-400); }
    @media (max-width: 900px) {
      .profile-layout { grid-template-columns: minmax(0, 1fr) 260px; gap: 16px; }
      .profile-nav { gap: 14px; }
    }
    @media (max-width: 700px) {
      .profile-header .container { flex-wrap: wrap; gap: 8px; padding-top: 12px; padding-bottom: 12px; }
      .profile-logo img { width: 180px; height: auto; }
      .profile-nav { order: 3; width: 100%; justify-content: space-between; gap: 10px; overflow-x: auto; }
      .profile-nav a { white-space: nowrap; }
      .profile-layout { grid-template-columns: 1fr; }
      .profile-sidebar { position: static; order: -1; }
      .profile-image { display: none; }
      .field-list { grid-template-columns: 1fr; }
      .field:nth-last-child(-n+2) { border-bottom: 1px solid var(--gray-100); }
      .field:last-child { border-bottom: 0; }
      .profile-intro { padding: 25px 22px; }
      .profile-section { padding: 21px; }
      .section-nav { top: 0; }
      .site-footer .container { flex-direction: column; text-align: center; }
    }
  </style>
@endsection

@section('content')

  <header class="profile-header">
    <div class="container">
      <a class="profile-logo" href="{{ route('home') }}" aria-label="BusinessX home"><img src="{{ asset('assets/img/businessx-logo.png?v=20261008') }}" alt="BusinessX"></a>
      <nav class="profile-nav" aria-label="Main navigation">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('business-listing') }}" aria-current="page">Bx Listing</a>
        <a href="{{ route('registration') }}">Registration</a>
        <a href="{{ route('pricing') }}">Pricing</a>
        <a href="{{ route('article') }}">Bx Insights</a>
      </nav>
      <div class="profile-actions">
        <a class="profile-login" href="{{ route('login') }}">Sign In</a>
        <a class="profile-register" href="{{ route('registration') }}">Register Free</a>
      </div>
    </div>
  </header>

  <main class="profile-main">
    <div class="container">
      <nav class="breadcrumbs" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a><span>/</span>
        <a href="{{ route('business-listing') }}">Business</a><span>/</span>
        <span>Nagoya</span><span>/</span>
        <span>Kreate Technologies pvt ltd</span><span>/</span>
        <span class="current">This is a testing headline.</span>
      </nav>

      <section class="profile-intro" aria-labelledby="profile-title">
        <div class="intro-topline">
          <span class="profile-type">Business opportunity</span>
          <span class="verified-label">Kreate Technologies pvt ltd</span>
        </div>
        <h1 id="profile-title">This is a testing headline.</h1>
        <p>I would love to schedule a brief call to share how we can support your goals.</p>
        <div class="intro-meta">
          <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Nagoya</span>
          <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Established 2009</span>
          <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>Ecommerce websites</span>
        </div>
      </section>

      <nav class="section-nav" aria-label="Profile sections">
        <a href="#overview">Overview</a>
        <a href="#details">Details</a>
        <a href="#financials">Financials</a>
        <a href="#requirement">Requirement</a>
      </nav>

      <div class="profile-layout">
        <div class="profile-content">
          <section class="profile-section" id="overview">
            <div class="section-title"><span class="section-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg></span><h2>Overview</h2></div>
            <div class="summary-copy">
              <p><strong>Summary</strong></p>
              <p>Subject: Introduction to [Your Company Name] and Our Services. Greeting: Dear [Client Name]. Opening: My name is [Your Name], and I am the [Your Title] at [Your Company Name]. We specialize in [briefly state what your company does or the product/service you offer].</p>
              <p><strong>Facilities</strong><br>I would love to schedule a brief call to share.</p>
            </div>
            <dl class="field-list" style="margin-top:14px">
              <div class="field"><dt>Director/CEO Information</dt><dd class="locked"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Available after Interaction</dd></div>
              <div class="field"><dt>Management Information</dt><dd class="locked"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Available after Interaction</dd></div>
              <div class="field"><dt>Business Documents</dt><dd class="locked"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Available after Interaction</dd></div>
            </dl>
          </section>

          <section class="profile-section" id="details">
            <div class="section-title"><span class="section-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h4"/></svg></span><h2>Business Details</h2></div>
            <dl class="field-list">
              <div class="field"><dt>Establishment Year</dt><dd>2009</dd></div>
              <div class="field"><dt>Employees</dt><dd>1</dd></div>
              <div class="field"><dt>Entity Type</dt><dd>1</dd></div>
              <div class="field"><dt>Business Sector</dt><dd>Ecommerce websites</dd></div>
              <div class="field"><dt>Business Type</dt><dd>B2C</dd></div>
              <div class="field"><dt>Website</dt><dd class="locked"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Available after Interaction</dd></div>
              <div class="field"><dt>Social Media Links</dt><dd class="locked"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>Available after Interaction</dd></div>
            </dl>
          </section>

          <section class="profile-section" id="financials">
            <div class="section-title"><span class="section-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-5 5"/></svg></span><h2>Financials</h2></div>
            <dl class="field-list">
              <div class="field"><dt>Annual Sales</dt><dd>INR 40.00</dd></div>
              <div class="field"><dt>EBITDA</dt><dd>INR 0.00</dd></div>
              <div class="field"><dt>EBITDA Margin</dt><dd>0.00%</dd></div>
              <div class="field"><dt>Inventory Value</dt><dd>INR 50.00</dd></div>
              <div class="field"><dt>Rentals</dt><dd>N/A</dd></div>
              <div class="field"><dt>Gross Income</dt><dd>INR 4,500.00</dd></div>
            </dl>
          </section>

          <section class="profile-section" id="requirement">
            <div class="section-title"><span class="section-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span><h2>Business Requirement</h2></div>
            <dl class="field-list">
              <div class="field"><dt>One-line Business Pitch</dt><dd>N/A</dd></div>
              <div class="field"><dt>Looking For</dt><dd>Investment</dd></div>
              <div class="field"><dt>Amount</dt><dd>INR 2,000,000.00 at 4 stake</dd></div>
              <div class="field"><dt>Reason</dt><dd>Sample description text.</dd></div>
            </dl>
          </section>
        </div>

        <aside class="profile-sidebar" aria-label="Investment and contact">
          <div class="investment-card">
            <span class="eyebrow">Seeking Investment</span>
            <h2>INR 2,000,000.00</h2>
            <span class="equity">Looking for investment at 4 stake</span>
            <p class="reason">Sample description text.</p>
            <a class="contact-button" href="{{ route('login') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>Contact Business</a>
          </div>
          <figure class="profile-image">
            <img src="{{ asset('assets/img/default-business-profile.png') }}" alt="Kreate Technologies business profile">
            <figcaption>Kreate Technologies pvt ltd</figcaption>
          </figure>
        </aside>
      </div>
    </div>
  </main>

  

@endsection
