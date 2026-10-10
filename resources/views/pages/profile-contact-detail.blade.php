@extends('layouts.app')

@section('title', $profile->display_title . ' | BusinessX')
@section('description', \Illuminate\Support\Str::limit($profile->display_summary ?: $profile->display_title, 155))

@section('head')
<style>
  .public-profile-page { min-height: 65vh; padding: 34px 0 64px; background: var(--gray-50); }
  .public-profile-page .profile-breadcrumb { margin-bottom: 18px; color: var(--gray-500); font-size: 12px; }
  .public-profile-page .profile-breadcrumb a { color: var(--gold-600); }
  .public-profile-page .profile-detail-grid { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 22px; align-items: start; }
  .public-profile-page .profile-detail-main, .public-profile-page .profile-contact-card { padding: 24px; border: 1px solid var(--gray-200); border-radius: 9px; background: var(--white); }
  .public-profile-page .profile-hero-banner { width: 100%; height: 320px; margin: -24px -24px 22px; border-radius: 9px 9px 0 0; object-fit: contain; display: block; background: var(--gray-100); }
  .public-profile-page .profile-detail-heading { display: flex; align-items: flex-start; gap: 20px; padding-bottom: 22px; border-bottom: 1px solid var(--gray-100); }
  .public-profile-page .profile-detail-heading img { width: 112px; height: 112px; border-radius: 8px; object-fit: cover; background: var(--gray-100); }
  .public-profile-page .profile-type-label { color: var(--gold-600); font-size: 12px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
  .public-profile-page h1 { margin: 6px 0 8px; color: var(--navy-900); font-size: clamp(24px, 3.5vw, 34px); line-height: 1.2; }
  .public-profile-page .profile-location, .public-profile-page .profile-summary { color: var(--gray-600); font-size: 14px; line-height: 1.65; }
  .public-profile-page .profile-detail-main h2 { margin: 24px 0 12px; color: var(--navy-900); font-size: 18px; }
  .public-profile-page .profile-detail-section { margin-top: 22px; padding: 20px 22px; border: 1px solid var(--gray-200); border-radius: 10px; background: linear-gradient(180deg, var(--gray-50), var(--white) 60px); }
  .public-profile-page .profile-detail-section .profile-info-grid { margin-top: 2px; }
  .public-profile-page .profile-detail-section .profile-info:last-child,
  .public-profile-page .profile-detail-section .profile-info:nth-last-child(2):nth-child(odd) { border-bottom: none; }
  .public-profile-page .profile-section-heading { display: flex; align-items: center; gap: 10px; margin: 0 0 6px; color: var(--navy-900); font-size: 16px; }
  .public-profile-page .profile-section-number { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: var(--gold-500); color: var(--navy-900); font-size: 13px; font-weight: 800; flex-shrink: 0; }
  .public-profile-page .profile-info-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 22px; }
  .public-profile-page .profile-info { padding: 13px 0; border-bottom: 1px solid var(--gray-100); overflow-wrap: anywhere; }
  .public-profile-page .profile-info dt { color: var(--gray-500); font-size: 12px; }
  .public-profile-page .profile-info dd { margin-top: 4px; color: var(--navy-800); font-size: 14px; font-weight: 600; }
  .public-profile-page .profile-info-locked { color: var(--gray-500); font-weight: 500; font-style: italic; font-size: 13px; }
  .public-profile-page .profile-info a { color: var(--gold-600); text-decoration: underline; }
  .public-profile-page .profile-contact-card { position: sticky; top: 20px; }
  .public-profile-page .profile-contact-card h2 { color: var(--navy-900); font-size: 19px; }
  .public-profile-page .profile-contact-card p { margin-top: 7px; color: var(--gray-600); font-size: 13px; line-height: 1.6; }
  .public-profile-page .profile-contact-button { display: flex; justify-content: center; width: 100%; margin-top: 18px; padding: 13px 16px; border: 1px solid var(--navy-900); border-radius: 7px; background: var(--white); color: var(--navy-900); font: inherit; font-weight: 700; cursor: pointer; }
  .public-profile-page .profile-contact-button:hover { background: var(--navy-900); color: var(--white); }
  .profile-contact-dialog { width: min(520px, calc(100% - 28px)); max-height: calc(100% - 28px); padding: 0; border: 0; border-radius: 10px; box-shadow: 0 20px 70px rgba(20,45,82,.28); }
  .profile-contact-dialog::backdrop { background: rgba(20,45,82,.58); }
  .profile-contact-dialog .dialog-body { padding: 24px; }
  .profile-contact-dialog h2 { color: var(--navy-900); font-size: 21px; }
  .profile-contact-dialog .dialog-intro { margin: 6px 0 18px; color: var(--gray-600); font-size: 13px; }
  .profile-contact-dialog .dialog-fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 13px; }
  .profile-contact-dialog .dialog-field { display: grid; gap: 6px; }
  .profile-contact-dialog .dialog-field.full { grid-column: 1 / -1; }
  .profile-contact-dialog label { color: var(--gray-700); font-size: 12px; font-weight: 600; }
  .profile-contact-dialog input, .profile-contact-dialog textarea { width: 100%; padding: 10px; border: 1px solid var(--gray-300); border-radius: 5px; color: var(--gray-800); font: inherit; font-size: 13px; }
  .profile-contact-dialog textarea { min-height: 110px; resize: vertical; }
  .profile-contact-dialog .dialog-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px; }
  .profile-contact-dialog .dialog-actions button { min-height: 40px; padding: 0 15px; border: 1px solid var(--gray-300); border-radius: 5px; background: var(--white); color: var(--gray-700); font: inherit; font-size: 13px; font-weight: 700; cursor: pointer; }
  .profile-contact-dialog .dialog-actions button[type=submit] { border-color: var(--gold-500); background: var(--gold-500); color: var(--navy-900); }
  @media (max-width: 760px) {
    .public-profile-page .profile-detail-grid { grid-template-columns: 1fr; }
    .public-profile-page .profile-contact-card { position: static; }
  }
  @media (max-width: 540px) {
    .public-profile-page .profile-detail-main { padding: 18px; }
    .public-profile-page .profile-hero-banner { height: 200px; margin: -18px -18px 18px; }
    .public-profile-page .profile-detail-heading { gap: 13px; }
    .public-profile-page .profile-detail-heading img { width: 78px; height: 78px; }
    .public-profile-page .profile-info-grid, .profile-contact-dialog .dialog-fields { grid-template-columns: 1fr; }
    .profile-contact-dialog .dialog-field.full { grid-column: auto; }
  }
</style>
@endsection

@section('content')
<main class="public-profile-page">
  <div class="container">
    <nav class="profile-breadcrumb" aria-label="Breadcrumb">
      <a href="{{ route('home') }}">Home</a> / <a href="{{ route($profileType . '-listing') }}">{{ $profileLabel }} listings</a> / <span>{{ $profile->display_title }}</span>
    </nav>
    <div class="profile-detail-grid">
      <article class="profile-detail-main">
        @php $useHeroBanner = in_array($profileType, ['business', 'startup'], true); @endphp
        @if ($useHeroBanner)
          <img class="profile-hero-banner" src="{{ $profile->display_image }}" alt="{{ $profile->display_title }}">
        @endif
        <header class="profile-detail-heading">
          @unless ($useHeroBanner)
            <img src="{{ $profile->display_image }}" alt="{{ $profile->display_title }}">
          @endunless
          <div>
            <span class="profile-type-label">{{ $profileLabel }} profile</span>
            <h1>{{ $profile->display_title }}</h1>
            @if ($profile->display_location)
              <p class="profile-location">{{ $profile->display_location }}</p>
            @endif
          </div>
        </header>
        @if ($profile->display_summary)
          <section>
            <h2>Profile overview</h2>
            <p class="profile-summary">{{ $profile->display_summary }}</p>
          </section>
        @endif
        @foreach ($profileSections as $sectionIndex => $section)
          <section class="profile-detail-section">
            <h2 class="profile-section-heading">
              <span class="profile-section-number">{{ $sectionIndex + 1 }}</span>
              {{ $section['title'] }}
            </h2>
            <dl class="profile-info-grid">
              @foreach ($section['items'] as $item)
                <div class="profile-info">
                  <dt>{{ $item['label'] }}</dt>
                  <dd>
                    @if ($item['locked'] ?? false)
                      <span class="profile-info-locked" title="Visible once the profile owner replies to your message">🔒 Available after Interaction</span>
                    @elseif ($item['is_url'])
                      <a href="{{ $item['value'] }}" target="_blank" rel="noopener noreferrer">{{ $item['value'] }}</a>
                    @else
                      {{ $item['value'] }}
                    @endif
                  </dd>
                </div>
              @endforeach
            </dl>
          </section>
        @endforeach
        @if ($profileItems !== [])
          <section>
            <h2>{{ $profileLabel }} details</h2>
            <dl class="profile-info-grid">
              @foreach ($profileItems as $item)
                <div class="profile-info">
                  <dt>{{ $item['label'] }}</dt>
                  <dd>
                    @if ($item['locked'] ?? false)
                      <span class="profile-info-locked" title="Visible once the profile owner replies to your message">🔒 Available after Interaction</span>
                    @elseif ($item['is_url'])
                      <a href="{{ $item['value'] }}" target="_blank" rel="noopener noreferrer">{{ $item['value'] }}</a>
                    @else
                      {{ $item['value'] }}
                    @endif
                  </dd>
                </div>
              @endforeach
            </dl>
          </section>
        @endif
      </article>

      <aside class="profile-contact-card">
        <h2>Interested in this {{ strtolower($profileLabel) }}?</h2>
        <p>Send a private message to the profile owner. Your message and replies will be available in your BusinessX inbox.</p>
        @if (!$isOwner && !$isUnlocked)
          <p class="profile-info-locked">🔒 This profile owner's contact details stay hidden until they reply to your message.</p>
        @endif
        @if ($canContact)
          @auth
            <button class="profile-contact-button" type="button" data-open-contact>Contact {{ $profileLabel }}</button>
          @else
            <a class="profile-contact-button" href="{{ route('profile-contact.start', ['type' => $profileType, 'id' => $profileId]) }}">Sign in to contact</a>
          @endauth
        @else
          <p>This is your profile. You cannot send a contact request to yourself.</p>
        @endif
      </aside>
    </div>
  </div>
</main>

@auth
  @if ($canContact)
    <dialog class="profile-contact-dialog" id="profile-contact-dialog" aria-labelledby="profile-contact-title">
      <form class="dialog-body" method="post" action="{{ route('profile-contact.store', ['type' => $profileType, 'id' => $profileId]) }}">
        @csrf
        <h2 id="profile-contact-title">Contact {{ $profileLabel }}</h2>
        <p class="dialog-intro">Your message will be sent privately to the profile owner.</p>
        <div class="dialog-fields">
          <div class="dialog-field">
            <label for="contact-name">Your name</label>
            <input id="contact-name" name="name" type="text" maxlength="255" value="{{ old('name', $contactDefaults['name']) }}" required>
          </div>
          <div class="dialog-field">
            <label for="contact-email">Email</label>
            <input id="contact-email" name="email" type="email" maxlength="255" value="{{ old('email', $contactDefaults['email']) }}" required>
          </div>
          <div class="dialog-field full">
            <label for="contact-phone">Phone</label>
            <div class="phone-input-group">
              @include('components.phone-country-code')
              <input id="contact-phone" name="phone" type="tel" maxlength="30" value="{{ old('phone', $contactDefaults['phone']) }}">
            </div>
          </div>
          <div class="dialog-field full">
            <label for="contact-message">Message</label>
            <textarea id="contact-message" name="message" maxlength="10000" required>{{ old('message') }}</textarea>
          </div>
        </div>
        @if ($errors->any())
          <div role="alert" style="margin-top:12px;color:#b91c1c;font-size:13px;">{{ $errors->first() }}</div>
        @endif
        <div class="dialog-actions">
          <button type="button" data-close-contact>Cancel</button>
          <button type="submit">Send message</button>
        </div>
      </form>
    </dialog>
  @endif
@endauth
@endsection

@push('scripts')
<script>
  const contactDialog = document.getElementById('profile-contact-dialog');
  document.querySelector('[data-open-contact]')?.addEventListener('click', () => contactDialog?.showModal());
  document.querySelector('[data-close-contact]')?.addEventListener('click', () => contactDialog?.close());
  @if (($errors->any() || session('open_contact')) && auth()->check() && $canContact)
    contactDialog?.showModal();
  @endif
</script>
@endpush
