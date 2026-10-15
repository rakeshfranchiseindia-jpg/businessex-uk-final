<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login | BusinessX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @include('admin.partials.styles')
    <style>
        body { background: linear-gradient(135deg, var(--admin-navy) 0%, #1f4a85 100%); min-height: 100vh; }
        .login-card { max-width: 420px; width: 100%; border: 0; border-radius: 1rem; box-shadow: 0 20px 50px rgba(0,0,0,.25); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
<div class="card login-card">
    <div class="card-body p-4 p-sm-5">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center gap-2 fs-4 fw-bold text-navy">
                <i class="bi bi-briefcase-fill text-warning"></i> BusinessX
            </div>
            <h1 class="h5 mt-3 mb-1 fw-semibold">Welcome Back</h1>
            <p class="text-muted small mb-0">Sign in to access your BusinessX Admin Panel</p>
        </div>

        @include('admin.partials.flash')

        <form method="POST" action="{{ route('admin.login.store') }}" novalidate>
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label fw-medium">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label fw-medium">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                <label class="form-check-label small" for="remember">Keep me signed in</label>
            </div>
            <button class="btn btn-navy w-100 py-2 fw-semibold"><i class="bi bi-box-arrow-in-right me-1"></i> Sign In</button>
        </form>
    </div>
    <div class="card-footer bg-white text-center small text-muted border-0 pb-4">&copy; {{ date('Y') }} BusinessX. All rights reserved.</div>
</div>
</body>
</html>
