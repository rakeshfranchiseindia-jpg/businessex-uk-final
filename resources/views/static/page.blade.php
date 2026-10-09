@extends('layouts.app')

@section('title', $staticPage['title'] . ' | BusinessX')
@section('description', $staticPage['description'])

@section('head')
<style>
  .static-page { background: var(--gray-50); min-height: 60vh; padding: 64px 0 80px; }
  .static-page .container { max-width: 960px; }
  .static-page__eyebrow { color: var(--gold-600); font-size: 12px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
  .static-page__title { color: var(--navy-700); font-size: clamp(32px, 5vw, 48px); font-weight: 800; letter-spacing: -.035em; margin: 10px 0 14px; }
  .static-page__lead { color: var(--gray-600); font-size: 17px; line-height: 1.7; margin: 0 0 30px; max-width: 760px; }
  .static-page__content { background: #fff; border: 1px solid var(--gray-200); border-radius: 14px; box-shadow: 0 12px 36px rgba(26, 35, 50, .06); padding: clamp(24px, 5vw, 48px); }
  .static-page__section + .static-page__section { border-top: 1px solid var(--gray-100); margin-top: 28px; padding-top: 26px; }
  .static-page__section h2 { color: var(--navy-700); font-size: 20px; font-weight: 750; margin: 0 0 12px; }
  .static-page__section p { color: var(--gray-600); font-size: 15px; line-height: 1.8; margin: 0 0 12px; }
  .static-page__section p:last-child { margin-bottom: 0; }
  .static-page__contact { background: var(--gray-50); border-radius: 10px; margin-top: 24px; padding: 20px 24px; }
  .static-page__contact p { margin: 0 0 10px; }
  .static-page__contact p:last-child { margin-bottom: 0; }
  .static-page__contact a { color: var(--navy-700); font-weight: 700; }
  .static-page__action { display: inline-flex; align-items: center; justify-content: center; background: var(--navy-700); border-radius: 7px; color: #fff; font-size: 14px; font-weight: 700; margin-top: 28px; padding: 13px 20px; text-decoration: none; }
  .static-page__action:hover { background: var(--navy-800); color: #fff; }
  @media (max-width: 640px) { .static-page { padding: 42px 0 56px; } }
</style>
@endsection

@section('content')
<main class="static-page">
  <div class="container">
    <span class="static-page__eyebrow">BusinessX</span>
    <h1 class="static-page__title">{{ $staticPage['title'] }}</h1>
    <p class="static-page__lead">{{ $staticPage['description'] }}</p>

    <div class="static-page__content">
      @foreach ($staticPage['sections'] as $section)
        <section class="static-page__section">
          <h2>{{ $section['heading'] }}</h2>
          @foreach ($section['paragraphs'] as $paragraph)
            <p>{{ $paragraph }}</p>
          @endforeach
        </section>
      @endforeach

      @if (isset($staticPage['email']) || isset($staticPage['address']))
        <div class="static-page__contact">
          @if (isset($staticPage['email']))
            <p><strong>Email:</strong> <a href="mailto:{{ $staticPage['email'] }}">{{ $staticPage['email'] }}</a></p>
          @endif
          @if (isset($staticPage['address']))
            <p><strong>Office:</strong> {{ $staticPage['address'] }}</p>
          @endif
        </div>
      @endif

      @if (isset($staticPage['action']))
        <a class="static-page__action" href="{{ route($staticPage['action']['route']) }}">{{ $staticPage['action']['label'] }}</a>
      @endif
    </div>
  </div>
</main>
@endsection
