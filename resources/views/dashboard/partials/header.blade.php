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
        <form class="logout-form" method="post" action="{{ route('logout') }}">
          @csrf
          <button type="submit">Sign Out</button>
        </form>
      </div>
    </details>
  </div>
</header>
