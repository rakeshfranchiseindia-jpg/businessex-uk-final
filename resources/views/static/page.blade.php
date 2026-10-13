@extends('layouts.app')

@section('title', $staticPage['title'] . ' | BusinessX')
@section('description', $staticPage['description'])

@section('head')
<style>
  .static-page { background: var(--gray-50); min-height: 60vh; padding: 64px 0 80px; }
  .static-page .container { max-width: 960px; }
  .static-page .container.static-page--wide { max-width: 1180px; }
  .static-page__eyebrow { color: var(--gold-600); font-size: 12px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
  .static-page__title { color: var(--navy-700); font-size: clamp(32px, 5vw, 48px); font-weight: 800; letter-spacing: -.035em; margin: 10px 0 14px; }
  .static-page__lead { color: var(--gray-600); font-size: 17px; line-height: 1.7; margin: 0 0 30px; max-width: 760px; }
  .static-page__content { background: #fff; border: 1px solid var(--gray-200); border-radius: 14px; box-shadow: 0 12px 36px rgba(26, 35, 50, .06); padding: clamp(24px, 5vw, 48px); }
  .static-page__section + .static-page__section { border-top: 1px solid var(--gray-100); margin-top: 28px; padding-top: 26px; }
  .static-page__section h2 { display: flex; align-items: center; gap: 12px; color: var(--navy-700); font-size: 20px; font-weight: 750; margin: 0 0 12px; }
  .static-page__section h2 .num { flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: rgba(199,150,47,0.12); color: var(--gold-600); font-size: 13px; font-weight: 800; }
  .static-page__notice { display: flex; align-items: center; gap: 12px; background: #fff8ec; border: 1px solid rgba(199,150,47,0.3); border-radius: 10px; margin-bottom: 26px; padding: 14px 18px; max-width: 760px; }
  .static-page__notice .ic { flex-shrink: 0; color: var(--gold-600); }
  .static-page__notice .ic svg { width: 20px; height: 20px; }
  .static-page__notice p { color: var(--navy-700); font-size: 14px; font-weight: 600; margin: 0; }
  .static-page__section p { color: var(--gray-600); font-size: 15px; line-height: 1.8; margin: 0 0 12px; }
  .static-page__section p:last-child { margin-bottom: 0; }
  .static-page__section ul { margin: 0 0 12px; padding-left: 20px; }
  .static-page__section ul:last-child { margin-bottom: 0; }
  .static-page__section li { color: var(--gray-600); font-size: 15px; line-height: 1.75; margin-bottom: 6px; }
  .static-page__section li:last-child { margin-bottom: 0; }
  .static-page__contact { background: var(--gray-50); border-radius: 10px; margin-top: 24px; padding: 20px 24px; }
  .static-page__contact p { margin: 0 0 10px; }
  .static-page__contact p:last-child { margin-bottom: 0; }
  .static-page__contact a { color: var(--navy-700); font-weight: 700; }
  .static-page__action { display: inline-flex; align-items: center; justify-content: center; background: var(--navy-700); border-radius: 7px; color: #fff; font-size: 14px; font-weight: 700; margin-top: 28px; padding: 13px 20px; text-decoration: none; }
  .static-page__action:hover { background: var(--navy-800); color: #fff; }
  .static-page__intro-grid { display: grid; grid-template-columns: 1.3fr 1fr; gap: clamp(20px, 4vw, 36px); align-items: center; }
  .static-page__intro-text p { color: var(--gray-600); font-size: 15px; line-height: 1.8; margin: 0 0 12px; }
  .static-page__intro-text p:last-child { margin-bottom: 0; }
  .static-page__video-frame { position: relative; width: 100%; padding-top: 56.25%; border-radius: 10px; overflow: hidden; background: var(--gray-900); box-shadow: 0 10px 28px rgba(20,45,82,0.12); }
  .static-page__video-frame iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
  @media (max-width: 760px) { .static-page__intro-grid { grid-template-columns: 1fr; } }
  @media (max-width: 640px) { .static-page { padding: 42px 0 56px; } }

  /* Quick-summary card grid */
  .static-page__summary { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 30px; }
  .static-page__summary-card { background: #fff; border: 1px solid var(--gray-200); border-top: 3px solid var(--gold-500); border-radius: 10px; box-shadow: 0 6px 18px rgba(20,45,82,0.05); padding: 18px 20px; }
  .static-page__summary-card h3 { color: var(--navy-700); font-size: 13px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; margin: 0 0 10px; }
  .static-page__summary-card ul { margin: 0; padding-left: 18px; }
  .static-page__summary-card li { color: var(--gray-600); font-size: 13px; line-height: 1.55; margin-bottom: 6px; }
  .static-page__summary-card li:last-child { margin-bottom: 0; }
  @media (max-width: 991px) { .static-page__summary { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 575px) { .static-page__summary { grid-template-columns: 1fr; } }

  /* Mobile jump-to-section menu */
  .static-page__mobile-nav { display: none; margin-bottom: 20px; }
  .static-page__mobile-nav select { width: 100%; border: 1px solid var(--gray-200); border-radius: 7px; color: var(--navy-700); font-weight: 600; padding: 11px 14px; }

  /* Content + sticky section nav layout */
  .static-page__layout { display: flex; align-items: flex-start; gap: 32px; }
  .static-page__layout .static-page__content { flex: 1 1 auto; min-width: 0; }
  .static-page__nav-col { flex: 0 0 240px; }
  .static-page__nav { background: #fff; border: 1px solid var(--gray-200); border-radius: 10px; box-shadow: 0 6px 18px rgba(20,45,82,0.04); padding: 8px 0; position: sticky; top: 20px; }
  .static-page__nav a { display: block; border-left: 3px solid transparent; color: var(--gray-600); font-size: 13.5px; padding: 9px 16px; text-decoration: none; transition: all .15s ease; }
  .static-page__nav a:hover { background: var(--gray-50); color: var(--navy-700); }
  .static-page__nav a.is-active { background: var(--gray-50); border-left-color: var(--gold-500); color: var(--navy-700); font-weight: 700; }
  .static-page__section[id] { scroll-margin-top: 100px; }
  @media (max-width: 991px) {
    .static-page__nav-col { display: none; }
    .static-page__mobile-nav { display: block; }
  }

  /* Contact page: info + form */
  .static-page__contact-grid { display: grid; grid-template-columns: 0.85fr 1.15fr; gap: 36px; margin-top: 8px; align-items: start; }
  .static-page__contact-info h2 { color: var(--navy-700); font-size: 19px; font-weight: 800; margin: 0 0 6px; }
  .static-page__contact-info > p.static-page__contact-sub { color: var(--gray-500); font-size: 13.5px; margin: 0 0 20px; }
  .static-page__contact { background: transparent; border-radius: 0; margin-top: 0; padding: 0; display: flex; flex-direction: column; gap: 14px; }
  .static-page__contact-row { display: flex; align-items: flex-start; gap: 14px; background: var(--gray-50); border: 1px solid var(--gray-100); border-radius: 10px; padding: 14px 16px; }
  .static-page__contact-row .ic { flex-shrink: 0; width: 38px; height: 38px; border-radius: 50%; background: rgba(19,141,227,0.1); color: var(--navy-700); display: flex; align-items: center; justify-content: center; }
  .static-page__contact-row .ic svg { width: 17px; height: 17px; }
  .static-page__contact-row .t1 { color: var(--gray-400); font-size: 11.5px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; margin-bottom: 3px; }
  .static-page__contact-row .t2 { color: var(--gray-700); font-size: 14px; line-height: 1.55; }
  .static-page__contact-row a { color: var(--navy-700); font-weight: 700; text-decoration: none; }
  .static-page__contact-row a:hover { text-decoration: underline; }
  .static-page__contact-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(25,135,84,0.08); color: #198754; border-radius: 999px; font-size: 12.5px; font-weight: 700; padding: 7px 14px; margin-top: 18px; }
  .static-page__contact-badge svg { width: 14px; height: 14px; }

  .static-page__contact-form { background: #fff; border: 1px solid var(--gray-200); border-radius: 14px; box-shadow: 0 10px 30px rgba(20,45,82,0.06); padding: 28px; }
  .static-page__contact-form h2 { color: var(--navy-700); font-size: 19px; font-weight: 800; margin: 0 0 4px; }
  .static-page__contact-form > p.static-page__form-sub { color: var(--gray-500); font-size: 13.5px; margin: 0 0 20px; }
  .static-page__form-status { display: flex; align-items: center; gap: 10px; border-radius: 8px; font-size: 13.5px; font-weight: 600; margin: 0 0 18px; padding: 12px 14px; }
  .static-page__form-status--success { background: #ecfdf5; color: #166534; border: 1px solid #bbf7d0; }
  .static-page__form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .static-page__form-field { margin-bottom: 18px; }
  .static-page__form-field label { display: block; color: var(--gray-700); font-size: 13.5px; font-weight: 700; margin-bottom: 7px; }
  .static-page__form-field label span { color: #c0392b; }
  .static-page__form-field input,
  .static-page__form-field textarea,
  .static-page__form-field .phone-country-code { width: 100%; border: 1.5px solid var(--gray-200); border-radius: 8px; background: var(--gray-50); color: var(--gray-700); font: inherit; font-size: 14.5px; padding: 12px 14px; transition: border-color .15s ease, background .15s ease; }
  .static-page__form-field textarea { resize: vertical; min-height: 100px; }
  .static-page__form-field input:focus,
  .static-page__form-field textarea:focus,
  .static-page__form-field .phone-country-code:focus { outline: none; border-color: var(--gold-500); background: #fff; box-shadow: 0 0 0 3px rgba(199,150,47,.15); }
  .static-page__form-field .phone-input-group { display: flex; gap: 10px; }
  .static-page__form-field .phone-country-code { flex: 0 0 112px; width: 112px; }
  .static-page__form-error { display: block; color: #c0392b; font-size: 12.5px; font-weight: 600; margin-top: 6px; }
  .static-page__form-submit { display: inline-flex; align-items: center; gap: 8px; background: var(--navy-700); border-radius: 8px; color: #fff; font-size: 14.5px; font-weight: 700; padding: 13px 28px; transition: background .15s ease, transform .15s ease; }
  .static-page__form-submit svg { width: 15px; height: 15px; }
  .static-page__form-submit:hover { background: var(--navy-800); transform: translateY(-1px); }
  .static-page__form-terms { color: var(--gray-500); font-size: 12.5px; margin: 14px 0 0; }
  .static-page__form-terms a { color: var(--navy-700); font-weight: 600; }
  @media (max-width: 760px) {
    .static-page__contact-grid { grid-template-columns: 1fr; }
    .static-page__form-row { grid-template-columns: 1fr; }
  }
</style>
@endsection

@section('content')
<main class="static-page">
  <div class="container {{ isset($staticPage['show_nav']) ? 'static-page--wide' : '' }}">
    <span class="static-page__eyebrow">BusinessX</span>
    <h1 class="static-page__title">{{ $staticPage['title'] }}</h1>
    <p class="static-page__lead">{{ $staticPage['description'] }}</p>

    @if (isset($staticPage['notice']))
      <div class="static-page__notice">
        <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
        <p>{{ $staticPage['notice'] }}</p>
      </div>
    @endif

    @if (isset($staticPage['summary']))
      <div class="static-page__summary">
        @foreach ($staticPage['summary'] as $card)
          <div class="static-page__summary-card">
            <h3>{{ $card['heading'] }}</h3>
            <ul>
              @foreach ($card['items'] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </div>
        @endforeach
      </div>
    @endif

    @if (isset($staticPage['show_nav']))
      <div class="static-page__mobile-nav">
        <select id="staticPageMobileNav" aria-label="Jump to section">
          <option value="">Jump to a section…</option>
          @foreach ($staticPage['sections'] as $section)
            @if (isset($section['id']))
              <option value="{{ $section['id'] }}">{{ $section['heading'] }}</option>
            @endif
          @endforeach
        </select>
      </div>
    @endif

    <div class="{{ isset($staticPage['show_nav']) ? 'static-page__layout' : '' }}">
      <div class="static-page__content">
        @if (isset($staticPage['video']))
          <section class="static-page__section static-page__intro">
            <div class="static-page__intro-grid">
              <div class="static-page__intro-text">
                @foreach ($staticPage['sections'][0]['paragraphs'] as $paragraph)
                  <p>{{ $paragraph }}</p>
                @endforeach
              </div>
              <div class="static-page__intro-video">
                <div class="static-page__video-frame">
                  <iframe src="{{ $staticPage['video'] }}" title="{{ $staticPage['title'] }}" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
              </div>
            </div>
          </section>
          @foreach (array_slice($staticPage['sections'], 1) as $section)
            <section class="static-page__section" @if (isset($section['id'])) id="{{ $section['id'] }}" @endif>
              <h2>{{ $section['heading'] }}</h2>
              @foreach ($section['paragraphs'] as $paragraph)
                @if (is_array($paragraph))
                  <ul>
                    @foreach ($paragraph as $item)
                      <li>{!! $item !!}</li>
                    @endforeach
                  </ul>
                @else
                  <p>{{ $paragraph }}</p>
                @endif
              @endforeach
            </section>
          @endforeach
        @else
          @foreach ($staticPage['sections'] as $section)
            <section class="static-page__section" @if (isset($section['id'])) id="{{ $section['id'] }}" @endif>
              <h2>
                @if (isset($staticPage['numbered']))
                  <span class="num">{{ $loop->iteration }}</span>
                @endif
                {{ $section['heading'] }}
              </h2>
              @foreach ($section['paragraphs'] as $paragraph)
                @if (is_array($paragraph))
                  <ul>
                    @foreach ($paragraph as $item)
                      <li>{!! $item !!}</li>
                    @endforeach
                  </ul>
                @else
                  <p>{{ $paragraph }}</p>
                @endif
              @endforeach
            </section>
          @endforeach
        @endif

        @if (isset($staticPage['form']))
          <div class="static-page__contact-grid">
            <div class="static-page__contact-info">
              @if (isset($staticPage['contact_heading']))
                <h2>{{ $staticPage['contact_heading'] }}</h2>
                <p class="static-page__contact-sub">We typically reply within one business day.</p>
              @endif
              @if (isset($staticPage['email']) || isset($staticPage['address']))
                <div class="static-page__contact">
                  @if (isset($staticPage['email']))
                    <div class="static-page__contact-row">
                      <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                      <div>
                        <div class="t1">Email</div>
                        <div class="t2"><a href="mailto:{{ $staticPage['email'] }}">{{ $staticPage['email'] }}</a></div>
                      </div>
                    </div>
                  @endif
                  @if (isset($staticPage['address']))
                    <div class="static-page__contact-row">
                      <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                      <div>
                        <div class="t1">Postal Mail</div>
                        <div class="t2">{{ $staticPage['address'] }}</div>
                      </div>
                    </div>
                  @endif
                </div>
              @endif
              <span class="static-page__contact-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>No passwords or financial details, please</span>
            </div>
            <div class="static-page__contact-form">
              <h2>Send Us Your Questions and Feedback</h2>
              <p class="static-page__form-sub">Fill in the form below and our team will get back to you.</p>
              @if (session('success'))
                <p class="static-page__form-status static-page__form-status--success" role="status"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</p>
              @endif
              <form method="POST" action="{{ route('contact.submit') }}">
                @csrf
                <div class="static-page__form-row">
                  <div class="static-page__form-field">
                    <label for="contact-name">Your Name <span>*</span></label>
                    <input id="contact-name" name="contact_name" type="text" pattern="[A-Za-z\s]+" title="Name should only contain letters and spaces" minlength="5" maxlength="55" placeholder="Enter Your Name" value="{{ old('contact_name') }}" required>
                    @error('contact_name') <span class="static-page__form-error" role="alert">{{ $message }}</span> @enderror
                  </div>
                  <div class="static-page__form-field">
                    <label for="contact-email">Email Address <span>*</span></label>
                    <input id="contact-email" name="contact_email" type="email" maxlength="100" placeholder="Enter Your Email ID" value="{{ old('contact_email') }}" required>
                    @error('contact_email') <span class="static-page__form-error" role="alert">{{ $message }}</span> @enderror
                  </div>
                </div>
                <div class="static-page__form-field">
                  <label for="contact-mobile">Mobile Number <span>*</span></label>
                  <div class="phone-input-group">
                    @include('components.phone-country-code')
                    <input id="contact-mobile" name="contact_mobile" type="tel" placeholder="Enter Your Mobile Number" inputmode="numeric" maxlength="10" value="{{ old('contact_mobile') }}" required>
                  </div>
                  @error('contact_mobile') <span class="static-page__form-error" role="alert">{{ $message }}</span> @enderror
                </div>
                <div class="static-page__form-field">
                  <label for="contact-comment">Comments <span>*</span></label>
                  <textarea id="contact-comment" name="contact_comment" rows="4" minlength="15" maxlength="155" placeholder="Enter Your Message" required>{{ old('contact_comment') }}</textarea>
                  @error('contact_comment') <span class="static-page__form-error" role="alert">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="static-page__form-submit">Submit<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></button>
                <p class="static-page__form-terms">By clicking Submit you are accepting <a href="{{ route('terms') }}">Terms &amp; Conditions</a></p>
              </form>
            </div>
          </div>
        @elseif (isset($staticPage['email']) || isset($staticPage['address']))
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

      @if (isset($staticPage['show_nav']))
        <div class="static-page__nav-col">
          <nav class="static-page__nav" aria-label="{{ $staticPage['title'] }} sections">
            @foreach ($staticPage['sections'] as $section)
              @if (isset($section['id']))
                <a href="#{{ $section['id'] }}">{{ $section['heading'] }}</a>
              @endif
            @endforeach
          </nav>
        </div>
      @endif
    </div>
  </div>
</main>

@if (isset($staticPage['show_nav']))
  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var navLinks = Array.prototype.slice.call(document.querySelectorAll('.static-page__nav a'));
      var mobileNav = document.getElementById('staticPageMobileNav');
      if (!navLinks.length) return;

      var sections = navLinks
        .map(function (link) { return document.getElementById(link.getAttribute('href').slice(1)); })
        .filter(Boolean);

      function scrollToSection(id) {
        var target = document.getElementById(id);
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }

      navLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
          e.preventDefault();
          scrollToSection(this.getAttribute('href').slice(1));
          history.replaceState(null, '', this.getAttribute('href'));
        });
      });

      if (mobileNav) {
        mobileNav.addEventListener('change', function () {
          if (this.value) scrollToSection(this.value);
        });
      }

      if ('IntersectionObserver' in window && sections.length) {
        var observer = new IntersectionObserver(function (entries) {
          entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            navLinks.forEach(function (link) {
              link.classList.toggle('is-active', link.getAttribute('href') === '#' + entry.target.id);
            });
          });
        }, { rootMargin: '-20% 0px -70% 0px', threshold: 0 });

        sections.forEach(function (section) { observer.observe(section); });
      }
    });
  </script>
  @endpush
@endif
@endsection
