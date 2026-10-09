<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="@yield('description', 'BusinessX connects businesses, startups, investors and mentors.')">
  <title>@yield('title', 'BusinessX - World Trade Council')</title>
  <link rel="icon" href="{{ asset('assets/img/favicon.svg?v=20261008') }}" type="image/svg+xml">
  <link rel="icon" href="{{ asset('assets/img/favicon.png?v=20261008') }}" type="image/png" sizes="48x48">
  <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png?v=20261008') }}" type="image/png">
  <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png?v=20261008') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/common-style.css?v=20261009') }}">
  @yield('head')
</head>
<body>
  @if (!empty($showHeader))
    @include('layouts.partials.header', ['page' => $page ?? 'home'])
  @endif

  @yield('content')

  @if (!empty($showFooter))
    @include('layouts.partials.footer')
  @endif

  @stack('scripts')
</body>
</html>
