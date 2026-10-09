@extends('layouts.app')

@section('title')
Create Mentor Profile | BusinessX - World Trade Council
@endsection
@section('description')
BusinessX connects businesses, startups, investors and mentors.
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/profile-registration.css') }}">
  <style>
    body { font-family: 'Inter', sans-serif; background: var(--gray-25); }

    /* Header */
    /* header styles /* header styles moved to css/common-style.css (shared) */

    /* Profile type chooser */
    .profile-type-wrap { margin-bottom: 26px; }
    .profile-type-wrap > label { display: block; font-size: 13px; font-weight: 600; color: var(--gray-700); margin-bottom: 10px; }
    .profile-types { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    .ptype {
      border: 1.5px solid var(--gray-200);
      border-radius: 10px;
      background: var(--white);
      padding: 14px 10px;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s;
    }
    .ptype .pt-ic {
      width: 36px; height: 36px;
      margin: 0 auto 8px;
      border-radius: 50%;
      background: var(--gray-100);
      color: var(--navy-700);
      display: flex; align-items: center; justify-content: center;
      transition: all 0.2s;
    }
    .ptype .pt-ic svg { width: 18px; height: 18px; }
    .ptype .pt-name { font-size: 13px; font-weight: 700; color: var(--gray-700); }
    .ptype .pt-sub { font-size: 10.5px; color: var(--gray-400); margin-top: 2px; }
    .ptype:hover { border-color: var(--gold-500); }
    .ptype.active { border-color: var(--gold-500); background: var(--gold-100); }
    .ptype.active .pt-ic { background: var(--gold-500); color: var(--navy-900); }
    @media (max-width: 720px) { .profile-types { grid-template-columns: repeat(2, 1fr); } }

    /* Page split */
    .reg-shell {
      display: grid;
      grid-template-columns: 360px 1fr;
      min-height: calc(100vh - 70px);
    }

    /* Sidebar */
    .reg-sidebar {
      background: var(--navy-800);
      color: var(--white);
      padding: 40px;
      position: relative;
      overflow: hidden;
    }
    .reg-sidebar::before {
      content: ''; position: absolute; inset: 0;
      background-image: radial-gradient(rgba(255,255,255,0.04) 1.5px, transparent 1.5px);
      background-size: 22px 22px;
      pointer-events: none;
    }
    .reg-sidebar > * { position: relative; z-index: 2; }
    .reg-sidebar h1 {
      font-size: 26px; font-weight: 700;
      color: var(--white); line-height: 1.3;
      letter-spacing: -0.01em;
      margin-bottom: 8px;
    }
    .reg-sidebar .sub { font-size: 14px; color: var(--gray-400); margin-bottom: 40px; }

    .reg-steps { display: flex; flex-direction: column; gap: 0; margin-bottom: 40px; }
    .reg-step {
      display: flex; align-items: flex-start; gap: 16px;
      padding: 0 0 28px 0; position: relative;
    }
    .reg-step:not(:last-child)::after {
      content: ''; position: absolute;
      left: 14px; top: 32px; bottom: 0;
      width: 2px; background: var(--navy-500);
    }
    .reg-step.active:not(:last-child)::after { background: var(--gold-500); }
    .reg-step .num {
      width: 30px; height: 30px;
      border-radius: 50%;
      background: var(--navy-500);
      color: var(--gray-400);
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 700;
      flex-shrink: 0; z-index: 1;
      border: 2px solid transparent;
    }
    .reg-step.active .num {
      background: var(--gold-500);
      color: var(--navy-900);
    }
    .reg-step.done .num {
      background: var(--navy-500);
      color: var(--gold-500);
      border-color: var(--gold-500);
    }
    .reg-step .title {
      font-size: 15px; font-weight: 600;
      color: var(--gray-400); margin-bottom: 2px;
    }
    .reg-step.active .title { color: var(--white); }
    .reg-step.done .title { color: rgba(255,255,255,0.8); }
    .reg-step .desc { font-size: 13px; color: var(--gray-500); line-height: 1.4; }

    .why-section {
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 12px; padding: 24px;
    }
    .why-section h3 {
      font-size: 16px; font-weight: 700;
      color: var(--gold-500); margin-bottom: 16px;
    }
    .why-list { display: flex; flex-direction: column; gap: 12px; }
    .why-item {
      display: flex; align-items: flex-start; gap: 12px;
      font-size: 13px; color: rgba(255,255,255,0.85);
      line-height: 1.4;
    }
    .why-item .ic {
      width: 28px; height: 28px; border-radius: 50%;
      background: rgba(19,141,227,0.12);
      color: var(--gold-500);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .why-item .ic svg { width: 14px; height: 14px; }

    /* Main */
    .reg-main { padding: 40px 60px; }
    .reg-main .page-head {
      display: flex; justify-content: space-between; align-items: flex-start;
      gap: 24px; flex-wrap: wrap; margin-bottom: 32px;
    }
    .reg-main .page-head h2 {
      font-size: 26px; font-weight: 700;
      color: var(--gray-900); margin-bottom: 4px;
    }
    .reg-main .page-head .sub { font-size: 15px; color: var(--gray-500); }
    .secure-badge {
      display: flex; align-items: center; gap: 10px;
      background: var(--green-100); padding: 8px 14px;
      border-radius: 999px;
    }
    .secure-badge .ic {
      width: 28px; height: 28px; border-radius: 50%;
      background: var(--green-500); color: var(--white);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .secure-badge .ic svg { width: 14px; height: 14px; }
    .secure-badge .text strong { font-size: 13px; font-weight: 700; color: var(--green-500); display: block; line-height: 1.2; }
    .secure-badge .text span { font-size: 11px; color: var(--gray-600); }

    /* Form */
    .reg-form {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: 12px;
      padding: 32px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .form-row.single { grid-template-columns: 1fr; }
    .form-field { display: flex; flex-direction: column; gap: 6px; }
    .form-field label { font-size: 13px; font-weight: 600; color: var(--gray-700); }
    .form-field label .req { color: var(--red-500); margin-left: 2px; }
    .form-field input,
    .form-field select,
    .form-field textarea {
      width: 100%; height: 44px;
      padding: 0 14px;
      border: 1px solid var(--gray-200);
      border-radius: 6px; background: var(--white);
      font-size: 14px; color: var(--gray-800);
      font-family: inherit;
      transition: all 0.2s;
    }
    .form-field input:focus,
    .form-field select:focus,
    .form-field textarea:focus {
      outline: none; border-color: var(--gold-500);
      box-shadow: 0 0 0 3px rgba(19,141,227,0.15);
    }
    .form-field input::placeholder, .form-field textarea::placeholder { color: var(--gray-400); }
    .form-field textarea { height: auto; min-height: 100px; padding: 10px 14px; resize: vertical; }
    .form-field select {
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='%236B7280'%3E%3Cpath d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      background-size: 12px;
      padding-right: 36px;
    }
    .field-icon {
      position: relative;
    }
    .field-icon .ic {
      position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
      width: 16px; height: 16px; color: var(--gray-400);
      pointer-events: none; z-index: 1;
    }
    .field-icon input, .field-icon select { padding-left: 38px; }

    .char-counter { font-size: 11px; color: var(--gray-400); text-align: right; }

    /* File upload */
    .upload-row { display: grid; grid-template-columns: 1fr 140px; gap: 16px; margin-bottom: 20px; }
    .upload-area {
      border: 2px dashed var(--gray-300);
      border-radius: 8px;
      background: var(--gray-25);
      min-height: 160px;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      gap: 8px; padding: 24px; text-align: center;
      cursor: pointer; transition: all 0.2s;
    }
    .upload-area:hover {
      border-color: var(--gold-500); background: #FFFBEB;
    }
    .upload-area .ic { width: 40px; height: 40px; color: var(--gray-400); }
    .upload-area:hover .ic { color: var(--gold-500); }
    .upload-area .title-up { font-size: 14px; font-weight: 600; color: var(--gray-700); }
    .upload-area .sub-up { font-size: 12px; color: var(--gray-400); }
    .upload-preview {
      border: 1px solid var(--gray-200); border-radius: 8px;
      min-height: 160px;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      background: var(--gray-25); gap: 8px;
    }
    .upload-preview .ic { width: 40px; height: 40px; color: var(--gray-400); }
    .upload-preview .label { font-size: 12px; color: var(--gray-400); }

    /* Actions */
    .form-actions {
      display: flex; justify-content: space-between; align-items: center;
      margin-top: 8px; padding-top: 24px;
      border-top: 1px solid var(--gray-100);
    }
    .btn-cancel {
      background: transparent; color: var(--gray-500);
      padding: 12px 20px; border-radius: 6px;
      font-size: 14px; font-weight: 500;
    }
    .btn-cancel:hover { color: var(--gray-700); background: var(--gray-100); }
    .btn-save {
      background: var(--gold-500); color: var(--white);
      padding: 12px 24px; border-radius: 6px;
      font-size: 15px; font-weight: 600;
      display: inline-flex; align-items: center; gap: 8px;
      transition: all 0.2s;
    }
    .btn-save:hover { background: var(--gold-600); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(19,141,227,0.3); }

    /* Step indicator dots */
    .step-dots {
      display: flex; gap: 8px; align-items: center;
      margin-bottom: 24px;
    }
    .step-dots .dot {
      width: 28px; height: 4px; border-radius: 2px;
      background: var(--gray-200);
      transition: all 0.3s;
    }
    .step-dots .dot.active { background: var(--gold-500); }
    .step-dots .dot.done { background: var(--green-500); }
    .step-dots .label { font-size: 12px; color: var(--gray-500); margin-left: 8px; }

    /* Trust bar */
    .trust-strip {
      display: flex; justify-content: space-between; align-items: center;
      background: var(--white); border: 1px solid var(--gray-200);
      border-radius: 12px; padding: 20px 28px;
      margin-top: 32px;
      flex-wrap: wrap; gap: 20px;
    }
    .trust-strip .left { display: flex; align-items: center; gap: 16px; }
    .trust-strip .ic-shield {
      width: 48px; height: 48px; border-radius: 50%;
      background: var(--green-100); color: var(--green-500);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .trust-strip .ic-shield svg { width: 24px; height: 24px; }
    .trust-strip .left .text strong { font-size: 16px; font-weight: 700; color: var(--gray-900); display: block; }
    .trust-strip .left .text span { font-size: 13px; color: var(--gray-500); }
    .trust-strip .right { display: flex; align-items: center; gap: 14px; }
    .avatar-stack { display: flex; }
    .avatar-stack .avatar {
      width: 36px; height: 36px; border-radius: 50%;
      border: 2px solid var(--white);
      margin-left: -10px;
    }
    .avatar-stack .avatar:first-child { margin-left: 0; }
    .avatar-stack .avatar:nth-child(1) { background: linear-gradient(135deg, #52A9EF, #1454B8); }
    .avatar-stack .avatar:nth-child(2) { background: linear-gradient(135deg, #1454B8, #5a6b5a); }
    .avatar-stack .avatar:nth-child(3) { background: linear-gradient(135deg, #b89070, #706050); }
    .avatar-stack .more {
      width: 36px; height: 36px; border-radius: 50%;
      background: var(--gold-500); color: var(--white);
      border: 2px solid var(--white);
      margin-left: -10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 700;
    }
    .rating-block { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
    .rating-block .stars { color: var(--gold-500); display: flex; gap: 1px; }
    .rating-block .stars svg { width: 14px; height: 14px; }
    .rating-block .label { font-size: 12px; color: var(--gray-500); }
    .rating-block .label strong { color: var(--gray-900); font-weight: 700; }

    /* Features section */
    .reg-features {
      background: var(--navy-900); color: var(--white);
      padding: 64px 0;
      position: relative; overflow: hidden;
    }
    .reg-features::before {
      content: ''; position: absolute; inset: 0;
      background-image: radial-gradient(rgba(255,255,255,0.04) 1.5px, transparent 1.5px);
      background-size: 22px 22px;
    }
    .reg-features .container { position: relative; z-index: 2; text-align: center; }
    .reg-features h2 { font-size: 28px; font-weight: 700; color: var(--white); margin-bottom: 8px; }
    .reg-features .sub { font-size: 16px; color: rgba(255,255,255,0.7); margin-bottom: 40px; }
    .reg-features-grid {
      display: grid; grid-template-columns: repeat(5, 1fr);
      gap: 32px; max-width: 980px; margin: 0 auto;
    }
    .reg-feature { text-align: center; }
    .reg-feature .ic {
      width: 56px; height: 56px; border-radius: 50%;
      background: rgba(19,141,227,0.12);
      color: var(--gold-500);
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 16px;
    }
    .reg-feature .ic svg { width: 24px; height: 24px; }
    .reg-feature .num { font-size: 28px; font-weight: 700; color: var(--white); line-height: 1.2; }
    .reg-feature .lbl { font-size: 13px; color: rgba(255,255,255,0.7); margin-top: 4px; }

    /* Footer features */
    .reg-footer-features {
      background: var(--white);
      padding: 48px 0;
      border-bottom: 1px solid var(--gray-200);
    }
    .reg-footer-features .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; }
    .reg-footer-feature { display: flex; align-items: flex-start; gap: 16px; }
    .reg-footer-feature .ic {
      width: 48px; height: 48px; border-radius: 50%;
      background: rgba(19,141,227,0.1); color: var(--gold-500);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .reg-footer-feature .ic svg { width: 22px; height: 22px; }
    .reg-footer-feature .t { font-size: 16px; font-weight: 700; color: var(--gray-900); margin-bottom: 4px; }
    .reg-footer-feature .d { font-size: 13px; color: var(--gray-500); line-height: 1.5; }

    @media (max-width: 1024px) {
      .reg-shell { grid-template-columns: 1fr; }
      .reg-sidebar { padding: 32px 24px; }
      .reg-main { padding: 32px 24px; }
      .reg-features-grid { grid-template-columns: repeat(3, 1fr); }
      .reg-footer-features .grid-3 { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
      .reg-nav { display: none; }
      .reg-actions .btn-back { display: none; }
      .form-row { grid-template-columns: 1fr; gap: 16px; }
      .upload-row { grid-template-columns: 1fr; }
      .reg-features-grid { grid-template-columns: 1fr; }
      .reg-header .container { gap: 16px; }
    }
  </style>
@endsection

@section('content')


  
  

  <!-- Page split -->
  <div class="reg-shell">

    <!-- Sidebar -->
    <aside class="reg-sidebar">
      <h1><?= htmlspecialchars($registrationProfile['heading'], ENT_QUOTES, 'UTF-8') ?></h1>
      <p class="sub"><?= htmlspecialchars($registrationProfile['subtitle'], ENT_QUOTES, 'UTF-8') ?></p>

      <div class="reg-steps">
        <?php foreach ($registrationProfile['steps'] as $stepIndex => $step): ?>
          <div class="reg-step<?= $stepIndex === 0 ? ' active' : '' ?>" data-step-indicator="<?= $stepIndex + 1 ?>">
            <div class="num"><?= $stepIndex + 1 ?></div>
            <div>
              <div class="title"><?= htmlspecialchars($step['title'], ENT_QUOTES, 'UTF-8') ?></div>
              <div class="desc"><?= htmlspecialchars($step['description'], ENT_QUOTES, 'UTF-8') ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="why-section">
        <h3>Why Create a Profile?</h3>
        <div class="why-list">
          <div class="why-item">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
            <span>Get discovered by thousands of businesses</span>
          </div>
          <div class="why-item">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span>
            <span>Build credibility with a verified business profile</span>
          </div>
          <div class="why-item">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span>
            <span>Generate leads and grow your network</span>
          </div>
          <div class="why-item">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></span>
            <span>Access global trade opportunities</span>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main -->
    <main class="reg-main">
      <div class="page-head">
        <div>
          <h2 data-current-step-title><?= htmlspecialchars($registrationProfile['steps'][0]['title'], ENT_QUOTES, 'UTF-8') ?></h2>
          <p class="sub" data-current-step-description><?= htmlspecialchars($registrationProfile['steps'][0]['description'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <div class="secure-badge">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></span>
          <div class="text">
            <strong>100% Secure</strong>
            <span>Your information is safe with us</span>
          </div>
        </div>
      </div>

      <div class="step-dots" data-step-progress>
        <?php foreach ($registrationProfile['steps'] as $stepIndex => $step): ?>
          <span class="dot<?= $stepIndex === 0 ? ' active current' : '' ?>" data-step-dot="<?= $stepIndex + 1 ?>"></span>
        <?php endforeach; ?>
        <span class="label" data-step-label>Step 1 of <?= count($registrationProfile['steps']) ?></span>
      </div>

      @include('components.registration-profile-wizard')

      <template class="legacy-profile-form">
      <form class="reg-form" onsubmit="event.preventDefault(); window.location.href='{{ route('dashboard.index') }}';">

        <div class="form-row single profile-type-wrap">
          <div class="form-field">
            <label>Registration Type <span class="req">*</span> <span style="font-weight:400;color:var(--gray-400);">— what profile do you want to create?</span></label>
            <div class="profile-types" id="profileTypes">
              <div class="ptype active" data-type="business" role="button" tabindex="0">
                <span class="pt-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg></span>
                <div class="pt-name">Business</div>
                <div class="pt-sub">Looking To Sell</div>
              </div>
              <div class="ptype" data-type="investor" role="button" tabindex="0">
                <span class="pt-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M8 10h8M8 14h8"/></svg></span>
                <div class="pt-name">Investor</div>
                <div class="pt-sub">Looking To Invest/Buy</div>
              </div>
              <div class="ptype" data-type="mentor" role="button" tabindex="0">
                <span class="pt-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                <div class="pt-name">Mentor</div>
                <div class="pt-sub">Looking To Guide/Coach</div>
              </div>
              <div class="ptype" data-type="startup" role="button" tabindex="0">
                <span class="pt-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg></span>
                <div class="pt-name">Startup</div>
                <div class="pt-sub">Looking For Funds</div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-field">
            <label>Business Name <span class="req">*</span></label>
            <input type="text" placeholder="Enter your business name" required>
          </div>
          <div class="form-field">
            <label>Business Type <span class="req">*</span></label>
            <select required>
              <option value="">Select business type</option>
              <option>Manufacturer</option>
              <option>Exporter</option>
              <option>Supplier</option>
              <option>Service Provider</option>
              <option>Franchise</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-field">
            <label>Company Registration Number</label>
            <input type="text" placeholder="Enter registration number">
          </div>
          <div class="form-field">
            <label>Year Established <span class="req">*</span></label>
            <select required>
              <option value="">Select year</option>
              <option>2025</option><option>2024</option><option>2023</option>
              <option>2022</option><option>2021</option><option>2020</option>
              <option>2015</option><option>2010</option><option>2005</option>
              <option>2000</option><option>Before 2000</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-field" style="grid-column: 1 / -1;">
            <label>Business Logo <span class="req">*</span></label>
            <div class="upload-row">
              <div class="upload-area">
                <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <div class="title-up">Upload your business logo</div>
                <div class="sub-up">JPG, PNG or SVG. Max size 2MB.</div>
              </div>
              <div class="upload-preview">
                <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
                <div class="label">Preview</div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-row single">
          <div class="form-field">
            <label>Business Tagline</label>
            <input type="text" placeholder="A short tagline that describes your business" maxlength="120">
            <div class="char-counter">0/120</div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-field">
            <label>Business Category <span class="req">*</span></label>
            <select required>
              <option value="">Select primary category</option>
              <option>Manufacturing</option>
              <option>Technology</option>
              <option>Agriculture</option>
              <option>Healthcare</option>
              <option>Finance</option>
              <option>Logistics</option>
              <option>Retail</option>
              <option>Construction</option>
            </select>
          </div>
          <div class="form-field">
            <label>Sub Category <span class="req">*</span></label>
            <select required>
              <option value="">Select sub category</option>
              <option>Industrial Equipment</option>
              <option>Software Development</option>
              <option>Medical Equipment</option>
              <option>Financial Services</option>
              <option>Supply Chain</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-field">
            <label>Country <span class="req">*</span></label>
            <div class="field-icon">
              <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
              <select required>
                <option value="">Select country</option>
                <option>United Kingdom</option>
                <option>United States</option>
                <option>Germany</option>
                <option>France</option>
                <option>Spain</option>
                <option>Italy</option>
                <option>Netherlands</option>
                <option>India</option>
                <option>China</option>
                <option>Japan</option>
                <option>Australia</option>
              </select>
            </div>
          </div>
          <div class="form-field">
            <label>State / Province <span class="req">*</span></label>
            <select required>
              <option value="">Select state / province</option>
              <option>London</option>
              <option>Manchester</option>
              <option>Birmingham</option>
              <option>Leeds</option>
              <option>Glasgow</option>
              <option>Edinburgh</option>
              <option>Cardiff</option>
              <option>Belfast</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-field">
            <label>City <span class="req">*</span></label>
            <input type="text" placeholder="Enter city" required>
          </div>
          <div class="form-field">
            <label>Zip / Postal Code <span class="req">*</span></label>
            <input type="text" placeholder="Enter zip / postal code" required>
          </div>
        </div>

        <div class="form-row single">
          <div class="form-field">
            <label>Business Address <span class="req">*</span></label>
            <textarea placeholder="Enter complete business address" required></textarea>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn-cancel">Cancel</button>
          <button type="submit" class="btn-save">
            Save & Continue
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>
        </div>
      </form>
      </template>

      <!-- Trust bar -->
      <div class="trust-strip">
        <div class="left">
          <span class="ic-shield"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></span>
          <div class="text">
            <strong>Trusted by 25,000+ Businesses Worldwide</strong>
            <span>Join verified members from 120+ countries and grow your global presence.</span>
          </div>
        </div>
        <div class="right">
          <div class="avatar-stack">
            <span class="avatar"></span>
            <span class="avatar"></span>
            <span class="avatar"></span>
            <span class="more">+25K</span>
          </div>
          <div class="rating-block">
            <span class="stars">
              <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
              <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
              <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
              <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
              <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
            </span>
            <span class="label"><strong>4.8/5</strong> Member Rating</span>
          </div>
        </div>
      </div>

    </main>
  </div>

  <!-- Features section -->
  <section class="reg-features">
    <div class="container">
      <h2>Your Business, Global Opportunities</h2>
      <p class="sub">Join a trusted network and take your business to the next level.</p>
      <div class="reg-features-grid">
        <div class="reg-feature">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg></span>
          <div class="num">150,000+</div>
          <div class="lbl">Businesses</div>
        </div>
        <div class="reg-feature">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></span>
          <div class="num">120+</div>
          <div class="lbl">Countries</div>
        </div>
        <div class="reg-feature">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
          <div class="num">25,000+</div>
          <div class="lbl">Verified Members</div>
        </div>
        <div class="reg-feature">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span>
          <div class="num">10,000+</div>
          <div class="lbl">Business Connections</div>
        </div>
        <div class="reg-feature">
          <span class="ic"><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg></span>
          <div class="num">4.8/5</div>
          <div class="lbl">Member Satisfaction</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer features -->
  <section class="reg-footer-features">
    <div class="container">
      <div class="grid-3">
        <div class="reg-footer-feature">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span>
          <div>
            <div class="t">Secure & Reliable</div>
            <div class="d">We use industry-standard security to protect your information.</div>
          </div>
        </div>
        <div class="reg-footer-feature">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
          <div>
            <div class="t">Privacy Protected</div>
            <div class="d">Your data is safe with us. We never share your information.</div>
          </div>
        </div>
        <div class="reg-footer-feature">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg></span>
          <div>
            <div class="t">24/7 Support</div>
            <div class="d">Our support team is always here to help you.</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  

  <script>
    // Char counter
    const taglineInput = document.querySelector('input[maxlength="120"]');
    if (taglineInput) {
      const counter = taglineInput.parentElement.querySelector('.char-counter');
      taglineInput.addEventListener('input', () => {
        counter.textContent = `${taglineInput.value.length}/120`;
      });
    }
  </script>
<script>
    // === Profile type chooser + ?type= preselect ===
    (function() {
      const types = document.querySelectorAll('.ptype');
      function setType(t, updateUrl) {
        types.forEach(el => el.classList.toggle('active', el.dataset.type === t));
        if (updateUrl) {
          const url = new URL(window.location);
          url.searchParams.set('type', t);
          window.history.replaceState({}, '', url);
        }
      }
      types.forEach(el => {
        el.addEventListener('click', () => setType(el.dataset.type, true));
        el.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); setType(el.dataset.type, true); } });
      });
      const urlType = new URLSearchParams(window.location.search).get('type');
      if (urlType && ['business','investor','startup','mentor'].includes(urlType)) {
        setType(urlType, false);
      }
    })();
  </script>
  <script src="{{ asset('js/profile-registration.js') }}"></script>

@endsection
