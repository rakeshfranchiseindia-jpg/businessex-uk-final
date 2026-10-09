@extends('layouts.app')

@section('title')
Membership Plans | BusinessX - World Trade Council
@endsection
@section('description')
BusinessX connects businesses, startups, investors and mentors.
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/pricing.css?v=20261009') }}">
@endsection

@section('content')


  
  
  <section class="pricing-hero" aria-label="Membership plans">
    <div class="container">
      <div class="hero-row">
        <div class="hero-copy">
          <h2>Choose The Right Membership<span class="gold">For Your Business</span></h2>
          <p class="lead">Join 25,000+ verified businesses across 120+ countries and unlock global opportunities.</p>
          <div class="pricing-toggle-wrap" aria-label="Billing period toggle">
            <div class="pricing-toggle">
              <span class="toggle-label active">Monthly</span>
              <label class="switch" aria-label="Toggle annual billing">
                <input type="checkbox" data-pricing-toggle>
                <span class="slider"></span>
              </label>
              <span class="toggle-label">Annual</span>
            </div>
          </div>
        </div>
        <div class="hero-visual">
          <img src="{{ asset('assets/img/hero-bg.jpg') }}" alt="Business owners collaborating in a modern office">
        </div>
      </div>

    </div>
  </section>

  <section class="plan-selection" aria-labelledby="plan-selection-title">
    <div class="container">
      <header class="plan-selection-heading">
        <h1 id="plan-selection-title">Choose a Plan</h1>
        <p>Create your profile &amp; find the correct solution for your business</p>
      </header>

      <fieldset class="profile-type-options">
        <legend>Profile Type:</legend>
        <label><input type="radio" name="pricing-profile-type" value="business" checked><span>Business</span></label>
        <label><input type="radio" name="pricing-profile-type" value="startup"><span>Startup</span></label>
        <label><input type="radio" name="pricing-profile-type" value="investor"><span>Investor</span></label>
        <label><input type="radio" name="pricing-profile-type" value="mentor"><span>Mentor</span></label>
      </fieldset>

      <div class="plan-selection-fields">
        <div class="plan-selection-field">
          <label for="pricing-name">Your Name <span>*</span></label>
          <input id="pricing-name" type="text" placeholder="Enter name" autocomplete="name" required>
        </div>
        <div class="plan-selection-field">
          <label for="pricing-mobile">Mobile No. <span>*</span></label>
          <div class="phone-input-group">
            @include('components.phone-country-code')
            <input id="pricing-mobile" type="tel" placeholder="Enter Mobile" autocomplete="tel" required>
          </div>
        </div>
        <div class="plan-selection-field">
          <label for="pricing-email">Email id <span>*</span></label>
          <input id="pricing-email" type="email" placeholder="Enter Email id" autocomplete="email" required>
        </div>
        <div class="plan-selection-field">
          <label for="pricing-company">Company Name <span>*</span></label>
          <input id="pricing-company" type="text" placeholder="Enter Company Name" autocomplete="organization" required>
        </div>
        <div class="plan-selection-field">
          <label for="pricing-payment-mode">Payment Mode <span>*</span></label>
          <select id="pricing-payment-mode" required>
            <option value="">Select Payment Mode</option>
            <option value="online">Online</option>
            <option value="offline">Offline</option>
            <option value="upi">UPI</option>
          </select>
        </div>
      </div>
    </div>
  </section>

  <section class="pricing-plans" aria-label="Membership plans">
    <div class="container">
      <div class="pricing-cards">
        <!-- Trial -->
        <div class="plan-card">
          <div class="plan-name">TRIAL</div>
          <div class="plan-subtitle">Get started with basic features</div>
          <div class="plan-price">
            <span class="amount">£0</span>
            <span class="period">/ Forever</span>
          </div>
          <div class="plan-note">No credit card required</div>
          <ul class="plan-features">
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Profile Activation</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span> 5 Business Proposals</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>  Receive Unlimited Investment Proposals</li>
          </ul>
          <a href="{{ route('registration') }}" class="plan-btn plan-btn-outline">Select Plan</a>
        </div>

        <!-- Premium -->
        <div class="plan-card featured">
          <div class="plan-badge">Popular</div>
          <div class="plan-name">PREMIUM</div>
          <div class="plan-subtitle">Grow your business with premium tools</div>
          <div class="plan-price">
            <span class="amount" data-price-monthly="£29" data-price-annual="£299">£29</span>
            <span class="period" data-billing-period="/ Month">/ Month</span>
          </div>
          <div class="plan-note">Billed annually</div>
          <ul class="plan-features">
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Speedy Profile Activation</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Send 50 Business Proposals</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Receive Unlimited Investment Proposals</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Continuous Email Support</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>10 Website Visitor Queries</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Profile Views</li>
          </ul>
          <a href="{{ route('registration') }}" class="plan-btn plan-btn-gold">Get Started</a>
        </div>

        <!-- Gold -->
        <div class="plan-card">
          <div class="plan-name">GOLD</div>
          <div class="plan-subtitle">Greater visibility and support for your business.</div>
          <div class="plan-price">
            <span class="amount" data-price-monthly="£89" data-price-annual="£999">£89</span>
            <span class="period" data-billing-period="/ Month">/ Month</span>
          </div>
          <div class="plan-note">Billed annually</div>
          <ul class="plan-features">
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Speedy Profile Activation</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>100 Business Proposals</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Receive Unlimited Investment Proposals</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Continuous Email Support</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>20 Website Visitor Queries</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Profile Views</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Profile Promotion</li>
          </ul>
          <a href="{{ route('registration') }}" class="plan-btn plan-btn-gold-outline">Choose Gold</a>
        </div>

        <!-- Platinum -->
        <div class="plan-card featured">
          <div class="plan-badge">All Features</div>
          <div class="plan-name">PLATINUM</div>
          <div class="plan-subtitle">Every membership feature, included.</div>
          <div class="plan-price">
            <span class="amount">Custom</span>
          </div>
          <div class="plan-note">Contact us for pricing</div>
          <ul class="plan-features">
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Everything in Gold Plan</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Key Account Manager</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Top Bussinesses/Startups</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Acceleration Marketing</li>
            <li><span class="ic ic-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Businesses Valution</li>
          </ul>
          <a href="{{ route('registration') }}" class="plan-btn plan-btn-gold">Choose Platinum</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Benefits -->
  <section class="benefits">
    <div class="container">
      <h2 class="h2">Why Upgrade Your Membership?</h2>
      <div class="benefits-grid">
        <div class="benefit-item">
          <div class="ic-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></div>
          <h3>Verified & Trusted</h3>
          <p>Build credibility with a verified member badge.</p>
        </div>
        <div class="benefit-item">
          <div class="ic-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
          <h3>Increase Visibility</h3>
          <p>Get discovered by buyers, partners and investors worldwide.</p>
        </div>
        <div class="benefit-item">
          <div class="ic-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
          <h3>Global Opportunities</h3>
          <p>Access international trade leads and business opportunities.</p>
        </div>
        <div class="benefit-item">
          <div class="ic-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
          <h3>Business Networking</h3>
          <p>Connect with decision makers and industry leaders.</p>
        </div>
        <div class="benefit-item">
          <div class="ic-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg></div>
          <h3>Marketing Support</h3>
          <p>Promote your business through our global channels.</p>
        </div>
        <div class="benefit-item">
          <div class="ic-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg></div>
          <h3>Priority Support</h3>
          <p>Get faster support from our dedicated success team.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Comparison + Testimonial -->
  <section class="compare">
    <div class="container">
      <h2 class="h2 text-center mb-8">Compare All Plans</h2>
      <div class="compare-grid">
        <div class="compare-table-wrap">
          <table class="compare-table">
            <thead>
              <tr>
                <th>Features</th>
                <th>
                  <div class="plan-name" style="color: var(--gray-700);">TRIAL</div>
                  <div class="plan-price-mini">£0 / Forever</div>
                </th>
                <th>
                  <div class="plan-name" style="color: var(--gold-600);">PREMIUM</div>
                  <div class="plan-price-mini">£299 / Year</div>
                </th>
                <th>
                  <div class="plan-name" style="color: var(--navy-700);">GOLD</div>
                  <div class="plan-price-mini">£999 / Year</div>
                </th>
                <th>
                  <div class="plan-name" style="color: var(--gold-600);">PLATINUM</div>
                  <div class="plan-price-mini">Custom pricing</div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Business Profile</td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
              </tr>
              <tr>
                <td>Basic Directory Listing</td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
              </tr>
              <tr>
                <td>Verified Member Badge</td>
                <td><span class="ic ic-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
              </tr>
              <tr>
                <td>Receive Leads & Inquiries</td>
                <td><span class="ic ic-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
              </tr>
              <tr>
                <td>Featured Listing</td>
                <td><span class="ic ic-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
              </tr>
              <tr>
                <td>Homepage Exposure</td>
                <td><span class="ic ic-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></td>
                <td><span class="ic ic-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
              </tr>
              <tr>
                <td>Priority Support</td>
                <td><span class="ic ic-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></td>
                <td><span class="ic ic-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
              </tr>
              <tr>
                <td>Dedicated Account Manager</td>
                <td><span class="ic ic-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></td>
                <td><span class="ic ic-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
              </tr>
              <tr>
                <td>Custom Business Page</td>
                <td><span class="ic ic-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></td>
                <td><span class="ic ic-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></td>
                <td><span class="ic ic-yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="testimonial-card">
          <div class="quote-mark">"</div>
          <p class="quote">BusinessX has helped us connect with global partners and grow our business in new markets.</p>
          <div class="stars">
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
          </div>
          <div class="author">
            <div class="avatar"></div>
            <div>
              <div class="name">John Smith</div>
              <div class="title">CEO, ABC Manufacturing Ltd.</div>
              <div class="location">United Kingdom</div>
            </div>
          </div>
          <div class="testimonial-dots">
            <span class="active"></span><span></span><span></span><span></span><span></span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container">
      <h2>Ready To Grow Your Business Globally?</h2>
      <p>Join thousands of businesses already expanding their global reach with BusinessX.</p>
      <a href="{{ route('registration') }}" class="cta-btn" style="color:#ffffff">
        Become A Member Today
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>
  </section>

  <!-- Trust bar -->
  <section class="trust-bar">
    <div class="container">
      <div class="trust-grid">
        <div class="trust-item">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span>
          <div>
            <div class="t">Secure & Safe</div>
            <div class="d">Your information is protected with top security.</div>
          </div>
        </div>
        <div class="trust-item">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/></svg></span>
          <div>
            <div class="t">Trusted Worldwide</div>
            <div class="d">A part of the World Trade Council network.</div>
          </div>
        </div>
        <div class="trust-item">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg></span>
          <div>
            <div class="t">24/7 Support</div>
            <div class="d">We're here to help you succeed.</div>
          </div>
        </div>
        <div class="trust-item">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14l-5-4.87 6.91-1.01z"/></svg></span>
          <div>
            <div class="t">Money Back Guarantee</div>
            <div class="d">14-day money back guarantee on all paid plans.</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  


@endsection
