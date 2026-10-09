@extends('layouts.app', ['page' => 'home', 'showHeader' => true, 'showFooter' => true])

@section('title', 'Page Not Found | BusinessX')
@section('description', 'The page you are looking for could not be found.')

@section('head')
<style>
  .not-found {
    min-height: 60vh;
    display: grid;
    place-items: center;
    padding: 64px 24px;
    background: linear-gradient(180deg, var(--gray-50), var(--white));
    text-align: center;
  }
  .not-found-content { max-width: 600px; }
  .not-found-code {
    margin: 0;
    color: var(--gold-500);
    font-size: clamp(96px, 20vw, 176px);
    font-weight: 800;
    letter-spacing: -0.08em;
    line-height: .9;
  }
  .not-found h1 {
    margin: 24px 0 12px;
    color: var(--navy-900);
    font-size: clamp(26px, 4vw, 36px);
  }
  .not-found p {
    margin: 0 auto 28px;
    color: var(--gray-600);
    font-size: 16px;
    line-height: 1.7;
  }
  .not-found-actions {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 12px;
  }
  .not-found-actions a {
    display: inline-flex;
    min-height: 46px;
    align-items: center;
    justify-content: center;
    padding: 0 20px;
    border: 1px solid var(--navy-800);
    border-radius: 6px;
    background: var(--navy-800);
    color: var(--white);
    font-size: 14px;
    font-weight: 700;
  }
  .not-found-actions a:hover { background: var(--navy-700); }
  .not-found-actions a.secondary {
    border-color: var(--gray-300);
    background: var(--white);
    color: var(--navy-800);
  }
  .not-found-actions a.secondary:hover { border-color: var(--gold-500); }
</style>
@endsection

@section('content')
<main class="not-found">
  <div class="not-found-content">
    <p class="not-found-code" aria-hidden="true">404</p>
    <h1>Page not found</h1>
    <p>Sorry, we couldn’t find the page you’re looking for. It may have moved or the address may be incorrect.</p>
    <nav class="not-found-actions" aria-label="Not found page actions">
      <a href="{{ route('home') }}">Back to Home</a>
      <a class="secondary" href="{{ route('business-listing') }}">Browse Businesses</a>
    </nav>
  </div>
</main>
@endsection
