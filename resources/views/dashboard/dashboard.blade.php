@extends('layouts.dashboard')

@section('title')
Dashboard | BusinessX - World Trade Council
@endsection
@section('description')
BusinessX connects businesses, startups, investors and mentors.
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/account-dashboard.css') }}">
@endsection

@section('content')


  <div class="dash-shell">

    <!-- Sidebar -->
    <aside class="dash-sidebar">
      <div class="brand">
        <span class="logo-pill"><img src="{{ asset('assets/img/businessx-logo.png?v=20261008') }}" alt="BusinessX"></span>
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
          <a href="{{ route('dashboard.index') }}" class="nav-item active" aria-current="page">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
          </a>
          <a href="{{ route('dashboard.profile') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg>
            Edit Profile
          </a>
          <a href="{{ route('dashboard.password') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Change Password
          </a>
          <a href="{{ route('dashboard.my-plan') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18v13H3z"/><path d="M3 10h18M7 15h4"/><path d="M7 3v4m10-4v4"/></svg>
            My Plan
          </a>
          <h5 class="interaction-heading">My Interaction</h5>
          <a href="{{ route('dashboard.inbox') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
            BX Inbox
          </a>
          <a href="{{ route('dashboard.proposals.sent') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7Z"/></svg>
            Proposal sent
          </a>
          <a href="{{ route('dashboard.proposals.received') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5"/><path d="m5 12 7-7 7 7"/><path d="M5 19h14"/></svg>
            Proposal received
          </a>
          <a href="{{ route('dashboard.instant-response') }}" class="nav-item">
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

    <!-- Main area -->
    <div class="dash-main">

      <!-- Top header -->
      <header class="dash-header">
        <button class="menu-btn" aria-label="Menu"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
        <div class="dash-search">
          <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" placeholder="Search for businesses, articles, news...">
        </div>
        <div class="dash-header-actions"
          data-dashboard-notifications
          data-activity-url="{{ $activityUrl }}"
          data-mark-all-url="{{ $markAllNotificationsReadUrl }}"
          data-csrf-token="{{ csrf_token() }}">
          <a href="#" class="lang-select">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            EN
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="10" height="10"><polyline points="6 9 12 15 18 9"/></svg>
          </a>
          <a href="{{ route('dashboard.inbox') }}" class="icon-bell" aria-label="Messages" title="Messages">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span class="notif-dot" data-unread-message-count @if ($unreadMessageCount === 0) hidden @endif>{{ $unreadMessageCount }}</span>
          </a>
          <details class="notification-dropdown" data-notification-dropdown>
            <summary class="icon-bell" aria-label="Notifications" title="Notifications">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              <span class="notif-dot" data-unread-notification-count @if ($unreadNotificationCount === 0) hidden @endif>{{ $unreadNotificationCount }}</span>
            </summary>
            <div class="notification-menu" aria-label="Notifications">
              <div class="notification-menu-heading">
                <strong>Notifications</strong>
                <button type="button" data-mark-all-notifications @if ($unreadNotificationCount === 0) hidden @endif>Mark all read</button>
              </div>
              <div class="notification-menu-list" data-notification-menu-list>
                @forelse ($notifications as $notification)
                  <a class="notification-menu-item{{ $notification['read_at'] ? '' : ' is-unread' }}"
                    href="{{ $notification['url'] }}"
                    data-notification-id="{{ $notification['id'] }}"
                    data-read-url="{{ $notification['read_url'] }}">
                    <span class="notif-icon {{ $notification['type'] === 'message' ? 'blue' : 'gold' }}" aria-hidden="true">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </span>
                    <span class="notification-menu-copy">
                      <strong>{{ $notification['title'] }}</strong>
                      <span>{{ $notification['message'] }}</span>
                      <time datetime="{{ $notification['created_at'] }}">{{ \Illuminate\Support\Carbon::parse($notification['created_at'])->diffForHumans() }}</time>
                    </span>
                  </a>
                @empty
                  <p class="notification-empty">You are all caught up.</p>
                @endforelse
              </div>
              <p class="notification-status" data-notification-status role="status" aria-live="polite"></p>
            </div>
          </details>
          <details class="account-menu">
            <summary class="user-chip">
            <div class="avatar"></div>
            <div class="info">
              <div class="name">{{ auth()->user()->name }}</div>
              <div class="company">Business account</div>
            </div>
            <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </summary>
            <div class="account-menu-panel">
              <a href="{{ route('dashboard.profile') }}">Settings</a>
              <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Sign Out</button>
              </form>
            </div>
          </details>
        </div>
      </header>

      <main class="account-dashboard" data-dashboard-screen="overview">
        <div class="page-title-row">
          <div>
            <h1>My Account</h1>
            <p>Manage your business profiles and account activity.</p>
          </div>
          <a class="account-primary-action" href="{{ route('dashboard.profiles.create') }}">Create Profile</a>
        </div>

        <section aria-labelledby="my-profiles-title">
          <div class="page-title-row">
            <div>
              <h2 id="my-profiles-title" style="color:var(--navy-800);font-size:16px;">My Profiles <a href="{{ route('dashboard.profiles.create') }}" style="font-weight:400;color:var(--gray-500);text-decoration:none;">(Create | Manage)</a></h2>
            </div>
            <form method="get" action="{{ route('dashboard.index') }}" class="profile-type-filter">
              <label for="profile-type-filter">Filter profiles</label>
              <select id="profile-type-filter" name="type" onchange="this.form.submit()">
                <option value="all" @selected($selectedProfileType === 'all')>All profile types</option>
                @foreach (['business' => 'Business', 'investor' => 'Investor', 'mentor' => 'Mentor', 'startup' => 'Startup'] as $type => $label)
                  <option value="{{ $type }}" @selected($selectedProfileType === $type)>{{ $label }}</option>
                @endforeach
              </select>
            </form>
          </div>
          <div class="account-tabs" role="tablist" aria-label="My profile activity">
            <button type="button" role="tab" id="my-profiles-tab" aria-controls="my-profiles-panel" aria-selected="true" tabindex="0" data-profile-tab="listings">New Listings</button>
            <button type="button" role="tab" id="saved-searches-tab" aria-controls="saved-searches-panel" aria-selected="false" tabindex="-1" data-profile-tab="saved">Saved Searches</button>
            <button type="button" role="tab" id="search-history-tab" aria-controls="search-history-panel" aria-selected="false" tabindex="-1" data-profile-tab="history">Search History</button>
          </div>

          <div class="account-tab-panel" id="my-profiles-panel" role="tabpanel" aria-labelledby="my-profiles-tab" data-profile-panel="listings">
            @if ($myProfiles->isEmpty())
              <div class="account-empty-state">
                <h2>No profiles found</h2>
                <p>Create a profile or choose another profile type filter.</p>
                <a class="profile-action" href="{{ route('dashboard.profiles.create') }}">Create a profile</a>
              </div>
            @else
              <div class="account-card-grid">
                @foreach ($myProfiles as $profile)
                  <article class="account-profile-card" data-owned-profile="{{ $profile->type }}">
                    <div class="profile-thumb">
                      <img src="{{ $profile->image && preg_match('#^https?://#i', $profile->image) ? $profile->image : asset($profile->default_image) }}" alt="" loading="lazy">
                    </div>
                    <div>
                      <span class="profile-kind">{{ $profile->label }} · {{ $profile->status }}</span>
                      <h2>{{ $profile->title }}</h2>
                      <p class="profile-meta">
                        {{ $profile->location }}
                        @if ($profile->category)
                          · {{ $profile->category }}
                        @endif
                        @if ($profile->summary)
                          <br>{{ \Illuminate\Support\Str::limit($profile->summary, 110) }}
                        @endif
                      </p>
                      <a class="profile-action" href="{{ route('dashboard.profiles.show', ['type' => $profile->type, 'id' => $profile->id]) }}">View Profile</a>
                      <a class="profile-action" href="{{ route('dashboard.profiles.edit', ['type' => $profile->type, 'id' => $profile->id]) }}">Manage Profile</a>
                    </div>
                  </article>
                @endforeach
              </div>
              @if ($myProfiles->hasPages())
                <nav class="account-profile-pagination" aria-label="Profile pages">
                  @if ($myProfiles->onFirstPage())
                    <span aria-disabled="true">Previous</span>
                  @else
                    <a href="{{ $myProfiles->previousPageUrl() }}">Previous</a>
                  @endif
                  @for ($page = 1; $page <= $myProfiles->lastPage(); $page++)
                    @if ($page === $myProfiles->currentPage())
                      <span aria-current="page">{{ $page }}</span>
                    @else
                      <a href="{{ $myProfiles->url($page) }}">{{ $page }}</a>
                    @endif
                  @endfor
                  @if ($myProfiles->hasMorePages())
                    <a href="{{ $myProfiles->nextPageUrl() }}">Next</a>
                  @else
                    <span aria-disabled="true">Next</span>
                  @endif
                </nav>
              @endif
            @endif
          </div>

          <div class="account-tab-panel" id="saved-searches-panel" role="tabpanel" aria-labelledby="saved-searches-tab" data-profile-panel="saved" hidden>
            <div class="account-empty-state">
              <span class="empty-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 3h12a2 2 0 0 1 2 2v16l-8-5-8 5V5a2 2 0 0 1 2-2z"/></svg></span>
              <h2>No saved searches yet</h2>
              <p>Save a search from the business directory to return to matching opportunities here.</p>
            </div>
          </div>

          <div class="account-tab-panel" id="search-history-panel" role="tabpanel" aria-labelledby="search-history-tab" data-profile-panel="history" hidden>
            <div class="account-empty-state">
              <span class="empty-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span>
              <h2>No profiles viewed yet</h2>
              <p>Profiles you open from the directory will appear in your search history.</p>
            </div>
          </div>
        </section>

        <section class="recommendations-section" aria-labelledby="recommendations-title">
          <div class="section-head">
            <h2 id="recommendations-title">Top Recommendations</h2>
            <a class="view-all" href="{{ route('business-listing') }}">Browse Directory</a>
          </div>
          <div class="recommendation-list">
            <div class="recommendation-item"><a href="{{ route('business-listing') }}">Specialist manufacturing company seeking growth investment</a><p>£1.5M · Manufacturing</p></div>
            <div class="recommendation-item"><a href="{{ route('business-listing') }}">Established cafe business for sale</a><p>£125K · Hospitality</p></div>
            <div class="recommendation-item"><a href="{{ route('business-listing') }}">Profitable service business seeking investment</a><p>£150K · Business services</p></div>
            <div class="recommendation-item"><a href="{{ route('business-listing') }}">Turnkey automotive body shop</a><p>£60K · Automotive</p></div>
            <div class="recommendation-item"><a href="{{ route('business-listing') }}">E-sports arena with expansion opportunity</a><p>£250K · Entertainment</p></div>
          </div>
        </section>
      </main>

      <main class="manage-business-view" data-dashboard-screen="manage" hidden>
        <div class="manage-page-heading">
          <div>
            <span class="manage-eyebrow">Business profile</span>
            <h1>Manage Business Information</h1>
          </div>
          <button class="manage-back-button" type="button" data-manage-back>Back to Dashboard</button>
        </div>

        <form class="manage-form" id="manage-business-form" novalidate>
          <div class="manage-form-heading">Manage Business Information</div>
          <div class="manage-tabs" role="tablist" aria-label="Business information sections">
            <button type="button" role="tab" id="manage-tab-confidential" aria-controls="manage-panel-confidential" aria-selected="true" tabindex="0" data-manage-tab="confidential">Confidential Info</button>
            <button type="button" role="tab" id="manage-tab-advert" aria-controls="manage-panel-advert" aria-selected="false" tabindex="-1" data-manage-tab="advert">Advert Details</button>
            <button type="button" role="tab" id="manage-tab-business" aria-controls="manage-panel-business" aria-selected="false" tabindex="-1" data-manage-tab="business">Business Info</button>
            <button type="button" role="tab" id="manage-tab-financial" aria-controls="manage-panel-financial" aria-selected="false" tabindex="-1" data-manage-tab="financial">Financial Details</button>
            <button type="button" role="tab" id="manage-tab-team" aria-controls="manage-panel-team" aria-selected="false" tabindex="-1" data-manage-tab="team">Team Details</button>
            <button type="button" role="tab" id="manage-tab-headquarters" aria-controls="manage-panel-headquarters" aria-selected="false" tabindex="-1" data-manage-tab="headquarters">Headquarters</button>
            <button type="button" role="tab" id="manage-tab-requirements" aria-controls="manage-panel-requirements" aria-selected="false" tabindex="-1" data-manage-tab="requirements">Requirements</button>
            <button type="button" role="tab" id="manage-tab-attachments" aria-controls="manage-panel-attachments" aria-selected="false" tabindex="-1" data-manage-tab="attachments">Attachments</button>
          </div>

          <section class="manage-tab-panel" id="manage-panel-confidential" role="tabpanel" aria-labelledby="manage-tab-confidential" data-manage-panel="confidential">
            <div class="manage-field-grid">
              <div class="manage-field"><label for="manage-contact-name">Your Name <span>*</span></label><input id="manage-contact-name" name="contactName" placeholder="Enter your name" autocomplete="name" required></div>
              <div class="manage-field"><label for="manage-designation">Designation <span>*</span></label><input id="manage-designation" name="designation" placeholder="e.g. Director" required></div>
              <div class="manage-field"><label for="manage-mobile">Mobile Number <span>*</span></label><div class="phone-input-group">@include('components.phone-country-code')<input id="manage-mobile" name="mobile" type="tel" placeholder="Enter mobile number" autocomplete="tel" required></div></div>
              <div class="manage-field"><label for="manage-email">Email ID <span>*</span></label><input id="manage-email" name="email" type="email" placeholder="Enter email address" autocomplete="email" required></div>
            </div>
          </section>

          <section class="manage-tab-panel" id="manage-panel-advert" role="tabpanel" aria-labelledby="manage-tab-advert" data-manage-panel="advert" hidden>
            <div class="manage-field-grid">
              <div class="manage-field"><label for="manage-ad-title">Advert Title <span>*</span></label><input id="manage-ad-title" name="advertTitle" placeholder="Add a clear listing title" required></div>
              <div class="manage-field"><label for="manage-ad-type">Advert Type <span>*</span></label><select id="manage-ad-type" name="advertType" required><option value="">Select advert type</option><option>Business for sale</option><option>Investment opportunity</option><option>Partnership opportunity</option></select></div>
              <div class="manage-field manage-field-full"><label for="manage-ad-summary">Advert Summary</label><textarea id="manage-ad-summary" name="advertSummary" rows="4" placeholder="Summarize the opportunity"></textarea></div>
            </div>
          </section>

          <section class="manage-tab-panel" id="manage-panel-business" role="tabpanel" aria-labelledby="manage-tab-business" data-manage-panel="business" hidden>
            <div class="manage-field-grid">
              <div class="manage-field"><label for="manage-business-name">Business Name <span>*</span></label><input id="manage-business-name" name="businessName" placeholder="Registered business name" required></div>
              <div class="manage-field"><label for="manage-industry">Industry <span>*</span></label><select id="manage-industry" name="industry" required><option value="">Select industry</option><option>Business services</option><option>Education</option><option>Finance &amp; investment</option><option>Manufacturing</option><option>Technology</option></select></div>
              <div class="manage-field"><label for="manage-website">Website</label><input id="manage-website" name="website" type="url" placeholder="https://example.com"></div>
              <div class="manage-field"><label for="manage-company-type">Company Type</label><select id="manage-company-type" name="companyType"><option value="">Select company type</option><option>Private limited company</option><option>Public limited company</option><option>Partnership</option><option>Sole trader</option></select></div>
              <div class="manage-field manage-field-full"><label for="manage-business-description">Business Description</label><textarea id="manage-business-description" name="businessDescription" rows="4" placeholder="Describe your business, products and services"></textarea></div>
            </div>
          </section>

          <section class="manage-tab-panel" id="manage-panel-financial" role="tabpanel" aria-labelledby="manage-tab-financial" data-manage-panel="financial" hidden>
            <div class="manage-field-grid">
              <div class="manage-field"><label for="manage-turnover">Annual Turnover</label><input id="manage-turnover" name="annualTurnover" type="number" min="0" placeholder="Enter annual turnover"></div>
              <div class="manage-field"><label for="manage-currency">Currency</label><select id="manage-currency" name="currency"><option>GBP (£)</option><option>USD ($)</option><option>EUR (€)</option></select></div>
              <div class="manage-field"><label for="manage-asking-price">Asking Price</label><input id="manage-asking-price" name="askingPrice" type="number" min="0" placeholder="Enter asking price"></div>
              <div class="manage-field"><label for="manage-funding-required">Funding Required</label><input id="manage-funding-required" name="fundingRequired" type="number" min="0" placeholder="Enter funding amount"></div>
            </div>
          </section>

          <section class="manage-tab-panel" id="manage-panel-team" role="tabpanel" aria-labelledby="manage-tab-team" data-manage-panel="team" hidden>
            <div class="manage-field-grid">
              <div class="manage-field"><label for="manage-team-size">Team Size</label><input id="manage-team-size" name="teamSize" type="number" min="1" placeholder="Number of employees"></div>
              <div class="manage-field"><label for="manage-key-contact">Key Contact</label><input id="manage-key-contact" name="keyContact" placeholder="Contact person name"></div>
              <div class="manage-field manage-field-full"><label for="manage-team-details">Leadership and Team Details</label><textarea id="manage-team-details" name="teamDetails" rows="4" placeholder="Add relevant experience and team information"></textarea></div>
            </div>
          </section>

          <section class="manage-tab-panel" id="manage-panel-headquarters" role="tabpanel" aria-labelledby="manage-tab-headquarters" data-manage-panel="headquarters" hidden>
            <div class="manage-field-grid">
              <div class="manage-field"><label for="manage-country">Country <span>*</span></label><input id="manage-country" name="country" autocomplete="country-name" placeholder="Country" required></div>
              <div class="manage-field"><label for="manage-city">City <span>*</span></label><input id="manage-city" name="city" autocomplete="address-level2" placeholder="City" required></div>
              <div class="manage-field manage-field-full"><label for="manage-address">Street Address</label><input id="manage-address" name="address" autocomplete="street-address" placeholder="Street address"></div>
              <div class="manage-field"><label for="manage-postcode">Postcode</label><input id="manage-postcode" name="postcode" autocomplete="postal-code" placeholder="Postcode"></div>
            </div>
          </section>

          <section class="manage-tab-panel" id="manage-panel-requirements" role="tabpanel" aria-labelledby="manage-tab-requirements" data-manage-panel="requirements" hidden>
            <div class="manage-field-grid">
              <div class="manage-field"><label for="manage-requirement-type">Requirement Type</label><select id="manage-requirement-type" name="requirementType"><option value="">Select requirement</option><option>Investment</option><option>Business acquisition</option><option>Distribution partner</option><option>Supplier</option></select></div>
              <div class="manage-field"><label for="manage-target-market">Target Market</label><input id="manage-target-market" name="targetMarket" placeholder="Countries or regions"></div>
              <div class="manage-field manage-field-full"><label for="manage-requirement-details">Requirement Details</label><textarea id="manage-requirement-details" name="requirementDetails" rows="4" placeholder="Describe what you are looking for"></textarea></div>
            </div>
          </section>

          <section class="manage-tab-panel" id="manage-panel-attachments" role="tabpanel" aria-labelledby="manage-tab-attachments" data-manage-panel="attachments" hidden>
            <div class="manage-field-grid">
              <div class="manage-field manage-field-full"><label for="manage-attachments">Upload Documents</label><input id="manage-attachments" name="attachments" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple><span class="manage-field-help">PDF, DOC, JPG or PNG files. Attachments are available for this session only.</span></div>
            </div>
          </section>

          <div class="manage-form-actions">
            <p id="manage-form-status" role="status" aria-live="polite"></p>
            <button type="submit">Submit</button>
          </div>
        </form>
      </main>

      <!-- Content -->
      <main class="dash-content">

        <!-- Welcome hero -->
        <div class="welcome-hero">
          <div class="content">
            <h1>Welcome back, John! 👋</h1>
            <p>Here's what's happening with your business today.</p>
          </div>
          <div class="right">
            <div class="membership-box">
              <div class="lbl">Membership Plan</div>
              <span class="premium-badge">★ Premium</span>
              <div class="validity">Valid till May 30, 2025</div>
            </div>
            <a href="{{ route('pricing') }}" class="btn-view-plan">View Plan Details</a>
          </div>
        </div>

        <!-- Stats grid -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="lbl">Profile Views</div>
            <div class="num">2,458</div>
            <div class="trend">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
              18.6% vs last 30 days
            </div>
          </div>
          <div class="stat-card">
            <div class="lbl">Search Appearances</div>
            <div class="num">3,671</div>
            <div class="trend">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
              24.3% vs last 30 days
            </div>
          </div>
          <div class="stat-card">
            <div class="lbl">Leads & Inquiries</div>
            <div class="num">128</div>
            <div class="trend">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
              12.8% vs last 30 days
            </div>
          </div>
          <div class="stat-card">
            <div class="lbl">Reviews</div>
            <div class="num">4.8</div>
            <div class="stars-row">
              <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
              <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
              <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
              <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
              <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
            </div>
            <div class="sub">Based on 56 reviews</div>
          </div>
          <div class="stat-card">
            <div class="lbl">Connections</div>
            <div class="num">356</div>
            <div class="trend">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
              15.2% vs last 30 days
            </div>
          </div>
        </div>

        <!-- Chart + completion -->
        <div class="row-1">
          <div class="chart-card">
            <div class="header">
              <h3>Profile Performance</h3>
              <select>
                <option>Last 30 Days</option>
                <option>Last 7 Days</option>
                <option>Last 90 Days</option>
              </select>
            </div>
            <div class="chart-legend">
              <div class="legend-item">
                <span class="dot" style="background:#3B82F6;"></span> Profile Views
              </div>
              <div class="legend-item">
                <span class="dot" style="background:#1769D2;"></span> Search Appearances
              </div>
            </div>
            <div class="chart-canvas">
              <svg class="chart-svg" viewBox="0 0 600 280" preserveAspectRatio="none">
                <!-- Grid lines -->
                <g stroke="#F3F4F6" stroke-width="1">
                  <line x1="40" y1="40" x2="580" y2="40"/>
                  <line x1="40" y1="100" x2="580" y2="100"/>
                  <line x1="40" y1="160" x2="580" y2="160"/>
                  <line x1="40" y1="220" x2="580" y2="220"/>
                </g>
                <!-- Y axis labels -->
                <g fill="#9CA3AF" font-size="10" font-family="Inter">
                  <text x="20" y="44" text-anchor="end">4K</text>
                  <text x="20" y="104" text-anchor="end">3K</text>
                  <text x="20" y="164" text-anchor="end">2K</text>
                  <text x="20" y="224" text-anchor="end">1K</text>
                  <text x="20" y="244" text-anchor="end">0</text>
                </g>
                <!-- X axis labels -->
                <g fill="#9CA3AF" font-size="10" font-family="Inter">
                  <text x="80" y="270" text-anchor="middle">Apr 21</text>
                  <text x="200" y="270" text-anchor="middle">Apr 28</text>
                  <text x="320" y="270" text-anchor="middle">May 5</text>
                  <text x="440" y="270" text-anchor="middle">May 12</text>
                  <text x="560" y="270" text-anchor="middle">May 19</text>
                </g>
                <!-- Search Appearances (gold) line -->
                <polyline points="80,140 140,100 200,90 260,70 320,60 380,80 440,40 500,55 560,30"
                  fill="none" stroke="#1769D2" stroke-width="3" stroke-linejoin="round"/>
                <!-- Search Appearances area fill -->
                <polygon points="80,140 140,100 200,90 260,70 320,60 380,80 440,40 500,55 560,30 560,240 80,240"
                  fill="#1769D2" opacity="0.08"/>
                <!-- Profile Views (blue) line -->
                <polyline points="80,180 140,170 200,160 260,150 320,140 380,130 440,100 500,110 560,90"
                  fill="none" stroke="#3B82F6" stroke-width="3" stroke-linejoin="round"/>
                <!-- Profile Views area fill -->
                <polygon points="80,180 140,170 200,160 260,150 320,140 380,130 440,100 500,110 560,90 560,240 80,240"
                  fill="#3B82F6" opacity="0.08"/>
                <!-- Data points -->
                <g fill="#1769D2" stroke="white" stroke-width="2">
                  <circle cx="80" cy="140" r="4"/><circle cx="140" cy="100" r="4"/>
                  <circle cx="200" cy="90" r="4"/><circle cx="260" cy="70" r="4"/>
                  <circle cx="320" cy="60" r="4"/><circle cx="380" cy="80" r="4"/>
                  <circle cx="440" cy="40" r="4"/><circle cx="500" cy="55" r="4"/>
                  <circle cx="560" cy="30" r="4"/>
                </g>
                <g fill="#3B82F6" stroke="white" stroke-width="2">
                  <circle cx="80" cy="180" r="4"/><circle cx="140" cy="170" r="4"/>
                  <circle cx="200" cy="160" r="4"/><circle cx="260" cy="150" r="4"/>
                  <circle cx="320" cy="140" r="4"/><circle cx="380" cy="130" r="4"/>
                  <circle cx="440" cy="100" r="4"/><circle cx="500" cy="110" r="4"/>
                  <circle cx="560" cy="90" r="4"/>
                </g>
                <!-- Tooltip on May 12 -->
                <g>
                  <line x1="440" y1="40" x2="440" y2="240" stroke="#9CA3AF" stroke-width="1" stroke-dasharray="3,3"/>
                  <rect x="380" y="10" width="120" height="50" fill="#1F2937" rx="6"/>
                  <text x="390" y="25" fill="white" font-size="9" font-family="Inter">May 12, 2024</text>
                  <circle cx="395" cy="38" r="3" fill="#3B82F6"/>
                  <text x="402" y="42" fill="white" font-size="9" font-family="Inter">Views: 2,458</text>
                  <circle cx="395" cy="52" r="3" fill="#1769D2"/>
                  <text x="402" y="56" fill="white" font-size="9" font-family="Inter">Searches: 3,671</text>
                </g>
              </svg>
            </div>
          </div>

          <div class="completion-card">
            <h3>Profile Completion</h3>
            <div class="progress-ring">
              <svg viewBox="0 0 120 120">
                <circle class="bg" cx="60" cy="60" r="50"/>
                <circle class="fg" cx="60" cy="60" r="50"
                  stroke-dasharray="314.16"
                  stroke-dashoffset="47.12"/>
              </svg>
              <div class="center">
                <div class="pct">85%</div>
                <div class="lbl">Completed</div>
              </div>
            </div>
            <p class="msg">Great job! Complete your profile to get more visibility.</p>
            <a href="{{ route('registration') }}" class="btn-complete">
              Complete Profile
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
          </div>
        </div>

        <!-- Row 2: Biz profile + Upgrade -->
        <div class="row-2">
          <div class="biz-profile-card">
            <div class="card-title-row">
              <h3>Your Business Profile</h3>
              <span class="verified-mini-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                Verified
              </span>
            </div>
            <div class="biz-logo">
              <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
              ABC
            </div>
            <div class="biz-name">
              ABC Manufacturing Ltd.
              <span class="verified" title="Verified"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2 4 4-2-2 4 4 2-4 2 2 4-4-2-2 4-2-4-4 2 2-4-4-2 4-2-2-4 4 2z"/></svg></span>
            </div>
            <div class="biz-meta" style="margin-top:6px;">Manufacturing</div>
            <div class="biz-meta-row" style="margin-top:6px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <span>London, United Kingdom</span>
            </div>
            <div class="biz-meta-row" style="margin-top:6px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span>Member since May 2023</span>
            </div>
            <div class="biz-actions">
              <a href="#" class="btn btn-dark">View Profile</a>
              <a href="{{ route('registration') }}" class="btn btn-outline-gray">Edit Profile</a>
            </div>
          </div>

          <div class="upgrade-visibility">
            <h3>Get More Visibility</h3>
            <p>Upgrade to Elite plan and showcase your business to a global audience.</p>
            <a href="{{ route('pricing') }}" class="btn-up">
              Upgrade Now
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
            <span class="trophy">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 5h-2V3H7v2H5a3 3 0 0 0-3 3v1a4 4 0 0 0 4 4h1.05A5 5 0 0 0 9 13.9V16H7v3h10v-3h-2v-2.1a5 5 0 0 0 1.95-1.9H18a4 4 0 0 0 4-4V8a3 3 0 0 0-3-3z"/></svg>
            </span>
          </div>

          <div class="quick-card">
            <div class="card-title-row">
              <h3>Quick Links</h3>
            </div>
            <div class="quick-list">
              <a href="{{ route('registration') }}" class="quick-link">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
                Add New Product / Service
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
              </a>
              <a href="{{ route('article') }}" class="quick-link">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span>
                Post an Article or News
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
              </a>
              <a href="{{ route('business-listing') }}" class="quick-link">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg></span>
                Browse Business Directory
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
              </a>
              <a href="#" class="quick-link">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
                Events & Webinars
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
              </a>
            </div>
          </div>
        </div>

        <!-- Row 3: Leads + Notifications -->
        <div class="row-3">
          <div class="leads-card">
            <div class="card-title-row">
              <h3>Recent Leads & Inquiries</h3>
              <a href="#" class="card-link">View All</a>
            </div>
            <div class="lead-item">
              <div class="lead-avatar green">WH</div>
              <div class="lead-info">
                <div class="top">
                  <span class="name">William Harris</span>
                  <span class="loc">· United States</span>
                </div>
                <div class="interest">Interested in Industrial Equipment</div>
                <div class="date">May 24, 2024</div>
              </div>
              <span class="lead-status new">New</span>
            </div>
            <div class="lead-item">
              <div class="lead-avatar purple">SB</div>
              <div class="lead-info">
                <div class="top">
                  <span class="name">Sophia Bennett</span>
                  <span class="loc">· Germany</span>
                </div>
                <div class="interest">Looking for Manufacturing Partner</div>
                <div class="date">May 23, 2024</div>
              </div>
              <span class="lead-status contacted">Contacted</span>
            </div>
            <div class="lead-item">
              <div class="lead-avatar orange">RJ</div>
              <div class="lead-info">
                <div class="top">
                  <span class="name">Rajesh Kumar</span>
                  <span class="loc">· India</span>
                </div>
                <div class="interest">Bulk Inquiry for Metal Components</div>
                <div class="date">May 22, 2024</div>
              </div>
              <span class="lead-status progress">In Progress</span>
            </div>
          </div>

          <div class="notifications-card">
            <div class="card-title-row">
              <h3>Latest Notifications</h3>
              <a href="{{ route('dashboard.inbox') }}" class="card-link">View All</a>
            </div>
            <div class="notification-feed" data-notification-feed>
              @forelse (array_slice($notifications, 0, 3) as $notification)
                <a class="notif-item notification-feed-item{{ $notification['read_at'] ? '' : ' is-unread' }}"
                  href="{{ $notification['url'] }}"
                  data-notification-id="{{ $notification['id'] }}"
                  data-read-url="{{ $notification['read_url'] }}">
                  <span class="notif-icon {{ $notification['type'] === 'message' ? 'blue' : 'gold' }}" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                  </span>
                  <span class="notif-content">
                    <span class="text">{{ $notification['title'] }}</span>
                    <span class="notification-feed-message">{{ $notification['message'] }}</span>
                    <time class="time" datetime="{{ $notification['created_at'] }}">{{ \Illuminate\Support\Carbon::parse($notification['created_at'])->diffForHumans() }}</time>
                  </span>
                </a>
              @empty
                <p class="notification-empty">No notifications yet.</p>
              @endforelse
            </div>
          </div>

          <div class="quick-card">
            <div class="card-title-row">
              <h3>Profile Health</h3>
            </div>
            <div style="font-size:13px;color:var(--gray-500);line-height:1.6;margin-bottom:16px;">
              Quick tips to boost your profile visibility and engagement:
            </div>
            <div class="quick-list">
              <div class="quick-link">
                <span class="ic" style="background:var(--green-100);color:var(--green-500);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span>
                <span style="color:var(--gray-700);">Logo uploaded</span>
              </div>
              <div class="quick-link">
                <span class="ic" style="background:var(--green-100);color:var(--green-500);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></span>
                <span style="color:var(--gray-700);">Contact details complete</span>
              </div>
              <div class="quick-link">
                <span class="ic" style="background:var(--gold-100);color:var(--gold-600);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
                <span style="color:var(--gold-600);font-weight:600;">Add product catalogue (3/5)</span>
              </div>
              <div class="quick-link">
                <span class="ic" style="background:var(--gold-100);color:var(--gold-600);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
                <span style="color:var(--gold-600);font-weight:600;">Add gallery images</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Bottom CTA -->
        <div class="bottom-cta">
          <div>
            <h3>Expand Your Business Globally</h3>
            <p>Connect with verified businesses, generate leads and grow your global network.</p>
          </div>
          <a href="{{ route('business-listing') }}" class="btn-gold">
            Explore Business Directory
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>

      </main>
    </div>
  </div>

  

  <script src="{{ asset('js/dashboard.js') }}"></script>
  <script src="{{ asset('js/dashboard-notifications.js') }}"></script>


@endsection
