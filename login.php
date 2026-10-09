<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In | BusinessX - World Trade Council</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/common-style.css?v=20261002">
  <style>
    body { background: #0A1628; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding: 24px 0 0; font-family: 'Inter', sans-serif; }

    .login-shell {
      width: min(calc(100% - 48px), 1280px);
      max-width: 1280px;
      height: min(820px, 92vh);
      display: grid;
      grid-template-columns: 55% 45%;
      background: var(--white);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 25px 80px -12px rgba(0,0,0,0.4), 0 10px 30px -5px rgba(0,0,0,0.3);
    }

    .bx-footer { width: 100%; margin-top: 24px; }

    /* === Left Panel === */
    .login-left {
      background: linear-gradient(135deg, #0D1F3C 0%, #0A1628 50%, #061020 100%);
      color: var(--white);
      position: relative;
      overflow: hidden;
      padding: 48px 56px;
      display: flex;
      flex-direction: column;
    }
    .login-left .world-map-bg {
      position: absolute; inset: 0;
      background-image:
        radial-gradient(rgba(255,255,255,0.18) 1.5px, transparent 1.5px);
      background-size: 18px 18px;
      opacity: 0.4;
      mask-image: radial-gradient(ellipse 60% 50% at 50% 40%, black 0%, transparent 80%);
      -webkit-mask-image: radial-gradient(ellipse 60% 50% at 50% 40%, black 0%, transparent 80%);
    }
    .login-left .glow {
      position: absolute;
      width: 320px; height: 320px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(229,166,35,0.18), transparent 70%);
      filter: blur(20px);
      top: -100px; right: -100px;
    }
    .login-left .glow-2 {
      top: auto; bottom: -120px; right: auto; left: -100px;
      background: radial-gradient(circle, rgba(59,130,246,0.15), transparent 70%);
    }

    .login-left .logo-row {
      display: flex; align-items: center; gap: 12px;
      position: relative; z-index: 2;
    }
    .login-left .globe {
      width: 44px; height: 44px;
      color: var(--white);
      display: inline-flex; align-items: center; justify-content: center;
    }
    .login-left .globe svg { width: 38px; height: 38px; }
    .login-left .brand-text { line-height: 1.1; }
    .login-left .brand-name { font-size: 22px; font-weight: 700; letter-spacing: -0.01em; }
    .login-left .brand-name .x { color: var(--gold-500); font-size: 26px; }
    .login-left .brand-tag { font-size: 9px; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: rgba(255,255,255,0.5); margin-top: 3px; }
    .login-left .logo-pill { display: inline-block; }
    .login-left .logo-pill img { width: 211px; height: 45px; display: block; }

    .login-left .hero-content { margin-top: 64px; position: relative; z-index: 2; }
    .login-left h1 {
      font-size: clamp(28px, 3.4vw, 44px);
      font-weight: 700;
      line-height: 1.1;
      letter-spacing: -0.02em;
      margin-bottom: 16px;
    }
    .login-left h1 .accent { color: var(--gold-500); }
    .login-left .lead { font-size: 16px; line-height: 1.6; color: rgba(255,255,255,0.7); max-width: 420px; margin-bottom: 48px; }

    .login-left .stats {
      display: flex; gap: 40px;
      position: relative; z-index: 2;
      margin-bottom: 40px;
    }
    .login-left .stat { display: flex; flex-direction: column; gap: 8px; }
    .login-left .stat .ic { color: var(--gold-500); width: 28px; height: 28px; }
    .login-left .stat .num { font-size: 24px; font-weight: 700; color: var(--white); }
    .login-left .stat .lbl { font-size: 14px; color: rgba(255,255,255,0.7); }

    .login-left .image-block {
      position: relative;
      z-index: 1;
      margin-top: auto;
      width: calc(100% + 112px);
      margin-left: -56px;
      margin-right: -56px;
      margin-bottom: -48px;
      aspect-ratio: 16/9;
      background: linear-gradient(180deg, transparent 0%, rgba(10,22,40,0.85) 100%),
                  linear-gradient(135deg, #4a5568 0%, #2d3a4f 100%);
      display: flex; align-items: end; justify-content: center;
      border-top: 1px solid rgba(255,255,255,0.08);
    }
    .login-left .image-block::before {
      content: 'BUSINESS TEAM PHOTO';
      position: absolute; bottom: 60px; left: 50%; transform: translateX(-50%);
      color: rgba(255,255,255,0.3); font-size: 11px; font-weight: 600;
      letter-spacing: 0.1em;
    }

    .login-left .trust-badge {
      position: absolute;
      bottom: 32px; left: 32px;
      background: rgba(13,31,60,0.95);
      backdrop-filter: blur(10px);
      padding: 16px 20px;
      border-radius: 12px;
      border: 1px solid rgba(255,255,255,0.1);
      display: flex; align-items: center; gap: 14px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.3);
      z-index: 3;
      max-width: 280px;
    }
    .login-left .trust-badge .ic {
      width: 40px; height: 40px; border-radius: 50%;
      background: rgba(229,166,35,0.15);
      color: var(--gold-500);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .login-left .trust-badge .text { font-size: 13px; line-height: 1.4; }
    .login-left .trust-badge .text strong { color: var(--white); display: block; font-size: 14px; }
    .login-left .trust-badge .text span { color: rgba(255,255,255,0.7); }

    /* === Right Panel === */
    .login-right {
      background: var(--white);
      display: flex; flex-direction: column;
      position: relative;
    }
    .login-right .top-bar {
      display: flex; justify-content: space-between; align-items: center;
      padding: 24px 40px;
      border-bottom: 1px solid var(--gray-200);
    }
    .login-right .help-link {
      display: inline-flex; align-items: center; gap: 8px;
      color: var(--gray-700);
      font-size: 14px; font-weight: 500;
    }
    .login-right .help-link svg { width: 16px; height: 16px; color: var(--gray-500); }
    .login-right .help-link:hover { color: var(--gold-600); }
    .login-right .close-btn {
      width: 32px; height: 32px;
      border-radius: 50%;
      display: inline-flex; align-items: center; justify-content: center;
      color: var(--gray-500);
    }
    .login-right .close-btn:hover { background: var(--gray-100); color: var(--gray-800); }

    .login-right .tabs {
      display: flex;
      padding: 0 40px;
      border-bottom: 1px solid var(--gray-200);
    }
    .login-right .tab {
      padding: 16px 24px 16px 0;
      margin-right: 32px;
      font-size: 16px; font-weight: 500;
      color: var(--gray-500);
      border: 0; background: transparent; font-family: inherit; text-align: left;
      position: relative;
      cursor: pointer;
      transition: color 0.2s;
    }
    .login-right .tab:focus-visible { outline: 2px solid var(--gold-500); outline-offset: 3px; }
    .login-right .tab.active {
      color: var(--navy-700);
      font-weight: 600;
    }
    .login-right .tab.active::after {
      content: '';
      position: absolute;
      bottom: -1px; left: 0;
      width: 100%; height: 3px;
      background: var(--gold-500);
      border-radius: 3px 3px 0 0;
    }
    .login-right .auth-panel[hidden] { display: none; }
    .login-right .register-intro { margin-bottom: 20px; }
    .login-right .register-type-list { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .login-right .register-type-list a {
      min-height: 64px; padding: 12px 14px; border: 1px solid var(--gray-200); border-radius: 8px;
      display: flex; flex-direction: column; justify-content: center; color: var(--navy-700);
      font-size: 14px; font-weight: 700; transition: border-color .2s, background .2s;
    }
    .login-right .register-type-list a:hover { border-color: var(--gold-500); background: var(--gold-100); }
    .login-right .register-type-list span { margin-top: 3px; color: var(--gray-500); font-size: 11px; font-weight: 400; }
    .login-right .register-signin { margin-top: 24px; color: var(--gray-600); text-align: center; font-size: 13px; }
    .login-right .register-signin button { color: var(--gold-600); font-weight: 700; }
    .login-right .register-signin button:hover { text-decoration: underline; }

    .login-right .form-wrap {
      padding: 32px 40px;
      flex: 1;
      display: flex; flex-direction: column;
    }
    .login-right h2 {
      font-size: 28px; font-weight: 700;
      color: var(--navy-700);
      letter-spacing: -0.01em;
      margin-bottom: 8px;
    }
    .login-right .sub {
      font-size: 14px; line-height: 1.5;
      color: var(--gray-600);
      margin-bottom: 28px;
    }

    .login-right .field { position: relative; margin-bottom: 16px; }
    .login-right .field .input-icon {
      position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
      width: 20px; height: 20px; color: var(--gray-400); pointer-events: none;
    }
    .login-right .field .pw-toggle {
      position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
      width: 20px; height: 20px; color: var(--gray-400); cursor: pointer;
    }
    .login-right .field .pw-toggle:hover { color: var(--gray-700); }
    .login-right .field input {
      width: 100%; height: 52px;
      padding: 0 16px 0 48px;
      border: 1.5px solid var(--gray-200);
      border-radius: 8px;
      font-size: 14px;
      background: var(--white);
      transition: all 0.2s;
    }
    .login-right .field.has-toggle input { padding-right: 48px; }
    .login-right .field input:focus {
      outline: none;
      border-color: var(--gold-500);
      box-shadow: 0 0 0 3px rgba(229,166,35,0.15);
    }
    .login-right .field input::placeholder { color: var(--gray-400); }

    .login-right .row-between {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 20px;
    }
    .login-right .remember {
      display: inline-flex; align-items: center; gap: 8px;
      font-size: 14px; color: var(--gray-600);
    }
    .login-right .remember input { width: 16px; height: 16px; accent-color: var(--gold-500); }
    .login-right .forgot {
      font-size: 14px; font-weight: 500;
      color: var(--gold-500);
    }
    .login-right .forgot:hover { color: var(--gold-600); }

    .login-right .btn-primary-gold {
      width: 100%;
      height: 52px;
      background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
      color: var(--white);
      font-size: 16px; font-weight: 600;
      border-radius: 8px;
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      transition: all 0.2s;
    }
    .login-right .btn-primary-gold:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(229,166,35,0.3);
    }

    .login-right .divider {
      display: flex; align-items: center; gap: 12px;
      margin: 24px 0;
      color: var(--gray-400);
      font-size: 11px; font-weight: 600;
      letter-spacing: 0.1em; text-transform: uppercase;
    }
    .login-right .divider::before, .login-right .divider::after {
      content: ''; flex: 1; height: 1px; background: var(--gray-200);
    }

    .login-right .social-row {
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }
    .login-right .social-btn {
      display: flex; align-items: center; justify-content: center; gap: 8px;
      padding: 12px;
      border: 1.5px solid var(--gray-200);
      border-radius: 8px;
      font-size: 14px; font-weight: 500;
      color: var(--gray-700);
      background: var(--white);
      transition: all 0.2s;
    }
    .login-right .social-btn:hover {
      border-color: var(--gray-300);
      background: var(--gray-25);
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .login-right .social-btn svg { width: 18px; height: 18px; }

    .login-right .register-cta {
      text-align: center;
      margin-top: 28px;
      font-size: 14px; color: var(--gray-600);
    }
    .login-right .register-cta a {
      color: var(--gold-500); font-weight: 600;
      display: inline-flex; align-items: center; gap: 4px;
    }
    .login-right .register-cta a:hover { color: var(--gold-600); }

    .login-right .feature-footer {
      display: grid; grid-template-columns: repeat(4, 1fr);
      border-top: 1px solid var(--gray-200);
      padding: 24px 40px;
      gap: 12px;
      background: var(--gray-25);
    }
    .login-right .feature-item {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 0 12px;
      border-right: 1px solid var(--gray-200);
    }
    .login-right .feature-item:last-child { border-right: none; padding-right: 0; }
    .login-right .feature-item:first-child { padding-left: 0; }
    .login-right .feature-icon-circle {
      width: 36px; height: 36px;
      border-radius: 50%;
      background: rgba(229,166,35,0.1);
      color: var(--gold-500);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .login-right .feature-icon-circle svg { width: 18px; height: 18px; }
    .login-right .feature-text .t { font-size: 12px; font-weight: 700; color: var(--navy-700); line-height: 1.2; }
    .login-right .feature-text .d { font-size: 11px; color: var(--gray-500); line-height: 1.3; margin-top: 2px; }

    /* Responsive */
    @media (max-width: 1024px) {
      .login-shell { grid-template-columns: 1fr; height: auto; max-height: none; }
      .login-left { padding: 40px 32px; min-height: 280px; }
      .login-left .hero-content { margin-top: 32px; }
      .login-left .image-block, .login-left .trust-badge { display: none; }
      .login-left .stats { gap: 24px; flex-wrap: wrap; }
      .login-right .feature-footer { grid-template-columns: 1fr 1fr; gap: 16px; }
      .login-right .feature-item { border-right: none; padding: 0; }
    }
    @media (max-width: 640px) {
      .login-right .form-wrap, .login-right .tabs, .login-right .top-bar, .login-right .feature-footer { padding-left: 20px; padding-right: 20px; }
      .login-right .social-row { grid-template-columns: 1fr; }
      .login-right .feature-footer { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <div class="login-shell">

    <!-- Left panel -->
    <aside class="login-left">
      <div class="world-map-bg"></div>
      <div class="glow"></div>
      <div class="glow glow-2"></div>

      <div class="logo-row">
        <span class="logo-pill"><img src="assets/img/businessx-logo.png" alt="BusinessX"></span>
      </div>

      <div class="hero-content">
        <h1>Connecting Businesses <span class="accent">Worldwide</span>, Empowering Trade Globally</h1>
        <p class="lead">Join the world's leading B2B network. Sign in to your BusinessX account to manage your global trade partnerships, connect with verified suppliers, and grow your business across borders.</p>
      </div>

      <div class="stats">
        <div class="stat">
          <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/>
          </svg>
          <div class="num">50K+</div>
          <div class="lbl">Businesses</div>
        </div>
        <div class="stat">
          <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="2" y1="12" x2="22" y2="12"/>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
          </svg>
          <div class="num">120+</div>
          <div class="lbl">Countries</div>
        </div>
        <div class="stat">
          <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            <polyline points="9 12 11 14 15 10"/>
          </svg>
          <div class="num">100%</div>
          <div class="lbl">Verified</div>
        </div>
      </div>

      <div class="image-block"></div>

      <div class="trust-badge">
        <span class="ic">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
        </span>
        <div class="text">
          <strong>Trusted by 50,000+ Businesses</strong>
          <span>Verified global trade network you can rely on.</span>
        </div>
      </div>
    </aside>

    <!-- Right panel -->
    <section class="login-right">
      <div class="top-bar">
        <a href="#" class="help-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          Need Help?
        </a>
        <a href="index.php" class="close-btn" aria-label="Close">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </a>
      </div>

      <div class="tabs" role="tablist" aria-label="Account access">
        <button class="tab active" type="button" id="login-tab" role="tab" aria-selected="true" aria-controls="login-panel" tabindex="0">Login</button>
        <button class="tab" type="button" id="register-tab" role="tab" aria-selected="false" aria-controls="register-panel" tabindex="-1">Register</button>
      </div>

      <div class="form-wrap">
        <section class="auth-panel" id="login-panel" role="tabpanel" aria-labelledby="login-tab">
        <h2>Welcome Back!</h2>
        <p class="sub">Sign in to access your dashboard and manage your business profile.</p>

        <form onsubmit="event.preventDefault(); window.location.href='dashboard.php';">
          <div class="field">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <input type="email" placeholder="Enter your business email" required>
          </div>
          <div class="field has-toggle">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <input id="pwInput" type="password" placeholder="Enter your password" required>
            <span class="pw-toggle" data-toggle-password="#pwInput" aria-label="Show password">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </span>
          </div>

          <div class="row-between">
            <label class="remember"><input type="checkbox"> Remember me</label>
            <a href="forgot-password.php" class="forgot">Forgot Password?</a>
          </div>

          <button type="submit" class="btn-primary-gold">
            Sign In to Your Account
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>

          <div class="divider">Or Continue With</div>

          <div class="social-row">
            <a href="#" class="social-btn">
              <svg viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84C6.71 7.31 9.14 5.38 12 5.38z"/></svg>
              Google
            </a>
            <a href="#" class="social-btn">
              <svg viewBox="0 0 24 24" fill="#0A66C2"><path d="M20.5 2h-17A1.5 1.5 0 002 3.5v17A1.5 1.5 0 003.5 22h17a1.5 1.5 0 001.5-1.5v-17A1.5 1.5 0 0020.5 2zM8 19H5v8h3V8zM6.5 6.7a1.8 1.8 0 110-3.6 1.8 1.8 0 010 3.6zM19 19h-3v-4.7c0-1.1 0-2.5-1.5-2.5s-1.7 1.2-1.7 2.4V19h-3V8h2.9v1.2c.4-.8 1.4-1.5 2.8-1.5 3 0 3.5 2 3.5 4.5V19z"/></svg>
              LinkedIn
            </a>
            <a href="#" class="social-btn">
              <svg viewBox="0 0 24 24"><path fill="#F25022" d="M0 0h11v11H0z"/><path fill="#7FBA00" d="M13 0h11v11H13z"/><path fill="#00A4EF" d="M0 13h11v11H0z"/><path fill="#FFB900" d="M13 13h11v11H13z"/></svg>
              Microsoft
            </a>
          </div>

          <p class="register-cta">New to BusinessX? <a href="registration.php">Create a free account <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a></p>
        </form>
        </section>

        <section class="auth-panel" id="register-panel" role="tabpanel" aria-labelledby="register-tab" hidden>
          <h2>Join BusinessX</h2>
          <p class="sub register-intro">Choose the profile you want to create and continue with registration.</p>
          <div class="register-type-list">
            <a href="registration.php?type=business">Business<span>Sell or grow your business</span></a>
            <a href="registration.php?type=investor">Investor<span>Find businesses to invest in</span></a>
            <a href="registration.php?type=startup">Startup<span>Raise funds and scale</span></a>
            <a href="registration.php?type=mentor">Mentor<span>Share your expertise</span></a>
          </div>
          <p class="register-signin">Already have an account? <button type="button" data-auth-tab="login">Sign in</button></p>
        </section>
      </div>

      <div class="feature-footer">
        <div class="feature-item">
          <span class="feature-icon-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
          </span>
          <div class="feature-text">
            <div class="t">Verified & Trusted</div>
            <div class="d">All members are verified</div>
          </div>
        </div>
        <div class="feature-item">
          <span class="feature-icon-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          </span>
          <div class="feature-text">
            <div class="t">Global Reach</div>
            <div class="d">Connect in 120+ countries</div>
          </div>
        </div>
        <div class="feature-item">
          <span class="feature-icon-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
          </span>
          <div class="feature-text">
            <div class="t">Business Growth</div>
            <div class="d">Discover new opportunities</div>
          </div>
        </div>
        <div class="feature-item">
          <span class="feature-icon-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </span>
          <div class="feature-text">
            <div class="t">Secure & Private</div>
            <div class="d">Top-level data protection</div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include __DIR__ . "/includes/footer.php"; ?>

  <script>
    const authTabs = [...document.querySelectorAll('.login-right [role="tab"]')];
    const authPanels = [...document.querySelectorAll('.login-right [role="tabpanel"]')];

    function activateAuthTab(tab) {
      authTabs.forEach(item => {
        const selected = item === tab;
        item.classList.toggle('active', selected);
        item.setAttribute('aria-selected', String(selected));
        item.tabIndex = selected ? 0 : -1;
      });
      authPanels.forEach(panel => {
        panel.hidden = panel.id !== tab.getAttribute('aria-controls');
      });
    }

    authTabs.forEach((tab, index) => {
      tab.addEventListener('click', () => activateAuthTab(tab));
      tab.addEventListener('keydown', event => {
        const next = event.key === 'ArrowRight' ? (index + 1) % authTabs.length
          : event.key === 'ArrowLeft' ? (index - 1 + authTabs.length) % authTabs.length
          : event.key === 'Home' ? 0
          : event.key === 'End' ? authTabs.length - 1
          : -1;
        if (next >= 0) {
          event.preventDefault();
          authTabs[next].focus();
          activateAuthTab(authTabs[next]);
        }
      });
    });

    document.querySelector('[data-auth-tab="login"]').addEventListener('click', () => activateAuthTab(authTabs[0]));
  </script>
</body>
</html>

