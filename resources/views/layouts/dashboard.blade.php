<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="@yield('description', 'Manage your BusinessX account and profiles.')">
  <title>@yield('title', 'Account Dashboard | BusinessX')</title>
  <link rel="icon" href="{{ asset('assets/img/favicon.svg?v=20261008') }}" type="image/svg+xml">
  <link rel="icon" href="{{ asset('assets/img/favicon.png?v=20261008') }}" type="image/png" sizes="48x48">
  <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png?v=20261008') }}" type="image/png">
  <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png?v=20261008') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/common-style.css?v=20261009') }}">
  <link rel="stylesheet" href="{{ asset('css/account-dashboard.css') }}">
  @yield('head')
</head>
<body class="dashboard-page">
  @if (session('status'))
    <div role="status" style="position:relative;z-index:1000;padding:12px 24px;background:#e8f5e9;color:#1b5e20;text-align:center;font-weight:600;">
      {{ session('status') }}
    </div>
  @endif
  @yield('content')
  <section class="dashboard-live-chat" id="dashboard-live-chat" data-live-chat-widget
    data-conversations-url="{{ route('dashboard.live-chat.conversations') }}"
    data-current-user-id="{{ auth()->user()->user_id }}"
    aria-label="Live chat">
    <div class="dashboard-live-chat-panel" data-chat-panel hidden>
      <header class="dashboard-live-chat-header">
        <div><strong>Live Chat</strong><span data-chat-status role="status">Loading conversations…</span></div>
        <button type="button" data-chat-close aria-label="Close live chat">×</button>
      </header>
      <div class="dashboard-live-chat-content">
        <nav class="dashboard-live-chat-threads" aria-label="Your conversations" data-chat-threads></nav>
        <section class="dashboard-live-chat-thread" data-chat-thread aria-label="Selected conversation">
          <p class="dashboard-live-chat-placeholder">Choose a conversation to start chatting.</p>
        </section>
      </div>
      <form class="dashboard-live-chat-form" data-chat-form hidden>
        @csrf
        <label class="sr-only" for="dashboard-live-chat-message">Write a message</label>
        <textarea id="dashboard-live-chat-message" name="message" rows="2" maxlength="10000" placeholder="Write a message…" required></textarea>
        <button type="submit">Send</button>
      </form>
    </div>
    <button type="button" class="dashboard-live-chat-launcher" data-chat-open aria-expanded="false" aria-controls="dashboard-live-chat">
      <span aria-hidden="true">●</span> Live Chat <span class="dashboard-live-chat-count" data-chat-count hidden></span>
    </button>
  </section>
  <script src="{{ asset('js/common.js') }}"></script>
  @vite('resources/js/app.js')
  @stack('scripts')
</body>
</html>
