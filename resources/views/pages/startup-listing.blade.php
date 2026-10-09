@extends('layouts.app')

@section('title')
{{ ucfirst($listingLabel) }} Directory | BusinessX - World Trade Council
@endsection
@section('description')
BusinessX connects businesses, startups, investors and mentors.
@endsection

@section('head')
<style>
    body { font-family: 'Inter', sans-serif; background: var(--gray-50); }

    /* header styles moved to css/common-style.css (shared) */
    /* Hero */
    .listing-hero { background: var(--gray-50); padding: 34px 0 22px; position: relative; overflow: hidden; }
    .listing-hero::before {
      content: ''; position: absolute; inset: 0;
      background-image: radial-gradient(rgba(26,35,50,0.06) 1.5px, transparent 1.5px);
      background-size: 22px 22px;
      mask-image: linear-gradient(to bottom, black 0%, transparent 100%);
      -webkit-mask-image: linear-gradient(to bottom, black 0%, transparent 100%);
    }
    .listing-hero .container { position: relative; z-index: 2; }
    .listing-hero h1 {
      font-size: clamp(28px, 4vw, 42px); font-weight: 700;
      color: var(--navy-700); text-align: center;
      margin-bottom: 12px; letter-spacing: -0.02em;
    }
    .listing-hero .lead {
      font-size: 17px; color: var(--gray-500);
      text-align: center; max-width: 640px;
      margin: 0 auto 18px;
    }
    .hero-search {
      background: var(--white);
      border-radius: 12px;
      padding: 8px;
      display: flex; align-items: center;
      max-width: 920px; margin: 0 auto;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      border: 1px solid var(--gray-200);
    }
    .hero-search .field {
      flex: 1; position: relative;
      padding: 8px 14px 8px 44px;
      border-right: 1px solid var(--gray-200);
    }
    .hero-search .field:last-of-type { border-right: none; }
    .hero-search .field-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--gray-400); }
    .hero-search .field input, .hero-search .field select {
      width: 100%; border: none; background: transparent;
      padding: 4px 0; font-size: 14px;
      font-family: inherit;
    }
    .hero-search .field input:focus, .hero-search .field select:focus { outline: none; }
    .hero-search .field input::placeholder { color: var(--gray-400); }
    .hero-search .search-btn {
      background: var(--navy-700); color: var(--white);
      padding: 14px 28px; border-radius: 8px;
      font-size: 14px; font-weight: 600;
      display: inline-flex; align-items: center; gap: 8px;
      margin-left: 4px;
    }
    .hero-search .search-btn:hover { background: var(--navy-800); }

    /* Stats Bar */
    .hero-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; max-width: 920px; margin: 20px auto 0; }
    .hero-stat { display: flex; align-items: center; gap: 14px; }
    .hero-stat .ic {
      width: 48px; height: 48px; border-radius: 50%;
      background: var(--gray-100); color: var(--navy-700);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .hero-stat .ic svg { width: 22px; height: 22px; }
    .hero-stat .num { font-size: 24px; font-weight: 700; color: var(--navy-700); line-height: 1.2; }
    .hero-stat .lbl { font-size: 12px; color: var(--gray-500); }

    /* Main grid */
    .listing-main { padding: 24px 0; }
    .listing-grid {
      display: grid; grid-template-columns: 280px 1fr;
      gap: 32px; align-items: start;
    }

    /* Sidebar */
    .filter-sidebar {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: 8px;
      padding: 18px;
      position: sticky; top: 90px;
      max-height: calc(100vh - 108px);
      overflow-y: auto;
    }
    .filter-head {
      display: flex; justify-content: space-between; align-items: center;
      padding-bottom: 13px; border-bottom: 1px solid var(--gray-200);
      margin-bottom: 4px;
    }
    .filter-head h3 { font-size: 14px; font-weight: 800; color: var(--navy-700); text-transform: uppercase; letter-spacing: 0.04em; }
    .filter-clear { font-size: 12px; color: var(--gold-600); font-weight: 700; }
    .filter-clear:hover { text-decoration: underline; }

    .filter-section { padding: 0; margin: 0; border-bottom: 1px solid var(--gray-200); }
    .filter-section-head {
      width: 100%; min-height: 48px;
      display: flex; justify-content: space-between; align-items: center;
      cursor: pointer; padding: 10px 0;
      text-align: left; color: var(--gray-800);
    }
    .filter-section-head h4 { font-size: 11px; font-weight: 800; color: var(--gray-800); letter-spacing: 0.045em; text-transform: uppercase; }
    .filter-section-head .chev { width: 14px; height: 14px; color: var(--gray-500); transition: transform 0.2s; }
    .filter-section.open .filter-section-head .chev { transform: rotate(180deg); }
    .filter-section-content { display: none; padding: 0 0 14px; }
    .filter-section.open > .filter-section-content { display: block; }
    .filter-radio-list, .filter-tree-children { display: flex; flex-direction: column; gap: 8px; }
    .filter-item { display: flex; align-items: center; gap: 9px; font-size: 12px; line-height: 1.4; color: var(--gray-700); cursor: pointer; }
    .filter-item input[type="checkbox"], .filter-item input[type="radio"] { width: 15px; height: 15px; margin: 0; accent-color: var(--gold-500); flex-shrink: 0; cursor: pointer; }
    .filter-search {
      width: 100%; height: 36px; margin-bottom: 10px; padding: 0 10px;
      border: 1px solid var(--gray-200); border-radius: 5px;
      color: var(--gray-800); font-size: 12px; background: var(--white);
    }
    .filter-search:focus { outline: 2px solid rgba(19,141,227,.28); border-color: var(--gold-500); }
    .filter-tree { display: flex; flex-direction: column; gap: 1px; }
    .filter-tree-group { border-bottom: 1px solid var(--gray-100); }
    .filter-tree-row { min-height: 36px; display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .filter-tree-row .filter-item { flex: 1; min-width: 0; }
    .filter-tree-toggle { width: 28px; height: 28px; display: grid; place-items: center; color: var(--gray-500); }
    .filter-tree-toggle svg { width: 13px; height: 13px; transition: transform .18s; }
    .filter-tree-group.open .filter-tree-toggle svg { transform: rotate(180deg); }
    .filter-tree-children { display: none; padding: 2px 0 10px 22px; }
    .filter-tree-group.open > .filter-tree-children { display: flex; }
    .filter-tree-children .filter-item { color: var(--gray-600); font-size: 11px; }
    .filter-range { display: grid; gap: 10px; }
    .filter-range input[type="range"] { width: 100%; height: 4px; accent-color: var(--gold-500); cursor: pointer; }
    .filter-range-values { display: flex; justify-content: space-between; color: var(--gray-600); font-size: 11px; font-weight: 600; }

    .apply-filters {
      width: 100%; min-height: 40px; padding: 10px 12px; background: var(--navy-700); color: var(--white);
      border-radius: 5px; font-size: 12px; font-weight: 700; text-transform: uppercase;
      margin-top: 14px; position: sticky; bottom: 0;
    }
    .apply-filters:hover { background: var(--navy-800); }


    /* Listing type pills */
    .type-pills {
      display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
      margin-bottom: 20px;
    }
    .type-pills .tp-label { font-size: 13px; font-weight: 700; color: var(--navy-700); margin-right: 4px; }
    .type-pill {
      padding: 9px 20px; border-radius: 999px;
      border: 1.5px solid var(--gray-200);
      background: var(--white); color: var(--gray-600);
      font-size: 13px; font-weight: 600;
      cursor: pointer; transition: all 0.2s;
    }
    .type-pill:hover { border-color: var(--gold-500); color: var(--gold-600); }
    .type-pill.active { background: var(--navy-800); border-color: var(--navy-800); color: var(--white); }

    /* Results area */
    .results-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
    }
    .results-count { font-size: 14px; color: var(--gray-700); }
    .results-count strong { color: var(--navy-700); font-weight: 700; }
    .results-controls { display: flex; align-items: center; gap: 16px; }
    .sort-dropdown {
      display: flex; align-items: center; gap: 8px;
      font-size: 13px;
    }
    .sort-dropdown select {
      padding: 8px 32px 8px 12px;
      border: 1px solid var(--gray-200);
      border-radius: 6px;
      background: var(--white);
      font-size: 13px;
      font-family: inherit;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='%236B7280'%3E%3Cpath d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 10px center;
      background-size: 10px;
    }
    .view-toggle {
      display: flex; border: 1px solid var(--gray-200); border-radius: 6px; overflow: hidden;
    }
    .view-toggle button {
      width: 36px; height: 32px;
      display: flex; align-items: center; justify-content: center;
      background: var(--white); color: var(--gray-400);
      border: none;
    }
    .view-toggle button.active { background: var(--gold-500); color: var(--white); }

    /* Business cards */
    .biz-list { display: flex; flex-direction: column; gap: 16px; }
    .biz-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: 12px;
      display: grid;
      grid-template-columns: 140px 1fr 240px;
      overflow: hidden;
      transition: all 0.2s ease;
      position: relative;
    }
    .biz-card:hover {
      border-color: var(--gold-300);
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .biz-card-logo {
      background: var(--gray-25);
      display: flex; align-items: center; justify-content: center;
      border-right: 1px solid var(--gray-200);
      position: relative;
    }
    .biz-card-logo img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .meta-action {
      margin-left: auto;
      font-size: 13px; font-weight: 700;
      color: var(--gold-600);
      display: inline-flex; align-items: center; gap: 5px;
    }
    .meta-action:hover { color: var(--gold-500); text-decoration: underline; }
    .biz-card-logo .logo-mark {
      width: 72px; height: 72px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px; font-weight: 800;
      letter-spacing: -0.02em;
      color: var(--white);
    }
    .biz-card-body { padding: 20px; }
    .biz-card-body .row1 {
      display: flex; align-items: center; gap: 8px; margin-bottom: 6px; flex-wrap: wrap;
    }
    .biz-card-body h3 { font-size: 18px; font-weight: 600; color: var(--navy-700); display: inline-flex; align-items: center; gap: 6px; }
    .biz-card-body h3 .verified { color: var(--blue-500); width: 16px; height: 16px; display: inline-flex; }
    .verified-badge {
      display: inline-flex; align-items: center; gap: 4px;
      background: var(--gold-100); color: var(--gold-600);
      padding: 3px 8px; border-radius: 999px;
      font-size: 10px; font-weight: 700;
      letter-spacing: 0.05em; text-transform: uppercase;
    }
    .premium-badge { background: var(--navy-700); color: var(--gold-500); }
    .biz-card-body .location {
      display: flex; align-items: center; gap: 4px;
      font-size: 13px; color: var(--gray-500);
      margin-bottom: 8px;
    }
    .biz-card-body .location svg { width: 13px; height: 13px; }
    .biz-card-body .rating {
      display: flex; align-items: center; gap: 6px;
      font-size: 13px; margin-bottom: 12px;
    }
    .biz-card-body .rating .stars { display: flex; gap: 1px; color: var(--gold-500); }
    .biz-card-body .rating .stars svg { width: 13px; height: 13px; }
    .biz-card-body .rating .score { font-weight: 700; color: var(--navy-700); }
    .biz-card-body .rating .count { color: var(--gray-400); }
    .biz-card-body .desc { font-size: 13px; color: var(--gray-600); line-height: 1.5; margin-bottom: 12px; }
    .biz-card-body .tags { display: flex; flex-wrap: wrap; gap: 6px; }
    .biz-card-body .tag {
      background: var(--gray-100); color: var(--gray-700);
      padding: 3px 10px; border-radius: 999px;
      font-size: 11px; font-weight: 500;
    }

    .biz-card-meta {
      padding: 20px;
      border-left: 1px solid var(--gray-100);
      display: flex; flex-direction: column; gap: 8px;
    }
    .biz-card-meta .meta-row {
      display: flex; align-items: center; gap: 8px;
      font-size: 12px; color: var(--gray-600);
    }
    .biz-card-meta .meta-row svg { width: 14px; height: 14px; color: var(--gray-400); }
    .biz-card-meta .meta-row strong { color: var(--navy-700); font-weight: 600; }
    .biz-card-meta .view-profile {
      margin-top: auto; padding-top: 14px;
    }
    .btn-view-profile {
      display: flex; justify-content: center;
      width: 100%; padding: 10px;
      border: 1px solid var(--gold-500);
      color: var(--gold-600);
      background: transparent;
      border-radius: 6px;
      font-size: 13px; font-weight: 600;
      transition: all 0.2s;
    }
    .btn-view-profile:hover { background: #FFFBEB; }
    .biz-card .heart {
      position: absolute; top: 14px; right: 14px;
      width: 32px; height: 32px; border-radius: 50%;
      background: var(--gray-100); color: var(--gray-400);
      display: flex; align-items: center; justify-content: center;
      z-index: 2;
    }
    .biz-card .heart:hover { color: var(--red-500); background: var(--gray-200); }
    .biz-card .heart svg { width: 16px; height: 16px; }

    /* Pagination */
    .pagination {
      display: flex; justify-content: center; gap: 6px;
      margin-top: 40px;
    }
    .pagination a {
      min-width: 40px; height: 40px; padding: 0 12px;
      border: 1px solid var(--gray-200); border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 500; color: var(--gray-700);
      background: var(--white);
    }
    .pagination a:hover { border-color: var(--navy-700); }
    .pagination a.active {
      background: var(--navy-700); border-color: var(--navy-700);
      color: var(--white);
    }
    .pagination a.ellipsis { border: none; background: transparent; }

    /* CTA banner */
    .cta-banner {
      background: var(--navy-900);
      color: var(--white);
      border-radius: 12px;
      padding: 36px 40px;
      margin-top: 48px;
      display: flex; align-items: center; justify-content: space-between;
      gap: 24px; flex-wrap: wrap;
      position: relative; overflow: hidden;
    }
    .cta-banner::before {
      content: ''; position: absolute; inset: 0;
      background-image: radial-gradient(rgba(255,255,255,0.08) 1.5px, transparent 1.5px);
      background-size: 20px 20px;
    }
    .cta-banner .content { display: flex; align-items: center; gap: 20px; position: relative; z-index: 2; }
    .cta-banner .ic {
      width: 56px; height: 56px; border-radius: 50%;
      border: 2px solid var(--gold-500);
      color: var(--gold-500);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .cta-banner .ic svg { width: 24px; height: 24px; }
    .cta-banner h3 { font-size: 22px; font-weight: 700; color: var(--white); margin-bottom: 4px; }
    .cta-banner p { font-size: 14px; color: rgba(255,255,255,0.7); }
    .cta-banner .btn-gold {
      background: var(--gold-500); color: var(--navy-900);
      padding: 14px 24px; border-radius: 8px;
      font-size: 14px; font-weight: 700;
      position: relative; z-index: 2;
    }
    .cta-banner .btn-gold:hover { background: var(--gold-600); }

    /* Footer (reuse) */
    .newsletter-form { display: flex; }
    .newsletter-form input {
      flex: 1; padding: 10px 14px;
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.2);
      color: var(--white); font-size: 13px;
      border-radius: 6px 0 0 6px;
    }
    .newsletter-form input::placeholder { color: rgba(255,255,255,0.5); }
    .newsletter-form button {
      background: var(--gold-500); color: var(--navy-900);
      padding: 10px 14px;
      border-radius: 0 6px 6px 0;
      font-weight: 700; display: flex; align-items: center; justify-content: center;
    }
    @media (max-width: 1024px) {
      .bx-nav { display: none; }
      .listing-grid { grid-template-columns: 1fr; }
      .filter-sidebar { position: static; max-height: none; overflow: visible; }
      .hero-stats { grid-template-columns: repeat(2, 1fr); }
      .biz-card { grid-template-columns: 100px 1fr; }
      .biz-card-meta { grid-column: 1 / -1; border-left: none; border-top: 1px solid var(--gray-100); }
    }
    @media (max-width: 640px) {
      .hero-search { flex-direction: column; padding: 12px; gap: 8px; }
      .hero-search .field { border-right: none; border-bottom: 1px solid var(--gray-200); padding: 8px 14px 8px 36px; }
      .hero-search .field:last-of-type { border-bottom: none; }
      .hero-search .field-icon { left: 12px; }
      .hero-search .search-btn { margin-left: 0; width: 100%; justify-content: center; }
      .hero-stats { grid-template-columns: 1fr; }
      .biz-card { grid-template-columns: 1fr; }
      .biz-card-logo { border-right: none; border-bottom: 1px solid var(--gray-200); padding: 16px; }
    }
  </style>
@endsection

@section('content')


  
  

  <!-- Hero -->
  <section class="listing-hero">
    <div class="container">
      <h1>Discover {{ ucfirst($listingLabel) }} Across the Network</h1>
      <p class="lead">Browse active {{ $listingLabel }} and narrow the results by location, industry, keyword, and amount.</p>
      <form class="hero-search" method="GET" action="{{ route('startup-listing') }}">
        <div class="field">
          <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, company, or keyword...">
        </div>
        <div class="field">
          <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M6 12h18M9 18h18"/></svg>
          <select name="industry">
            <option value="">All Industries</option>
            @foreach ($industryCategoryTree ?? [] as $group)
              @foreach ($group['children'] as $child)
                <option value="{{ $child['id'] }}" @selected((string) request('industry') === (string) $child['id'])>{{ $child['name'] }}</option>
              @endforeach
            @endforeach
          </select>
        </div>
        <div class="field">
          <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <input type="text" name="city" value="{{ request('city') }}" placeholder="Location">
        </div>
        <button type="submit" class="search-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          Search
        </button>
      </form>
      <div class="hero-stats">
        <div class="hero-stat">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg></span>
          <div><div class="num">{{ number_format($listings->total()) }}</div><div class="lbl">{{ ucfirst($listingLabel) }} Listed</div></div>
        </div>
        <div class="hero-stat">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/></svg></span>
          <div><div class="num">120+</div><div class="lbl">Countries</div></div>
        </div>
        <div class="hero-stat">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></span>
          <div><div class="num">23,450</div><div class="lbl">Verified Members</div></div>
        </div>
        <div class="hero-stat">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></span>
          <div><div class="num">8,650</div><div class="lbl">Premium Members</div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Main -->
  <section class="listing-main">
    <div class="container">
      <div class="listing-grid">

        <!-- Sidebar -->
        <aside class="filter-sidebar">
          <div class="filter-head">
            <h3>Filters</h3>
            <button type="button" class="filter-clear">Clear All</button>
          </div>

          <section class="filter-section open" data-filter-section>
            <button type="button" class="filter-section-head" aria-expanded="true">
              <h4>Startups Looking for</h4>
              <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="filter-section-content">
              <div class="filter-radio-list" role="radiogroup" aria-label="Startups looking for">
                <label class="filter-item"><input type="radio" name="startupIntent" value="all" checked> All</label>
                <label class="filter-item"><input type="radio" name="startupIntent" value="sale">Buyer</label>
                <label class="filter-item"><input type="radio" name="startupIntent" value="investor"> Investor</label>
                <label class="filter-item"><input type="radio" name="startupIntent" value="loan">Lender</label>
                <label class="filter-item"><input type="radio" name="startupIntent" value="mentor">Mentorship</label>
                <label class="filter-item"><input type="radio" name="startupIntent" value="incubator"> Incubators / Accelerators</label>
              </div>
            </div>
          </section>

          <section class="filter-section" data-filter-section>
            <button type="button" class="filter-section-head" aria-expanded="false">
              <h4>Investment Size</h4>
              <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="filter-section-content">
              <div class="filter-range">
                <label class="filter-item" for="annual-sales-min">Minimum funding amount</label>
                <input id="annual-sales-min" type="range" min="0" max="1000000000" step="1000000" value="{{ request('min_amount', 0) }}" aria-label="Minimum funding amount">
                <label class="filter-item" for="annual-sales-max">Maximum funding amount</label>
                <input id="annual-sales-max" type="range" min="0" max="1000000000" step="1000000" value="{{ request('max_amount', 1000000000) }}" aria-label="Maximum funding amount">
                <div class="filter-range-values"><span id="annual-sales-min-value">£0</span><span id="annual-sales-max-value">£1,000,000,000</span></div>
              </div>
            </div>
          </section>

          <section class="filter-section" data-filter-section>
            <button type="button" class="filter-section-head" aria-expanded="false">
              <h4>Location</h4>
              <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="filter-section-content">
              <input class="filter-search" type="search" placeholder="Search city or state..." aria-label="Search city or state" data-filter-search="location">
              <div class="filter-tree" id="location-filter-tree"></div>
            </div>
          </section>

          <section class="filter-section" data-filter-section>
            <button type="button" class="filter-section-head" aria-expanded="false">
              <h4>Industries</h4>
              <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="filter-section-content">
              <input class="filter-search" type="search" placeholder="Search industry..." aria-label="Search industries" data-filter-search="industry">
              <div class="filter-tree" id="industry-filter-tree"></div>
            </div>
          </section>

          <button class="apply-filters">Apply Filters</button>
        </aside>

        <!-- Results -->
        <div class="results-area">
          @php($listingRoutes = ['business' => 'business-listing', 'investor' => 'investor-listing', 'startup' => 'startup-listing', 'mentor' => 'mentor-listing'])
          <nav class="type-pills" aria-label="Listing categories">
            <span class="tp-label">Browse:</span>
            @foreach ($listingRoutes as $type => $routeName)
              <a class="type-pill {{ $listingType === $type ? 'active' : '' }}" href="{{ route($routeName, request()->query()) }}">{{ ucfirst($type) }}</a>
            @endforeach
          </nav>
          <div class="results-header">
            <div class="results-count">Showing <strong>{{ $listings->firstItem() ?? 0 }} – {{ $listings->lastItem() ?? 0 }}</strong> of <strong>{{ number_format($listings->total()) }}</strong> {{ $listingLabel }}</div>
            <div class="results-controls">
              <div class="sort-dropdown">
                <label>Sort By:</label>
                <select class="listing-sort" aria-label="Sort listings">
                  <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest First</option>
                  <option value="oldest" @selected(request('sort') === 'oldest')>Oldest First</option>
                  <option value="name_asc" @selected(request('sort') === 'name_asc')>Alphabetical</option>
                  <option value="amount_asc" @selected(request('sort') === 'amount_asc')>Amount: Low to High</option>
                  <option value="amount_desc" @selected(request('sort') === 'amount_desc')>Amount: High to Low</option>
                </select>
              </div>
              <div class="view-toggle">
                <button title="Grid View"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></button>
                <button class="active" title="List View"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></button>
              </div>
            </div>
          </div>

          <div class="biz-list">
            @include('pages.listing-cards')
          </div>

          <!-- Pagination -->
          @include('pages.listing-pagination')

          <!-- CTA banner -->
          <div class="cta-banner">
            <div class="content">
              <span class="ic">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a.73.73 0 0 1-.65.06A7.5 7.5 0 0 1 12 15z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>
              </span>
              <div>
                <h3>Grow with BusinessX</h3>
                <p>Join the BusinessX network and connect with verified {{ $listingLabel }}.</p>
              </div>
            </div>
            <a href="{{ route($listingType . '-registration') }}" class="btn-gold">Join as {{ ucfirst($listingType) }}</a>
          </div>

        </div>
      </div>
    </div>
  </section>

  

  <script>
    const filterTreeData = {
      location: [{ name: 'United Kingdom', children: @json($ukCities ?? []) }],
      industry: @json($industryCategoryTree ?? [])
    };

    function escapeFilterText(value) {
      return value.replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[character]);
    }

    function renderFilterTree(type) {
      const tree = document.getElementById(type + '-filter-tree');
      const groups = filterTreeData[type] || [];
      tree.innerHTML = groups.map((group, index) => {
        const groupName = group.name || group.label || '';
        const groupId = type + '-group-' + index;
        const children = (group.children || []).map(child => {
          const childName = typeof child === 'string' ? child : child.name;
          const childId = typeof child === 'string' ? '' : (child.id || '');
          return '<label class="filter-item"><input class="filter-tree-child" type="checkbox" value="' + escapeFilterText(childName) + '" data-category-id="' + childId + '"> ' + escapeFilterText(childName) + '</label>';
        }).join('');
        return '<div class="filter-tree-group" data-filter-group>' +
          '<div class="filter-tree-row"><label class="filter-item"><input class="filter-tree-parent" type="checkbox" value="' + escapeFilterText(groupName) + '" data-category-id="' + (group.id || '') + '"> ' + escapeFilterText(groupName) + '</label>' +
          '<button type="button" class="filter-tree-toggle" aria-expanded="false" aria-controls="' + groupId + '" aria-label="Expand ' + escapeFilterText(groupName) + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></button></div>' +
          '<div class="filter-tree-children" id="' + groupId + '">' + children + '</div></div>';
      }).join('');
    }

    renderFilterTree('location');
    renderFilterTree('industry');

    document.querySelectorAll('.filter-section-head').forEach(button => {
      button.addEventListener('click', () => {
        const section = button.closest('.filter-section');
        const open = section.classList.toggle('open');
        button.setAttribute('aria-expanded', String(open));
      });
    });

    document.querySelectorAll('.filter-tree-toggle').forEach(button => {
      button.addEventListener('click', () => {
        const group = button.closest('.filter-tree-group');
        const open = group.classList.toggle('open');
        button.setAttribute('aria-expanded', String(open));
        button.setAttribute('aria-label', `${open ? 'Collapse' : 'Expand'} ${group.querySelector('.filter-tree-parent').value}`);
      });
    });

    document.querySelectorAll('.filter-tree-parent').forEach(parent => {
      parent.addEventListener('change', () => {
        parent.closest('.filter-tree-group').querySelectorAll('.filter-tree-child').forEach(child => {
          child.checked = parent.checked;
        });
        parent.indeterminate = false;
      });
    });

    document.querySelectorAll('.filter-tree-child').forEach(child => {
      child.addEventListener('change', () => {
        const group = child.closest('.filter-tree-group');
        const parent = group.querySelector('.filter-tree-parent');
        const children = [...group.querySelectorAll('.filter-tree-child')];
        const checkedCount = children.filter(item => item.checked).length;
        parent.checked = checkedCount === children.length;
        parent.indeterminate = checkedCount > 0 && checkedCount < children.length;
      });
    });

    document.querySelectorAll('[data-filter-search]').forEach(search => {
      search.addEventListener('input', () => {
        const query = search.value.trim().toLowerCase();
        search.nextElementSibling.querySelectorAll('.filter-tree-group').forEach(group => {
          const parentLabel = group.querySelector('.filter-tree-parent').value.toLowerCase();
          const children = [...group.querySelectorAll('.filter-tree-child')];
          const parentMatches = parentLabel.includes(query);
          const matchingChildren = children.filter(child => child.value.toLowerCase().includes(query));
          group.hidden = Boolean(query) && !parentMatches && matchingChildren.length === 0;
          children.forEach(child => {
            child.closest('.filter-item').hidden = Boolean(query) && !parentMatches && !child.value.toLowerCase().includes(query);
          });
          if (query && matchingChildren.length) {
            group.classList.add('open');
            group.querySelector('.filter-tree-toggle').setAttribute('aria-expanded', 'true');
          }
        });
      });
    });

    const params = new URLSearchParams(window.location.search);
    const splitParam = key => (params.get(key) || '').split(',').map(value => value.trim()).filter(Boolean);

    const cityName = params.get('city');
    const selectedCities = (cityName || '').split(',').map(value => value.trim().toLowerCase()).filter(Boolean);
    document.querySelectorAll('#location-filter-tree .filter-tree-child').forEach(input => {
      input.checked = selectedCities.includes(input.value.trim().toLowerCase());
    });
    const selectedIndustries = splitParam('industry');
    const selectedParents = splitParam('industry_parent');
    document.querySelectorAll('#industry-filter-tree .filter-tree-child').forEach(input => {
      input.checked = selectedIndustries.includes(input.dataset.categoryId);
    });
    document.querySelectorAll('#industry-filter-tree .filter-tree-parent').forEach(input => {
      const childInputs = [...input.closest('.filter-tree-group').querySelectorAll('.filter-tree-child')];
      if (selectedParents.includes(input.dataset.categoryId)) {
        input.checked = true;
        childInputs.forEach(child => { child.checked = true; });
      } else {
        const selectedCount = childInputs.filter(child => child.checked).length;
        input.checked = childInputs.length > 0 && selectedCount === childInputs.length;
        input.indeterminate = selectedCount > 0 && selectedCount < childInputs.length;
      }
      if (input.checked || input.indeterminate) {
        const group = input.closest('.filter-tree-group');
        group.classList.add('open');
        group.querySelector('.filter-tree-toggle').setAttribute('aria-expanded', 'true');
      }
    });

    const intentInput = document.querySelector('input[name="businessIntent"], input[name="startupIntent"], input[name="investorType"]');
    if (intentInput) {
      const intent = params.get('intent') || 'all';
      const selectedIntent = document.querySelector('input[name="' + intentInput.name + '"][value="' + intent + '"]');
      if (selectedIntent) selectedIntent.checked = true;
    }

    const minimumAmount = document.getElementById('annual-sales-min');
    const maximumAmount = document.getElementById('annual-sales-max');
    const formatAmount = value => '£' + Number(value).toLocaleString('en-GB');
    const updateAmountLabels = () => {
      document.getElementById('annual-sales-min-value').textContent = formatAmount(minimumAmount.value);
      document.getElementById('annual-sales-max-value').textContent = formatAmount(maximumAmount.value);
    };
    minimumAmount.addEventListener('input', updateAmountLabels);
    maximumAmount.addEventListener('input', updateAmountLabels);
    updateAmountLabels();

    const sortSelect = document.querySelector('.listing-sort');
    if (sortSelect) sortSelect.addEventListener('change', () => {
      const url = new URL(window.location.href);
      url.searchParams.set('sort', sortSelect.value);
      url.searchParams.delete('page');
      window.location.href = url.toString();
    });

    document.querySelector('.apply-filters').addEventListener('click', () => {
      const url = new URL(window.location.href);
      const params = url.searchParams;
      params.delete('page');
      const query = document.querySelector('.hero-search input[name="q"]')?.value.trim() || '';
      const heroCity = document.querySelector('.hero-search input[name="city"]')?.value.trim() || '';
      const heroIndustry = document.querySelector('.hero-search select[name="industry"]')?.value || '';
      if (query) params.set('q', query); else params.delete('q');

      const cities = [...document.querySelectorAll('#location-filter-tree .filter-tree-child:checked')].map(input => input.value);
      const selectedCityValues = cities.length ? cities : (heroCity ? [heroCity] : []);
      if (selectedCityValues.length) params.set('city', selectedCityValues.join(',')); else params.delete('city');

      const categories = [...document.querySelectorAll('#industry-filter-tree .filter-tree-child:checked')]
        .map(input => input.dataset.categoryId).filter(Boolean);
      if (!categories.length && heroIndustry) categories.push(heroIndustry);
      if (categories.length) params.set('industry', [...new Set(categories)].join(',')); else params.delete('industry');
      const parents = [...document.querySelectorAll('#industry-filter-tree .filter-tree-parent:checked')]
        .map(input => input.dataset.categoryId).filter(Boolean);
      if (parents.length) params.set('industry_parent', [...new Set(parents)].join(',')); else params.delete('industry_parent');

      const checkedIntent = document.querySelector('input[name="businessIntent"]:checked, input[name="startupIntent"]:checked, input[name="investorType"]:checked');
      if (checkedIntent && checkedIntent.value !== 'all') params.set('intent', checkedIntent.value); else params.delete('intent');
      const minimum = document.getElementById('annual-sales-min');
      const maximum = document.getElementById('annual-sales-max');
      if (minimum && Number(minimum.value) > Number(minimum.min)) params.set('min_amount', minimum.value); else params.delete('min_amount');
      if (maximum && Number(maximum.value) < Number(maximum.max)) params.set('max_amount', maximum.value); else params.delete('max_amount');
      window.location.href = url.toString();
    });

    document.querySelector('.filter-clear').addEventListener('click', () => {
      window.location.href = window.location.pathname;
    });
  </script>

@endsection
