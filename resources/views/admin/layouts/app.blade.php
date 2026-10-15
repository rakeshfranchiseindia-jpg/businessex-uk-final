<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') | BusinessEx Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @include('admin.partials.styles')
    @stack('styles')
</head>
<body class="admin-body">
<div class="admin-shell">
    @include('admin.partials.sidebar')

    <div class="admin-main">
        <header class="admin-topbar">
            <button class="btn btn-link text-body d-lg-none p-0 me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-label="Open menu">
                <i class="bi bi-list fs-3"></i>
            </button>
            <div class="me-auto">
                <h1 class="admin-page-title">@yield('title', 'Dashboard')</h1>
                @hasSection('subtitle')
                    <div class="text-muted small">@yield('subtitle')</div>
                @endif
            </div>
            <div class="dropdown">
                @php($admin = auth('admin')->user())
                <button class="btn btn-light d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="admin-avatar">{{ strtoupper(mb_substr($admin->admin_name ?: $admin->admin_email, 0, 1)) }}</span>
                    <span class="d-none d-sm-inline text-start lh-sm">
                        <span class="d-block fw-semibold small">{{ $admin->admin_name ?: 'Admin' }}</span>
                        <span class="d-block text-muted" style="font-size:.75rem">{{ $admin->roleName() }}</span>
                    </span>
                    <i class="bi bi-chevron-down small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><span class="dropdown-item-text small text-muted">{{ $admin->admin_email }}</span></li>
                    <li><a class="dropdown-item" href="{{ url('/') }}" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>View website</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <main class="admin-content">
            @include('admin.partials.flash')
            @yield('content')
        </main>

        <footer class="admin-footer">&copy; {{ date('Y') }} BusinessEx. All rights reserved.</footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Confirm destructive actions declared with data-confirm.
    document.addEventListener('submit', function (event) {
        const message = event.target.getAttribute('data-confirm');
        if (message && !window.confirm(message)) {
            event.preventDefault();
        }
    });
</script>
@stack('scripts')
</body>
</html>
