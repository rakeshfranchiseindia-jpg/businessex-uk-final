<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Business Directory | BusinessX - World Trade Council</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/common-style.css?v=20261002">
  <style>
    body { font-family: 'Inter', sans-serif; background: var(--gray-50); }

    /* header styles moved to css/common-style.css (shared) */
    /* Hero */
    .listing-hero { background: var(--gray-50); padding: 60px 0 50px; position: relative; overflow: hidden; }
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
      margin: 0 auto 32px;
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
    .hero-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; max-width: 920px; margin: 40px auto 0; }
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
    .listing-main { padding: 48px 0; }
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
    .filter-search:focus { outline: 2px solid rgba(229,166,35,.28); border-color: var(--gold-500); }
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
</head>
<body>

  <?php $page = 'listing'; ?>
  <?php include __DIR__ . "/includes/header.php"; ?>

  <!-- Hero -->
  <section class="listing-hero">
    <div class="container">
      <h1>Discover Trusted Businesses Across The World</h1>
      <p class="lead">Find verified companies, suppliers, exporters, service providers and franchise opportunities.</p>
      <form class="hero-search" onsubmit="event.preventDefault();">
        <div class="field">
          <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" placeholder="Company, Product, Service...">
        </div>
        <div class="field">
          <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M6 12h18M9 18h18"/></svg>
          <select><option>All Industries</option><option>Manufacturing</option><option>Technology</option><option>Agriculture</option></select>
        </div>
        <div class="field">
          <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <input type="text" placeholder="Location">
        </div>
        <button type="submit" class="search-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          Search
        </button>
      </form>
      <div class="hero-stats">
        <div class="hero-stat">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg></span>
          <div><div class="num">25,630</div><div class="lbl">Businesses Listed</div></div>
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
              <h4>Investor Type</h4>
              <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="filter-section-content">
              <div class="filter-radio-list" role="radiogroup" aria-label="Investor type">
                <label class="filter-item"><input type="radio" name="investorType" value="all" checked> Individual Investor</label>
                <label class="filter-item"><input type="radio" name="investorType" value="firm">  Investment Firm</label>
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
                <label class="filter-item" for="annual-sales-min">Minimum annual sales</label>
                <input id="annual-sales-min" type="range" min="0" max="1000000000" step="1000000" value="0" aria-label="Minimum annual sales">
                <label class="filter-item" for="annual-sales-max">Maximum annual sales</label>
                <input id="annual-sales-max" type="range" min="0" max="1000000000" step="1000000" value="1000000000" aria-label="Maximum annual sales">
                <div class="filter-range-values"><span id="annual-sales-min-value">0.00 cr</span><span id="annual-sales-max-value">100.00 cr</span></div>
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
          <div class="type-pills" id="typePills">
            <span class="tp-label">Show:</span>
            <button class="type-pill active" data-type="all">All Listings</button>
            <button class="type-pill" data-type="business">Business</button>
            <button class="type-pill" data-type="investor">Investor</button>
            <button class="type-pill" data-type="startup">Startup</button>
            <button class="type-pill" data-type="mentor">Mentor</button>
          </div>
          <div class="results-header">
            <div class="results-count">Showing <strong>1 – 12</strong> of <strong>25,630</strong> businesses</div>
            <div class="results-controls">
              <div class="sort-dropdown">
                <label>Sort By:</label>
                <select>
                  <option>Most Relevant</option>
                  <option>Highest Rated</option>
                  <option>Newest First</option>
                  <option>Alphabetical</option>
                </select>
              </div>
              <div class="view-toggle">
                <button title="Grid View"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></button>
                <button class="active" title="List View"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></button>
              </div>
            </div>
          </div>

          <div class="biz-list">

            <!-- Card 1 -->
            <article class="biz-card" data-type="business" data-intent="investor" data-annual-sales="40">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <div class="logo-mark" style="background: linear-gradient(135deg, #1F2937, #4B5563);">KT</div>
              </div>
              <div class="biz-card-body">
                <div class="row1">
                  <span class="verified-badge">Business Opportunity</span>
                </div>
                <h3>Kreate Technologies pvt ltd</h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Nagoya</div>
                <p class="desc">I would love to schedule a brief call to share how we can support your goals.</p>
                <div class="tags">
                  <span class="tag">Ecommerce websites</span>
                  <span class="tag">B2C</span>
                </div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Established <strong>2008</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Employees <strong>1</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/></svg> Seeking <strong>INR 2,000,000</strong></div>
                <div class="view-profile">
                  <a href="profile-details.php" class="btn-view-profile">View Profile</a>
                </div>
              </div>
            </article>

            <!-- Card 2 -->
            <article class="biz-card" data-type="business">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <div class="logo-mark" style="background: linear-gradient(135deg, #2563EB, #3B82F6);">XYZ</div>
              </div>
              <div class="biz-card-body">
                <div class="row1"><span class="verified-badge">Verified Member</span></div>
                <h3>XYZ Software Solutions
                  <span class="verified" title="Verified">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2 4 4-2-2 4 4 2-4 2 2 4-4-2-2 4-2-4-4 2 2-4-4-2 4-2-2-4 4 2z"/></svg>
                  </span>
                </h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Manchester, United Kingdom</div>
                <div class="rating">
                  <span class="stars">
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                  </span>
                  <span class="score">4.8</span>
                  <span class="count">(98 Reviews)</span>
                </div>
                <p class="desc">Providing innovative software solutions, AI development and IT consulting services worldwide.</p>
                <div class="tags">
                  <span class="tag">Technology</span>
                  <span class="tag">Software Development</span>
                </div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Established <strong>2012</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Employees <strong>150+</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Export to <strong>25+ Countries</strong></div>
                <div class="view-profile">
                  <a href="#" class="btn-view-profile">View Profile</a>
                </div>
              </div>
            </article>

            <!-- Card 3 -->
            <article class="biz-card" data-type="business">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <div class="logo-mark" style="background: linear-gradient(135deg, #047857, #10B981);">GTE</div>
              </div>
              <div class="biz-card-body">
                <div class="row1"><span class="verified-badge">Verified Member</span></div>
                <h3>Global Trade Exports
                  <span class="verified" title="Verified"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2 4 4-2-2 4 4 2-4 2 2 4-4-2-2 4-2-4-4 2 2-4-4-2 4-2-2-4 4 2z"/></svg></span>
                </h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Birmingham, United Kingdom</div>
                <div class="rating">
                  <span class="stars">
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                  </span>
                  <span class="score">4.7</span>
                  <span class="count">(76 Reviews)</span>
                </div>
                <p class="desc">Trusted exporter of agricultural products, textiles and consumer goods since 2010.</p>
                <div class="tags">
                  <span class="tag">Exporters</span>
                  <span class="tag">Agriculture</span>
                </div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Established <strong>2010</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Employees <strong>100+</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Export to <strong>40+ Countries</strong></div>
                <div class="view-profile">
                  <a href="#" class="btn-view-profile">View Profile</a>
                </div>
              </div>
            </article>

            <!-- Card 4 -->
            <article class="biz-card" data-type="business">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <div class="logo-mark" style="background: linear-gradient(135deg, #DC2626, #F59E0B);">+</div>
              </div>
              <div class="biz-card-body">
                <div class="row1"><span class="verified-badge">Verified Member</span></div>
                <h3>Meditech Healthcare
                  <span class="verified" title="Verified"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2 4 4-2-2 4 4 2-4 2 2 4-4-2-2 4-2-4-4 2 2-4-4-2 4-2-2-4 4 2z"/></svg></span>
                </h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>London, United Kingdom</div>
                <div class="rating">
                  <span class="stars">
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                  </span>
                  <span class="score">4.9</span>
                  <span class="count">(110 Reviews)</span>
                </div>
                <p class="desc">Healthcare equipment, medical supplies and solutions for hospitals and clinics.</p>
                <div class="tags">
                  <span class="tag">Healthcare</span>
                  <span class="tag">Medical Equipment</span>
                </div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Established <strong>2015</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Employees <strong>200+</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Export to <strong>20+ Countries</strong></div>
                <div class="view-profile">
                  <a href="#" class="btn-view-profile">View Profile</a>
                </div>
              </div>
            </article>

            <!-- Card 5 -->
            <article class="biz-card" data-type="business">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <div class="logo-mark" style="background: linear-gradient(135deg, #7C3AED, #A855F7);">FS</div>
              </div>
              <div class="biz-card-body">
                <div class="row1"><span class="verified-badge">Verified Member</span></div>
                <h3>Finsbury Solutions
                  <span class="verified" title="Verified"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2 4 4-2-2 4 4 2-4 2 2 4-4-2-2 4-2-4-4 2 2-4-4-2 4-2-2-4 4 2z"/></svg></span>
                </h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Leeds, United Kingdom</div>
                <div class="rating">
                  <span class="stars">
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                  </span>
                  <span class="score">4.6</span>
                  <span class="count">(64 Reviews)</span>
                </div>
                <p class="desc">Financial consulting, audit and risk advisory services for global enterprises.</p>
                <div class="tags">
                  <span class="tag">Finance</span>
                  <span class="tag">Consulting</span>
                </div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Established <strong>2005</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Employees <strong>180+</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Export to <strong>15+ Countries</strong></div>
                <div class="view-profile">
                  <a href="#" class="btn-view-profile">View Profile</a>
                </div>
              </div>
            </article>

            <!-- Card 6 -->
            <article class="biz-card" data-type="business">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <div class="logo-mark" style="background: linear-gradient(135deg, #0EA5E9, #06B6D4);">ATL</div>
              </div>
              <div class="biz-card-body">
                <div class="row1"><span class="verified-badge premium-badge">Premium Member</span></div>
                <h3>Atlantic Logistics
                  <span class="verified" title="Verified"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2 4 4-2-2 4 4 2-4 2 2 4-4-2-2 4-2-4-4 2 2-4-4-2 4-2-2-4 4 2z"/></svg></span>
                </h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Glasgow, United Kingdom</div>
                <div class="rating">
                  <span class="stars">
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                    <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
                  </span>
                  <span class="score">4.8</span>
                  <span class="count">(89 Reviews)</span>
                </div>
                <p class="desc">International freight forwarding, customs brokerage and supply chain solutions.</p>
                <div class="tags">
                  <span class="tag">Logistics</span>
                  <span class="tag">Supply Chain</span>
                </div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Established <strong>2003</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Employees <strong>320+</strong></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg> Export to <strong>50+ Countries</strong></div>
                <div class="view-profile">
                  <a href="#" class="btn-view-profile">View Profile</a>
                </div>
              </div>
            </article>

            

            <!-- Investor sample -->
            <article class="biz-card person-row" data-type="investor">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <img src="assets/img/default-investor-profile.png" alt="Whitfield Capital Partners" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div class="biz-card-body">
                <div class="row1">
                  <span class="verified-badge premium-badge">Platinum Investor</span>
                  <span class="verified-badge">Verified Member</span>
                </div>
                <h3>Whitfield Capital Partners</h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>London, United Kingdom</div>
                <p class="desc">Acquiring majority stakes in established manufacturing and distribution businesses with £500K–£5M EBITDA.</p>
                <div class="tags"><span class="tag">Investor</span><span class="tag">Manufacturing</span><span class="tag">Distribution</span></div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <span>Phone</span></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> <span>Email</span></div>
                <a href="login.php" class="meta-action">Send Proposal</a>
              </div>
            </article>

            <!-- Investor sample -->
            <article class="biz-card person-row" data-type="investor">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <img src="assets/img/default-investor-profile.png" alt="Northline Ventures" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div class="biz-card-body">
                <div class="row1">
                  <span class="verified-badge premium-badge">Platinum Investor</span>
                  <span class="verified-badge">Verified Member</span>
                </div>
                <h3>Northline Ventures</h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Manchester, United Kingdom</div>
                <p class="desc">Early-stage investor backing scalable consumer brands and digital platforms across the North West.</p>
                <div class="tags"><span class="tag">Investor</span><span class="tag">Consumer Brands</span><span class="tag">Digital</span></div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <span>Phone</span></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> <span>Email</span></div>
                <a href="login.php" class="meta-action">Send Proposal</a>
              </div>
            </article>

            <!-- Investor sample -->
            <article class="biz-card person-row" data-type="investor">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <img src="assets/img/default-investor-profile.png" alt="Meridian Growth Fund" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div class="biz-card-body">
                <div class="row1">
                  <span class="verified-badge premium-badge">Platinum Investor</span>
                  <span class="verified-badge">Verified Member</span>
                </div>
                <h3>Meridian Growth Fund</h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Birmingham, United Kingdom</div>
                <p class="desc">Growth capital for healthcare and med-tech companies that are ready to scale nationally.</p>
                <div class="tags"><span class="tag">Investor</span><span class="tag">Healthcare</span><span class="tag">Growth Capital</span></div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <span>Phone</span></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> <span>Email</span></div>
                <a href="login.php" class="meta-action">Send Proposal</a>
              </div>
            </article>

            <!-- Startup sample -->
            <article class="biz-card person-row" data-type="startup">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <img src="assets/img/default-startup-profile.png" alt="Immersive STEM Learning Platform" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div class="biz-card-body">
                <div class="row1">
                  <span class="verified-badge premium-badge">Startup</span>
                  <span class="verified-badge">Verified Member</span>
                </div>
                <h3>Immersive STEM Learning Platform</h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>London, United Kingdom</div>
                <p class="desc">High-growth startup building immersive STEM learning tools for secondary schools. Seeking £250,000 investment.</p>
                <div class="tags"><span class="tag">Startup</span><span class="tag">Education</span><span class="tag">Seeking £250,000</span></div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <span>Phone</span></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> <span>Email</span></div>
                <a href="login.php" class="meta-action">Send Proposal</a>
              </div>
            </article>

            <!-- Startup sample -->
            <article class="biz-card person-row" data-type="startup">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <img src="assets/img/default-startup-profile.png" alt="Telehealth GP Access Platform" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div class="biz-card-body">
                <div class="row1">
                  <span class="verified-badge premium-badge">Startup</span>
                  <span class="verified-badge">Verified Member</span>
                </div>
                <h3>Telehealth GP Access Platform</h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Manchester, United Kingdom</div>
                <p class="desc">Telehealth startup connecting patients with private GPs. Seeking £400,000 seed investment for national rollout.</p>
                <div class="tags"><span class="tag">Startup</span><span class="tag">Healthcare</span><span class="tag">Seeking £400,000</span></div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <span>Phone</span></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> <span>Email</span></div>
                <a href="login.php" class="meta-action">Send Proposal</a>
              </div>
            </article>

            <!-- Startup sample -->
            <article class="biz-card person-row" data-type="startup">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <img src="assets/img/default-startup-profile.png" alt="AI Compliance Automation" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div class="biz-card-body">
                <div class="row1">
                  <span class="verified-badge premium-badge">Startup</span>
                  <span class="verified-badge">Verified Member</span>
                </div>
                <h3>AI Compliance Automation</h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Cambridge, United Kingdom</div>
                <p class="desc">AI-powered compliance automation for UK SMEs. Seeking £300,000 investment to accelerate product development.</p>
                <div class="tags"><span class="tag">Startup</span><span class="tag">Technology</span><span class="tag">Seeking £300,000</span></div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <span>Phone</span></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> <span>Email</span></div>
                <a href="login.php" class="meta-action">Send Proposal</a>
              </div>
            </article>

            <!-- Mentor sample -->
            <article class="biz-card person-row" data-type="mentor">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <img src="assets/img/default-mentor-profile.png" alt="Richard Hallsworth" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div class="biz-card-body">
                <div class="row1">
                  <span class="verified-badge premium-badge">Platinum Mentor</span>
                  <span class="verified-badge">Verified Member</span>
                </div>
                <h3>Richard Hallsworth</h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>London, United Kingdom</div>
                <p class="desc">40+ years of experience in corporate finance, technical due diligence and banking institutions.</p>
                <div class="tags"><span class="tag">Mentor</span><span class="tag">Corporate Finance</span><span class="tag">M&A</span></div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <span>Phone</span></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> <span>Email</span></div>
                <a href="login.php" class="meta-action">Send Proposal</a>
              </div>
            </article>

            <!-- Mentor sample -->
            <article class="biz-card person-row" data-type="mentor">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <img src="assets/img/default-mentor-profile.png" alt="Amanda Clarke" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div class="biz-card-body">
                <div class="row1">
                  <span class="verified-badge premium-badge">Platinum Mentor</span>
                  <span class="verified-badge">Verified Member</span>
                </div>
                <h3>Amanda Clarke</h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Manchester, United Kingdom</div>
                <p class="desc">Strategic business planning, business development and international expansion specialist with P&L ownership.</p>
                <div class="tags"><span class="tag">Mentor</span><span class="tag">Strategy</span><span class="tag">Expansion</span></div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <span>Phone</span></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> <span>Email</span></div>
                <a href="login.php" class="meta-action">Send Proposal</a>
              </div>
            </article>

            <!-- Mentor sample -->
            <article class="biz-card person-row" data-type="mentor">
              <button class="heart" aria-label="Save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
              <div class="biz-card-logo">
                <img src="assets/img/default-mentor-profile.png" alt="Elaine Foster" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div class="biz-card-body">
                <div class="row1">
                  <span class="verified-badge premium-badge">Platinum Mentor</span>
                  <span class="verified-badge">Verified Member</span>
                </div>
                <h3>Elaine Foster</h3>
                <div class="location"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>Leeds, United Kingdom</div>
                <p class="desc">Marketing, branding and customer growth mentor with 25 years across consumer and retail sectors.</p>
                <div class="tags"><span class="tag">Mentor</span><span class="tag">Marketing</span><span class="tag">Branding</span></div>
              </div>
              <div class="biz-card-meta">
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <span>Phone</span></div>
                <div class="meta-row"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> <span>Email</span></div>
                <a href="login.php" class="meta-action">Send Proposal</a>
              </div>
            </article>

          </div>

          <!-- Pagination -->
          <div class="pagination">
            <a href="#" aria-label="Previous"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
            <a href="#" class="active">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">5</a>
            <a href="#" class="ellipsis">…</a>
            <a href="#">2136</a>
            <a href="#" aria-label="Next"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
          </div>

          <!-- CTA banner -->
          <div class="cta-banner">
            <div class="content">
              <span class="ic">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="M12 15l-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a.73.73 0 0 1-.65.06A7.5 7.5 0 0 1 12 15z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>
              </span>
              <div>
                <h3>Grow Your Business Globally</h3>
                <p>Join thousands of businesses already growing with BusinessX.</p>
              </div>
            </div>
            <a href="registration.php" class="btn-gold">Add Your Business Now</a>
          </div>

        </div>
      </div>
    </div>
  </section>

  <?php include __DIR__ . "/includes/footer.php"; ?>

  <script>
    const filterTreeData = {
      location: [
        { label: 'United Kingdom', children: ['London', 'Manchester', 'Birmingham', 'Leeds', 'Glasgow'] },
        { label: 'Japan', children: ['Nagoya'] }
      ],
      industry: [
        { label: 'Automobile', children: ['Automobile Accessories', 'Automobile parts', 'Automobile wash', 'Automobile Electric vehicles', 'Automobile Insurance', 'Automobile Maintenance & repair', 'Automobile Manufacturing', 'Automobile Reselling', 'Automobile Showrooms', 'Car workshop for sale near me', 'Car wash for sale', 'Car service center for sale'] },
        { label: 'Beauty, health & wellness', children: ['Ambulance healthcare service', 'Beauty equipments', 'Beauty Salons', 'Clinics & Nursing Homes'] },
        { label: 'Building construction & Home products', children: ['Bathroom fixtures', 'Brick & cement', 'Building contractors', 'Building maintenance'] },
        { label: 'Business services', children: ['Advertisement & media services', 'BPO', 'Broadcasting services', 'Book, magazine & newspaper publishing'] },
        { label: 'Education', children: ['Coaching & training institutes', 'Colleges', 'Day Care centres, Creches', 'Education Supplies'] },
        { label: 'Energy & Environment', children: ['Biofuel', 'Environment related', 'Gas & petroleum stations', 'LPG dealers'] },
        { label: 'FMCG', children: ['Ayurvedic products', 'Beauty cosmetics', 'Computer hardware', 'Consumer electronics'] },
        { label: 'Fashion', children: ['Bags & luggage', 'Children clothing', 'Ethnical wear', 'Fabric'] },
        { label: 'Finance', children: ['Banking', 'Consumer leasing', 'Insurance', 'Insurance agent'] },
        { label: 'Food & beverage', children: ['Agriculture & farming', 'Agriculture products', 'Alcoholic beverages', 'Animal feed'] },
        { label: 'Health', children: ['Hospital investment', 'Running hospital for sale', 'Medical shop in hospital for sale', 'Private hospital sale'] },
        { label: 'Industrial machinery & Manufacturing', children: ['Aerospace equipments & related', 'Agriculture related', 'Automotive machinery', 'Battery, UPS & electricity backup'] },
        { label: 'Leisure & Entertainment', children: ['Adventure sports', 'Amusement parks', 'Casinos & gaming', 'Entertainment centres'] },
        { label: 'Retail', children: ['Auto stores', 'Beauty & health stores', 'Book stores', 'Children clothing & footwear stores'] },
        { label: 'Software & ITservices', children: ['Telecommunication', 'Web Hosting Businesses for Sale', 'Web Hosting Investment Opportunities', 'Hosting business for sale'] },
        { label: 'Travel & tourism', children: ['Airlines', 'Airport services', 'Vehicle rental services', 'Charter flight services'] }
      ]
    };

    function escapeFilterText(value) {
      return value.replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[character]);
    }

    function renderFilterTree(type) {
      const tree = document.getElementById(`${type}-filter-tree`);
      tree.innerHTML = filterTreeData[type].map((group, index) => {
        const groupId = `${type}-group-${index}`;
        const children = group.children.map(child => `
          <label class="filter-item"><input class="filter-tree-child" type="checkbox" value="${escapeFilterText(child)}"> ${escapeFilterText(child)}</label>
        `).join('');
        return `
          <div class="filter-tree-group" data-filter-group>
            <div class="filter-tree-row">
              <label class="filter-item"><input class="filter-tree-parent" type="checkbox" value="${escapeFilterText(group.label)}"> ${escapeFilterText(group.label)}</label>
              <button type="button" class="filter-tree-toggle" aria-expanded="false" aria-controls="${groupId}" aria-label="Expand ${escapeFilterText(group.label)}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></button>
            </div>
            <div class="filter-tree-children" id="${groupId}">${children}</div>
          </div>
        `;
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

    // === Listing type and sidebar filters ===
    const typePills = document.querySelectorAll('.type-pill');
    const typeCards = document.querySelectorAll('.biz-list .biz-card');
    const resultsCount = document.querySelector('.results-count');
    const countStrong = resultsCount ? resultsCount.querySelectorAll('strong') : [];
    const typeLabels = { all: 'listings', business: 'businesses', investor: 'investors', startup: 'startups', mentor: 'mentors' };
    const typeCounts = { all: 25630, business: 1863, investor: 511, startup: 678, mentor: 194 };
    let activeType = 'all';

    function applyTypeFilter(type) {
      activeType = type;
      typePills.forEach(p => p.classList.toggle('active', p.dataset.type === type));
      applySidebarFilters();
    }

    function applySidebarFilters() {
      const intent = document.querySelector('input[name="businessIntent"]:checked').value;
      const locations = [...document.querySelectorAll('#location-filter-tree .filter-tree-child:checked')].map(input => input.value.toLowerCase());
      const industries = [...document.querySelectorAll('#industry-filter-tree .filter-tree-child:checked')].map(input => input.value.toLowerCase());
      const minimumSales = Number(document.getElementById('annual-sales-min').value);
      const maximumSales = Number(document.getElementById('annual-sales-max').value);
      let visible = 0;
      typeCards.forEach(card => {
        const cardText = card.textContent.toLowerCase();
        const cardIntent = card.dataset.intent || (card.dataset.type === 'investor' ? 'investor' : card.dataset.type === 'business' ? 'sale' : 'other');
        const annualSales = card.dataset.annualSales === undefined ? null : Number(card.dataset.annualSales);
        const matchesType = activeType === 'all' || card.dataset.type === activeType;
        const matchesIntent = intent === 'all' || cardIntent === intent;
        const matchesLocation = locations.length === 0 || locations.some(location => cardText.includes(location));
        const matchesIndustry = industries.length === 0 || industries.some(industry => cardText.includes(industry));
        const matchesSales = annualSales === null || (annualSales >= minimumSales && annualSales <= maximumSales);
        const show = matchesType && matchesIntent && matchesLocation && matchesIndustry && matchesSales;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
      });
      if (countStrong.length >= 2) {
        countStrong[0].textContent = visible ? '1 – ' + visible : '0';
        countStrong[1].textContent = typeCounts[activeType].toLocaleString();
      }
      if (resultsCount) {
        resultsCount.innerHTML = resultsCount.innerHTML.replace(/businesses|investors|startups|mentors|listings/, typeLabels[activeType]);
      }
    }

    document.querySelector('.apply-filters').addEventListener('click', applySidebarFilters);
    document.querySelector('.filter-clear').addEventListener('click', () => {
      document.querySelector('input[name="businessIntent"][value="all"]').checked = true;
      document.querySelectorAll('.filter-tree input').forEach(input => {
        input.checked = false;
        input.indeterminate = false;
      });
      document.querySelectorAll('[data-filter-search]').forEach(search => {
        search.value = '';
        search.dispatchEvent(new Event('input'));
      });
      const minimum = document.getElementById('annual-sales-min');
      const maximum = document.getElementById('annual-sales-max');
      minimum.value = minimum.min;
      maximum.value = maximum.max;
      minimum.dispatchEvent(new Event('input'));
      maximum.dispatchEvent(new Event('input'));
      applySidebarFilters();
    });

    const minimumSalesInput = document.getElementById('annual-sales-min');
    const maximumSalesInput = document.getElementById('annual-sales-max');
    function updateSalesRange(changedInput) {
      if (Number(minimumSalesInput.value) > Number(maximumSalesInput.value)) {
        if (changedInput === minimumSalesInput) maximumSalesInput.value = minimumSalesInput.value;
        else minimumSalesInput.value = maximumSalesInput.value;
      }
      document.getElementById('annual-sales-min-value').textContent = `${(Number(minimumSalesInput.value) / 10000000).toFixed(2)} cr`;
      document.getElementById('annual-sales-max-value').textContent = `${(Number(maximumSalesInput.value) / 10000000).toFixed(2)} cr`;
    }
    minimumSalesInput.addEventListener('input', () => updateSalesRange(minimumSalesInput));
    maximumSalesInput.addEventListener('input', () => updateSalesRange(maximumSalesInput));

    typePills.forEach(pill => {
      pill.addEventListener('click', () => {
        const t = pill.dataset.type;
        applyTypeFilter(t);
        const url = new URL(window.location);
        if (t === 'all') url.searchParams.delete('type'); else url.searchParams.set('type', t);
        window.history.replaceState({}, '', url);
      });
    });

    // Preselect from ?type= query param
    const urlType = new URLSearchParams(window.location.search).get('type');
    if (urlType && ['business','investor','startup','mentor'].includes(urlType)) {
      applyTypeFilter(urlType);
    }
  </script>
</body>
</html>

