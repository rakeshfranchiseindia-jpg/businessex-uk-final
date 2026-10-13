<aside class="dash-sidebar">
  <div class="brand">
    <a href="{{ route('home') }}" class="logo-pill"><img src="{{ asset('assets/img/businessx-logo.png?v=20261008') }}" alt="BusinessX"></a>
  </div>

  <div class="account-identity">
    <div class="identity-mark" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
    <div class="identity-name">{{ auth()->user()->name }}</div>
    <div class="identity-role">{{ \Illuminate\Support\Str::title(auth()->user()->reg_profile ?: 'member') }} account</div>
    <div class="identity-contact">
      <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
      @if (auth()->user()->email)
        <a href="mailto:{{ auth()->user()->email }}">{{ auth()->user()->email }}</a>
      @else
        <span>N/A</span>
      @endif
    </div>
    <div class="identity-contact">
      <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 3h4l2 5-2.5 1.5a15 15 0 0 0 6 6L16 13l5 2v4a2 2 0 0 1-2 2C10 21 3 14 3 5a2 2 0 0 1 2-2Z"/></svg>
      @if (auth()->user()->mobile)
        <a href="tel:{{ preg_replace('/[^0-9+]/', '', auth()->user()->mobile) }}">{{ auth()->user()->mobile }}</a>
      @else
        <span>N/A</span>
      @endif
    </div>
    <div class="identity-contact">
      <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
      <span>{{ filled(auth()->user()->location) ? auth()->user()->location : 'N/A' }}</span>
    </div>
    <a class="identity-edit" href="{{ route('dashboard.profile') }}">Edit profile</a>
  </div>

  <div class="nav-section account-navigation">
    <h5>My Account</h5>
    <div class="nav-items">
      <a href="{{ route('dashboard.index') }}" class="nav-item @if (request()->routeIs('dashboard.index')) active @endif" @if (request()->routeIs('dashboard.index')) aria-current="page" @endif>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Dashboard
      </a>
      <a href="{{ route('dashboard.profile') }}" class="nav-item @if (request()->routeIs('dashboard.profile')) active @endif" @if (request()->routeIs('dashboard.profile')) aria-current="page" @endif>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg>
        Edit Profile
      </a>
      <a href="{{ route('dashboard.password') }}" class="nav-item @if (request()->routeIs('dashboard.password')) active @endif" @if (request()->routeIs('dashboard.password')) aria-current="page" @endif>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Change Password
      </a>
      <a href="{{ route('dashboard.my-plan') }}" class="nav-item @if (request()->routeIs('dashboard.my-plan')) active @endif" @if (request()->routeIs('dashboard.my-plan')) aria-current="page" @endif>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18v13H3z"/><path d="M3 10h18M7 15h4"/><path d="M7 3v4m10-4v4"/></svg>
        My Plan
      </a>
      <h5 class="interaction-heading">My Interaction</h5>
      <a href="{{ route('dashboard.inbox') }}" class="nav-item @if (request()->routeIs('dashboard.inbox')) active @endif" @if (request()->routeIs('dashboard.inbox')) aria-current="page" @endif>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
        BX Inbox
      </a>
      <a href="{{ route('dashboard.proposals.sent') }}" class="nav-item @if (request()->routeIs('dashboard.proposals.sent')) active @endif" @if (request()->routeIs('dashboard.proposals.sent')) aria-current="page" @endif>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7Z"/></svg>
        Proposal sent
      </a>
      <a href="{{ route('dashboard.proposals.received') }}" class="nav-item @if (request()->routeIs('dashboard.proposals.received')) active @endif" @if (request()->routeIs('dashboard.proposals.received')) aria-current="page" @endif>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/><path d="M5 19h14"/></svg>
        Proposal received
      </a>
      <a href="{{ route('dashboard.instant-response') }}" class="nav-item @if (request()->routeIs('dashboard.instant-response')) active @endif" @if (request()->routeIs('dashboard.instant-response')) aria-current="page" @endif>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v3m0 12v3M3 12h3m12 0h3"/><circle cx="12" cy="12" r="7"/><path d="m9 12 2 2 4-4"/></svg>
        Instant Response
      </a>
      <a href="#dashboard-live-chat" class="nav-item" data-open-live-chat>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v3m0 12v3M3 12h3m12 0h3"/><circle cx="12" cy="12" r="7"/><path d="m9 12 2 2 4-4"/></svg>
        Live Chat
      </a>
    </div>
  </div>

  <div class="nav-section">
    <h5>My Business</h5>
    <div class="nav-items">
      <a href="#" class="nav-item active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Dashboard
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
        Business Profile
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        Leads & Inquiries
        <span class="nav-badge gray">12</span>
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.91 8.84L8.56 21.18M16.51 5.36L19 2.87l3.09 3.09L19.6 8.45M8.56 21.18l-3.27-3.27"/></svg>
        Products & Services
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        Media Gallery
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
        Reviews & Ratings
      </a>
    </div>
  </div>

  <div class="nav-section">
    <h5>Network & Growth</h5>
    <div class="nav-items">
      <a href="{{ route('business-listing') }}" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
        Business Directory
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
        Connections
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Messages
        <span class="nav-badge gray">8</span>
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        Saved Businesses
      </a>
    </div>
  </div>

  <div class="nav-section">
    <h5>Insights & Tools</h5>
    <div class="nav-items">
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
        Profile Insights
        <span class="nav-badge green">New</span>
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M18 17V9M13 17V5M8 17v-3"/></svg>
        Analytics
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Events & Webinars
      </a>
      <a href="{{ route('article') }}" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Articles & News
      </a>
    </div>
  </div>

  <div class="nav-section">
    <h5>Account</h5>
    <div class="nav-items">
      <a href="{{ route('pricing') }}" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
        Membership
        <span class="nav-badge gold">Premium</span>
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        Billing & Invoices
      </a>
      <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        Account Settings
      </a>
      <a href="{{ route('home') }}" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Log Out
      </a>
    </div>
  </div>

  <div class="upgrade-card">
    <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4l3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"/></svg>
    <h4>Upgrade to Elite Membership</h4>
    <p>Get more visibility, priority leads and global exposure.</p>
    <a href="{{ route('pricing') }}">Upgrade Now →</a>
  </div>
</aside>
