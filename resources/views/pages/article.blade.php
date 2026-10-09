@extends('layouts.app')

@section('title')
Articles &amp; News | BusinessX - World Trade Council
@endsection
@section('description')
BusinessX articles, news and international trade insights.
@endsection

@section('head')
<style>
    body { font-family: 'Inter', sans-serif; background: var(--gray-25); }
    .article-hero { position: relative; overflow: hidden; background: var(--navy-900); color: var(--white); }
    .article-hero::before { content: ''; position: absolute; inset: 0 0 0 36%; background: linear-gradient(90deg, rgba(16,40,74,.94) 0%, rgba(16,40,74,.72) 36%, rgba(16,40,74,.66) 100%), url("{{ asset('assets/img/hero-bg.jpg') }}") center / cover no-repeat; }
    .article-hero .container { position: relative; z-index: 1; display: grid; grid-template-columns: minmax(0, .72fr) minmax(0, 1.28fr); }
    .article-hero-content {  height:400px; min-width: 0; padding: 60px 0 52px 28px; position: relative; z-index: 1; }
    .article-hero h1 { margin-bottom: 12px; color: var(--white); font-size: 36px; }
    .article-hero .sub { max-width: 720px; margin-bottom: 28px; color: var(--gray-300); font-size: 16px; }
    .hero-search { display: flex; width: 100%; max-width: 760px; overflow: hidden; border-radius: 8px; background: var(--white); box-shadow: 0 4px 20px rgba(0,0,0,.2); }
    .hero-search input { flex: 1; min-width: 0; height: 52px; padding: 0 18px; border: 0; background: transparent; font: inherit; font-size: 14px; }
    .hero-search select { width: 180px; height: 52px; padding: 0 12px; border: 0; border-left: 1px solid var(--gray-200); background: var(--white); font: inherit; font-size: 13px; }
    .hero-search .search-btn { min-width: 96px; background: var(--gold-500); color: var(--navy-900); font-weight: 700; }
    .hero-stats { display: flex; flex-wrap: wrap; gap: 28px; max-width: 760px; margin-top: 28px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,.2); }
    .hero-stat { display: flex; align-items: center; gap: 10px; }
    .hero-stat .ic { width: 24px; height: 24px; color: var(--gold-500); }
    .hero-stat .num { color: var(--gold-500); font-size: 17px; font-weight: 700; }
    .hero-stat .lbl { color: var(--gray-300); font-size: 11px; }
    .article-main { padding: 48px 0; }
    .article-grid { display: grid; grid-template-columns: minmax(0, 340px) minmax(0, 1fr); align-items: start; gap: 24px; width: 100%; min-width: 0; }
    .article-sidebar { min-width: 0; }
    .article-content { min-width: 0; }
    .side-card { margin-bottom: 20px; padding: 28px 30px; border: 1px solid var(--gray-200); border-radius: 8px; background: var(--white); box-shadow: 0 1px 3px rgba(0,0,0,.05); }
    .side-card h3 { font-size: 20px; font-weight: 700; color: var(--navy-900); margin-bottom: 24px; }
    .side-card .reset-link { font-size: 12px; color: var(--gold-600); font-weight: 600; }
    .cat-list .cat-item {
      display: flex; justify-content: space-between; align-items: center;
      width: 100%; min-height: 50px; padding: 8px 0;
      border-bottom: 1px solid var(--gray-100);
      background: transparent; text-align: left; font: inherit;
      font-size: 17px; color: var(--navy-700);
      cursor: pointer; transition: color 0.2s;
    }
    .cat-list .cat-item:last-child { border-bottom: none; }
    .cat-list .cat-item:hover, .cat-list .cat-item.active { color: var(--gold-600); }
    .cat-list .count {
      flex-shrink: 0; margin-left: 12px;
      font-size: 14px; color: var(--gray-400);
      background: var(--gray-100); padding: 5px 10px;
      border-radius: 999px;
    }
    .cat-list .cat-item.active .count { background: var(--gold-100); color: var(--gold-600); }

    .filter-group { margin-bottom: 20px; }
    .filter-group h4 { font-size: 13px; font-weight: 600; color: var(--gray-700); margin-bottom: 10px; }
    .check-list { display: flex; flex-direction: column; gap: 8px; }
    .check-item { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--gray-700); }
    .check-item input { accent-color: var(--gold-500); width: 16px; height: 16px; }
    .filter-group select {
      width: 100%; height: 40px; padding: 0 12px;
      border: 1px solid var(--gray-300); border-radius: 6px;
      background: var(--white); font-size: 13px;
      font-family: inherit; appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='%236B7280'%3E%3Cpath d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
      background-size: 10px;
    }
    .apply-filters {
      width: 100%; height: 44px;
      background: var(--navy-900); color: var(--white);
      border-radius: 6px; font-size: 14px; font-weight: 600;
      margin-top: 20px;
    }
    .apply-filters:hover { background: var(--navy-800); }

    /* Top bar of content area */
    .results-top {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .results-top .count-text { font-size: 14px; color: var(--gray-600); }
    .results-top .count-text strong { color: var(--navy-700); }
    .results-controls { display: flex; align-items: center; gap: 16px; }
    .sort-wrap { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--gray-600); }
    .sort-wrap select {
      padding: 8px 32px 8px 12px; border: 1px solid var(--gray-200);
      border-radius: 6px; background: var(--white); font-size: 13px;
      font-family: inherit; appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='%236B7280'%3E%3Cpath d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 10px center;
      background-size: 10px;
    }
    .view-toggle { display: flex; border: 1px solid var(--gray-200); border-radius: 6px; overflow: hidden; }
    .view-toggle button { width: 36px; height: 32px; display: flex; align-items: center; justify-content: center; background: var(--white); color: var(--gray-400); border: none; }
    .view-toggle button.active { background: var(--gold-500); color: var(--white); }

    /* Article cards grid */
    .article-grid-list { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px; }
    .article-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: 10px;
      overflow: hidden;
      transition: all 0.2s ease;
      display: flex; flex-direction: column;
    }
    .article-card:hover { box-shadow: 0 10px 25px rgba(0,0,0,0.1); transform: translateY(-3px); }
    .article-card .img {
      aspect-ratio: 16/9;
      position: relative;
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,0.5); font-size: 11px; font-weight: 600;
      letter-spacing: 0.1em;
    }
    .article-card .img .badge-cat {
      position: absolute; top: 12px; left: 12px;
      background: var(--blue-500); color: var(--white);
      padding: 4px 10px; border-radius: 4px;
      font-size: 11px; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .article-card .img.badge-success .badge-cat { background: var(--green-500); }
    .article-card .img.badge-gold .badge-cat { background: var(--gold-500); color: var(--navy-900); }
    .article-card .img.badge-purple .badge-cat { background: var(--purple-500); }
    .article-card .img-1 { background: linear-gradient(135deg, #1e3a5f, #2d5078); }
    .article-card .img-2 { background: linear-gradient(135deg, #1a4d2e, #2d6e3f); }
    .article-card .img-3 { background: linear-gradient(135deg, #5f3a1e, #8b5a2b); }
    .article-card .img-4 { background: linear-gradient(135deg, #4a1e5f, #6b2d8b); }
    .article-card .img-5 { background: linear-gradient(135deg, #1e3a5f, #2d5078); }
    .article-card .img-6 { background: linear-gradient(135deg, #2d4f1e, #4a7d2b); }
    .article-card .body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
    .article-card .meta-row {
      display: flex; align-items: center; gap: 8px;
      font-size: 12px; color: var(--gray-400);
      margin-bottom: 8px;
    }
    .article-card .meta-row svg { width: 12px; height: 12px; }
    .article-card h3 {
      font-size: 16px; font-weight: 700;
      color: var(--gray-900); line-height: 1.3;
      margin-bottom: 8px; flex: 1;
    }
    .article-card .desc { font-size: 13px; color: var(--gray-600); line-height: 1.5; margin-bottom: 12px; }
    .article-card .author {
      display: flex; align-items: center; gap: 8px;
      padding-top: 12px; border-top: 1px solid var(--gray-100);
    }
    .article-card .author .avatar {
      width: 28px; height: 28px; border-radius: 50%;
      background: linear-gradient(135deg, #52A9EF, #1454B8);
    }
    .article-card .author .name { font-size: 12px; font-weight: 600; color: var(--gray-700); }
    .article-card .author .date { font-size: 11px; color: var(--gray-400); }
    .article-card .read-more {
      display: inline-flex; align-items: center; gap: 4px;
      font-size: 13px; font-weight: 600; color: var(--gold-600);
      margin-top: 8px;
    }
    .article-card .read-more:hover { color: var(--gold-700); }

    /* Pagination */
    .pagination {
      display: flex; justify-content: center; gap: 6px;
      margin-bottom: 32px;
    }
    .pagination a {
      min-width: 36px; height: 36px; padding: 0 10px;
      border: 1px solid var(--gray-200); border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; color: var(--gray-700); background: var(--white);
    }
    .pagination a.active {
      background: var(--navy-900); border-color: var(--navy-900); color: var(--white);
    }
    .pagination span {
      min-width: 36px; height: 36px; padding: 0 10px;
      border: 1px solid var(--gray-200); border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; color: var(--gray-400); background: var(--white);
    }
    .pagination a:hover:not(.active) { border-color: var(--navy-700); }
    .pagination a.ellipsis { border: none; background: transparent; }

    /* Newsletter banner */
    .newsletter-banner {
      background: linear-gradient(135deg, var(--navy-900), var(--navy-700));
      border-radius: 12px; padding: 32px 40px;
      display: flex; align-items: center; justify-content: space-between;
      gap: 24px; flex-wrap: wrap;
      position: relative; overflow: hidden;
    }
    .newsletter-banner::before {
      content: ''; position: absolute; inset: 0;
      background-image: radial-gradient(rgba(255,255,255,0.06) 1.5px, transparent 1.5px);
      background-size: 20px 20px;
    }
    .newsletter-banner .ic {
      width: 48px; height: 48px; border-radius: 50%;
      background: rgba(19,141,227,0.15); color: var(--gold-500);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .newsletter-banner .ic svg { width: 22px; height: 22px; }
    .newsletter-banner .content { display: flex; align-items: center; gap: 16px; position: relative; z-index: 2; }
    .newsletter-banner h3 { font-size: 20px; font-weight: 700; color: var(--white); margin-bottom: 4px; }
    .newsletter-banner p { font-size: 14px; color: rgba(255,255,255,0.7); }
    .newsletter-banner form { display: flex; gap: 8px; position: relative; z-index: 2; min-width: 320px; }
    .newsletter-banner input {
      flex: 1; height: 44px; padding: 0 14px;
      background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
      color: var(--white); border-radius: 6px; font-size: 14px;
    }
    .newsletter-banner input::placeholder { color: rgba(255,255,255,0.5); }
    .newsletter-banner button {
      padding: 0 20px; background: var(--gold-500); color: var(--navy-900);
      border-radius: 6px; font-weight: 700; font-size: 14px;
      white-space: nowrap;
    }
    .newsletter-banner button:hover { background: var(--gold-600); }

    /* Featured Article Detail (below fold) */
    .featured-detail {
      background: var(--white);
      border-top: 1px solid var(--gray-200);
      padding: 64px 0;
    }
    .breadcrumbs {
      display: flex; align-items: center; gap: 8px;
      font-size: 13px; color: var(--gray-500);
      margin-bottom: 24px; flex-wrap: wrap;
    }
    .breadcrumbs a { color: var(--gray-500); }
    .breadcrumbs a:hover { color: var(--gold-600); }
    .breadcrumbs .sep { color: var(--gray-300); }
    .breadcrumbs .current { color: var(--navy-700); font-weight: 500; }

    .featured-grid {
      display: grid; grid-template-columns: 1fr 320px;
      gap: 40px;
    }

    .article-detail .cat-badge {
      display: inline-block;
      background: var(--blue-100); color: var(--blue-500);
      padding: 4px 10px; border-radius: 4px;
      font-size: 11px; font-weight: 700;
      letter-spacing: 0.05em; text-transform: uppercase;
      margin-bottom: 16px;
    }
    .article-detail h1 {
      font-size: clamp(24px, 3vw, 36px);
      font-weight: 700; line-height: 1.2;
      color: var(--gray-900); margin-bottom: 16px;
      letter-spacing: -0.02em;
    }
    .article-detail .lede {
      font-size: 17px; line-height: 1.6;
      color: var(--gray-700); margin-bottom: 24px;
    }
    .article-detail .meta {
      display: flex; align-items: center; gap: 16px;
      padding-bottom: 20px; margin-bottom: 24px;
      border-bottom: 1px solid var(--gray-200);
      font-size: 13px; color: var(--gray-500);
      flex-wrap: wrap;
    }
    .article-detail .meta .author {
      display: flex; align-items: center; gap: 8px;
    }
    .article-detail .meta .author .avatar {
      width: 36px; height: 36px; border-radius: 50%;
      background: linear-gradient(135deg, #52A9EF, #1454B8);
    }
    .article-detail .meta .author .name { font-weight: 600; color: var(--gray-800); }
    .article-detail .meta .meta-divider { width: 1px; height: 24px; background: var(--gray-200); }
    .article-detail .meta .meta-item { display: flex; align-items: center; gap: 6px; }
    .article-detail .meta .meta-item svg { width: 14px; height: 14px; color: var(--gray-400); }

    .article-detail .hero-img {
      width: 100%; aspect-ratio: 16/9;
      border-radius: 12px; margin-bottom: 32px;
      background: linear-gradient(135deg, #1e3a5f, #2d5078);
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,0.5); font-size: 12px;
      font-weight: 600; letter-spacing: 0.1em;
    }
    .article-detail .content p {
      font-size: 16px; line-height: 1.7;
      color: var(--gray-700);
      margin-bottom: 20px;
    }
    .article-detail .content h2 {
      font-size: 22px; font-weight: 700;
      color: var(--gray-900);
      margin: 32px 0 16px;
    }
    .article-detail .content blockquote {
      border-left: 4px solid var(--gold-500);
      padding: 16px 24px;
      background: var(--gray-50);
      border-radius: 0 8px 8px 0;
      margin: 24px 0;
      font-style: italic;
      color: var(--gray-700);
      font-size: 16px;
    }
    .article-detail .content ul {
      list-style: none; margin-bottom: 20px;
      padding-left: 0;
    }
    .article-detail .content ul li {
      position: relative;
      padding-left: 24px;
      font-size: 15px; line-height: 1.7;
      color: var(--gray-700);
      margin-bottom: 10px;
    }
    .article-detail .content ul li::before {
      content: '';
      position: absolute; left: 0; top: 9px;
      width: 14px; height: 14px;
      background: var(--gold-500);
      border-radius: 50%;
      box-shadow: 0 0 0 3px rgba(19,141,227,0.2);
    }
    .article-detail .tags-row {
      display: flex; gap: 8px; flex-wrap: wrap;
      padding-top: 24px; margin-top: 32px;
      border-top: 1px solid var(--gray-200);
    }
    .article-detail .tag-pill {
      background: var(--gray-100);
      color: var(--gray-700);
      padding: 4px 12px; border-radius: 999px;
      font-size: 12px; font-weight: 500;
    }

    .article-detail .share-row {
      display: flex; justify-content: space-between;
      align-items: center; gap: 12px;
      padding: 16px 0;
      margin-top: 24px;
      border-top: 1px solid var(--gray-200);
      border-bottom: 1px solid var(--gray-200);
    }
    .article-detail .share-label { font-size: 13px; font-weight: 600; color: var(--gray-700); }
    .article-detail .share-icons { display: flex; gap: 8px; }
    .article-detail .share-icons a {
      width: 32px; height: 32px; border-radius: 50%;
      background: var(--gray-100); color: var(--gray-600);
      display: flex; align-items: center; justify-content: center;
    }
    .article-detail .share-icons a:hover { background: var(--gold-500); color: var(--white); }
    .article-detail .share-icons svg { width: 14px; height: 14px; }

    /* Sidebar */
    .detail-sidebar .side-card { margin-bottom: 24px; }
    .author-card .author-img {
      width: 80px; height: 80px; border-radius: 50%;
      background: linear-gradient(135deg, #52A9EF, #1454B8);
      margin: 0 auto 16px;
    }
    .author-card { text-align: center; }
    .author-card h4 { font-size: 16px; font-weight: 700; color: var(--gray-900); margin-bottom: 4px; }
    .author-card .role { font-size: 12px; color: var(--gold-600); font-weight: 600; margin-bottom: 12px; }
    .author-card .bio { font-size: 13px; color: var(--gray-600); line-height: 1.5; margin-bottom: 16px; }
    .author-card .follow-btn {
      width: 100%; padding: 8px; border: 1px solid var(--gold-500);
      color: var(--gold-600); background: transparent;
      border-radius: 6px; font-size: 13px; font-weight: 600;
    }
    .author-card .follow-btn:hover { background: var(--gold-50); }

    .related-list .related-item {
      display: flex; gap: 12px;
      padding: 12px 0; border-bottom: 1px solid var(--gray-100);
    }
    .related-list .related-item:last-child { border-bottom: none; }
    .related-list .related-img {
      width: 64px; height: 64px; border-radius: 8px;
      flex-shrink: 0; background: linear-gradient(135deg, #1e3a5f, #2d5078);
    }
    .related-list h5 { font-size: 13px; font-weight: 600; color: var(--gray-800); line-height: 1.4; margin-bottom: 4px; }
    .related-list .related-item:hover h5 { color: var(--gold-600); }
    .related-list .related-date { font-size: 11px; color: var(--gray-400); }

    .popular-list { counter-reset: pop; }
    .popular-list .popular-item {
      display: flex; gap: 12px; padding: 10px 0;
      counter-increment: pop;
    }
    .popular-list .popular-item::before {
      content: counter(pop);
      font-size: 24px; font-weight: 700;
      color: var(--gold-500); line-height: 1;
      flex-shrink: 0;
    }
    .popular-list h5 { font-size: 13px; font-weight: 600; color: var(--gray-800); line-height: 1.4; margin-bottom: 4px; }
    .popular-list .popular-item:hover h5 { color: var(--gold-600); }
    .popular-list .popular-meta { font-size: 11px; color: var(--gray-400); }

    /* Tags cloud */
    .tag-cloud { display: flex; flex-wrap: wrap; gap: 8px; }
    .tag-cloud a {
      background: var(--gray-100); color: var(--gray-700);
      padding: 4px 12px; border-radius: 999px;
      font-size: 12px; font-weight: 500;
    }
    .tag-cloud a:hover { background: var(--gold-500); color: var(--white); }

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
      font-weight: 700;
    }
    @media (max-width: 1024px) {
      .bx-nav { display: none; }
      .article-hero::before { left: 0; background-image: linear-gradient(90deg, rgba(16,40,74,.82), rgba(16,40,74,.72)), url("{{ asset('assets/img/hero-bg.jpg') }}"); }
      .article-hero .container { grid-template-columns: minmax(0, 1fr); }
      .article-hero-content { grid-column: 1; width: min(100%, 760px); justify-self: end; margin-left: 0; padding-left: 0; }
      .article-grid { grid-template-columns: minmax(0, 1fr); }
      .article-grid-list { grid-template-columns: repeat(2, 1fr); }
      .featured-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
      .article-hero-content { padding-top: 48px; padding-bottom: 44px; }
      .article-grid-list { grid-template-columns: 1fr; }
      .hero-stats { flex-wrap: wrap; gap: 20px; }
      .newsletter-banner form { min-width: 100%; }
    }
    @media (max-width: 640px) {
      .article-hero-content { width: 100%; justify-self: stretch; }
      .hero-search { flex-direction: column; }
      .hero-search input,
      .hero-search select { width: 100%; flex: 0 0 auto; }
      .hero-search select { border-top: 1px solid var(--gray-200); border-left: 0; }
      .hero-search .search-btn { min-height: 48px; }
    }
  </style>
@endsection

@section('content')


  
  

  <!-- Hero -->
  <section class="article-hero">
    <div class="container">
    <div class="article-hero-content">
          <h1>Articles &amp; News</h1>
          <p class="sub">Insights, updates and expert perspectives to help your business grow globally.</p>
          <form class="hero-search" method="get" action="{{ route('article') }}">
            <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Search articles, news, topics..." aria-label="Search articles">
            <select name="category" aria-label="Article category">
              <option value="">All Categories</option>
              @foreach ($categories as $category)
                <option value="{{ $category['slug'] }}" @selected($filters['category'] === $category['slug'])>{{ $category['name'] }}</option>
              @endforeach
            </select>
            <button class="search-btn" type="submit">Search</button>
          </form>
          <div class="hero-stats">
            <div class="hero-stat"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h8"/></svg><div><div class="num">{{ number_format($totalArticles) }}</div><div class="lbl">Articles</div></div></div>
            <div class="hero-stat"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20"/></svg><div><div class="num">{{ count($categories) }}</div><div class="lbl">Topics</div></div></div>
            <div class="hero-stat"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg><div><div class="num">{{ $articles->total() }}</div><div class="lbl">Matching articles</div></div></div>
            <!-- <div class="hero-stat"><svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg><div><div class="num">Daily</div><div class="lbl">Fresh updates</div></div></div> -->
          </div>
      </div>
    </div>
  </section>

      <section class="article-main">
        <div class="container">
          <div class="article-grid">
            <aside class="article-sidebar">
              <div class="side-card">
                <h3>Categories</h3>
                <div class="cat-list">
                  <a href="{{ route('article', array_filter(['q' => $filters['q'], 'sort' => $filters['sort'], 'period' => $filters['period']])) }}" class="cat-item {{ !$selectedCategory ? 'active' : '' }}">
                    All Categories <span class="count">{{ $totalArticles }}</span>
                  </a>
                  @foreach ($categories as $category)
                    <a href="{{ route('article', array_filter(['category' => $category['slug'], 'q' => $filters['q'], 'sort' => $filters['sort'], 'period' => $filters['period']])) }}" class="cat-item {{ ($selectedCategory['slug'] ?? null) === $category['slug'] ? 'active' : '' }}">
                      {{ $category['name'] }} <span class="count">{{ $category['count'] }}</span>
                    </a>
                  @endforeach
                </div>
              </div>
              <div class="side-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
              <h3 style="margin-bottom:0;">Filter By</h3>
              <a href="{{ route('article') }}" class="reset-link">Reset</a>
            </div>
            <form method="get" action="{{ route('article') }}">
              <input type="hidden" name="q" value="{{ $filters['q'] }}">
              <input type="hidden" name="category" value="{{ $filters['category'] }}">
              <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
              <div class="filter-group">
                <label for="period-filter"><h4>Date Published</h4></label>
                <select id="period-filter" name="period">
                  <option value="all" @selected($filters['period'] === 'all')>Any time</option>
                  <option value="day" @selected($filters['period'] === 'day')>Last 24 hours</option>
                  <option value="week" @selected($filters['period'] === 'week')>Last 7 days</option>
                  <option value="month" @selected($filters['period'] === 'month')>Last 30 days</option>
                  <option value="half-year" @selected($filters['period'] === 'half-year')>Last 6 months</option>
                  <option value="year" @selected($filters['period'] === 'year')>Last year</option>
                </select>
              </div>
              <button class="apply-filters" type="submit">Apply Filters</button>
            </form>
          </div>

        </aside>

        <!-- Main content -->
        <div class="article-content">
          <div class="results-top">
            <div class="count-text">
              Showing <strong>{{ $articles->firstItem() ?? 0 }}–{{ $articles->lastItem() ?? 0 }}</strong> of {{ $articles->total() }} articles
              @if (!$articlesAvailable)
                <span> (configure the BusinessX article database to publish content)</span>
              @endif
            </div>
            <div class="results-controls">
              <form class="sort-wrap" method="get" action="{{ route('article') }}">
                <input type="hidden" name="q" value="{{ $filters['q'] }}">
                <input type="hidden" name="category" value="{{ $filters['category'] }}">
                <input type="hidden" name="period" value="{{ $filters['period'] }}">
                <label for="article-sort">Sort By:</label>
                <select id="article-sort" name="sort" onchange="this.form.submit()">
                  <option value="recent" @selected($filters['sort'] === 'recent')>Most Recent</option>
                  <option value="popular" @selected($filters['sort'] === 'popular')>Most Popular</option>
                  <option value="comments" @selected($filters['sort'] === 'comments')>Most Commented</option>
                  <option value="title" @selected($filters['sort'] === 'title')>Alphabetical</option>
                </select>
              </form>
              <div class="view-toggle">
                <button class="active" title="Grid View"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></button>
                <button title="List View"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/></svg></button>
              </div>
            </div>
          </div>

          <!-- Article cards grid -->
          <div class="article-grid-list">
            @forelse ($articles as $article)
              <article class="article-card">
                <a class="img" href="{{ route('articles.show', $article->article_id) }}">
                  @if ($article->tag_list)
                    <span class="badge-cat">{{ $article->tag_list[0] }}</span>
                  @endif
                  <img src="{{ $article->image_url }}" alt="{{ $article->article_title }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                </a>
                <div class="body">
                  <div class="meta-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    {{ $article->read_minutes }} min read
                  </div>
                  <h3><a href="{{ route('articles.show', $article->article_id) }}">{{ $article->article_title }}</a></h3>
                  <p class="desc">{{ $article->short_desc }}</p>
                  <div class="author">
                    <div class="avatar"></div>
                    <div>
                      <div class="name">{{ $article->author_name ?: 'BusinessX Editorial' }}</div>
                      <div class="date">{{ $article->published_at_label }}</div>
                    </div>
                  </div>
                  <a href="{{ route('articles.show', $article->article_id) }}" class="read-more">Read More <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
                </div>
              </article>
            @empty
              <p class="desc">
                @if ($articlesAvailable)
                  No published articles match those filters.
                @else
                  Articles will appear here after the BusinessX article database is configured.
                @endif
              </p>
            @endforelse
            @if (false)

            <article class="article-card">
              <div class="img img-1"><span class="badge-cat">Business Growth</span><img src="{{ asset('assets/img/article-1.jpg') }}" alt="Article image" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;"></div>
              <div class="body">
                <div class="meta-row">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  5 min read
                </div>
                <h3>Unlocking Global Markets: A Strategic Guide for UK Exporters in 2025</h3>
                <p class="desc">Discover the proven strategies UK businesses are using to break into new international markets this year.</p>
                <div class="author">
                  <div class="avatar"></div>
                  <div>
                    <div class="name">Sarah Mitchell</div>
                    <div class="date">15 Sep 2025</div>
                  </div>
                </div>
                <a href="{{ route('article-detail') }}" class="read-more">Read More <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
              </div>
            </article>

            <article class="article-card">
              <div class="img img-2 badge-success"><span class="badge-cat">Global Trade</span><img src="{{ asset('assets/img/article-2.jpg') }}" alt="Article image" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;"></div>
              <div class="body">
                <div class="meta-row">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  8 min read
                </div>
                <h3>The Rise of Sustainable Supply Chains: How UK Manufacturers Are Adapting</h3>
                <p class="desc">Net zero commitments are reshaping global supply chains. Here's what leading UK manufacturers are doing.</p>
                <div class="author">
                  <div class="avatar" style="background:linear-gradient(135deg,#1454B8,#5a6b5a);"></div>
                  <div>
                    <div class="name">James Patel</div>
                    <div class="date">12 Sep 2025</div>
                  </div>
                </div>
                <a href="#featured" class="read-more">Read More <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
              </div>
            </article>

            <article class="article-card">
              <div class="img img-3 badge-gold"><span class="badge-cat">Finance</span><img src="{{ asset('assets/img/article-3.jpg') }}" alt="Article image" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;"></div>
              <div class="body">
                <div class="meta-row">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  6 min read
                </div>
                <h3>Brexit Five Years On: Assessing the Impact on UK Trade Relationships</h3>
                <p class="desc">A retrospective analysis of how UK businesses have navigated new trade agreements since 2020.</p>
                <div class="author">
                  <div class="avatar" style="background:linear-gradient(135deg,#a89070,#706050);"></div>
                  <div>
                    <div class="name">Emma Thompson</div>
                    <div class="date">10 Sep 2025</div>
                  </div>
                </div>
                <a href="#featured" class="read-more">Read More <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
              </div>
            </article>

            <article class="article-card">
              <div class="img img-4 badge-purple"><span class="badge-cat">Technology</span><img src="{{ asset('assets/img/article-4.jpg') }}" alt="Article image" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;"></div>
              <div class="body">
                <div class="meta-row">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  7 min read
                </div>
                <h3>AI in International Trade: Transforming Cross-Border Operations</h3>
                <p class="desc">From customs documentation to predictive analytics, AI is revolutionising how UK firms trade globally.</p>
                <div class="author">
                  <div class="avatar" style="background:linear-gradient(135deg,#7090a8,#506070);"></div>
                  <div>
                    <div class="name">Michael Chen</div>
                    <div class="date">08 Sep 2025</div>
                  </div>
                </div>
                <a href="#featured" class="read-more">Read More <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
              </div>
            </article>

            <article class="article-card">
              <div class="img img-5"><span class="badge-cat">Markets</span><img src="{{ asset('assets/img/article-1.jpg') }}" alt="Article image" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;"></div>
              <div class="body">
                <div class="meta-row">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  9 min read
                </div>
                <h3>Emerging Markets 2025: Where UK Investors Should Look Next</h3>
                <p class="desc">From Southeast Asia to Latin America, here are the high-growth markets attracting UK capital.</p>
                <div class="author">
                  <div class="avatar" style="background:linear-gradient(135deg,#8b9a7a,#5a6b5a);"></div>
                  <div>
                    <div class="name">Olivia Brown</div>
                    <div class="date">05 Sep 2025</div>
                  </div>
                </div>
                <a href="#featured" class="read-more">Read More <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
              </div>
            </article>

            <article class="article-card">
              <div class="img img-6 badge-success"><span class="badge-cat">Success Story</span><img src="{{ asset('assets/img/article-2.jpg') }}" alt="Article image" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;"></div>
              <div class="body">
                <div class="meta-row">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  4 min read
                </div>
                <h3>From Local to Global: How ABC Manufacturing Expanded to 30+ Countries</h3>
                <p class="desc">The inside story of how a UK industrial firm became a global exporter in just over a decade.</p>
                <div class="author">
                  <div class="avatar" style="background:linear-gradient(135deg,#b89070,#706050);"></div>
                  <div>
                    <div class="name">David Wilson</div>
                    <div class="date">02 Sep 2025</div>
                  </div>
                </div>
                <a href="#featured" class="read-more">Read More <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
              </div>
            </article>

            @endif
          </div>

          <!-- Pagination -->
          @if ($articles->hasPages())
            <nav class="pagination" aria-label="Article pagination">
              @if ($articles->onFirstPage())
                <span aria-disabled="true" aria-label="Previous page">‹</span>
              @else
                <a href="{{ $articles->previousPageUrl() }}" rel="prev" aria-label="Previous page">‹</a>
              @endif
              @foreach ($articles->getUrlRange(max(1, $articles->currentPage() - 2), min($articles->lastPage(), $articles->currentPage() + 2)) as $pageNumber => $pageUrl)
                <a href="{{ $pageUrl }}" @class(['active' => $pageNumber === $articles->currentPage()]) @if ($pageNumber === $articles->currentPage()) aria-current="page" @endif>{{ $pageNumber }}</a>
              @endforeach
              @if ($articles->hasMorePages())
                <a href="{{ $articles->nextPageUrl() }}" rel="next" aria-label="Next page">›</a>
              @else
                <span aria-disabled="true" aria-label="Next page">›</span>
              @endif
            </nav>
          @endif

          <!-- Newsletter banner -->
          <div class="newsletter-banner">
            <div class="content">
              <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
              <div>
                <h3>Never Miss an Insight</h3>
                <p>Subscribe to our weekly newsletter for the latest trade insights.</p>
              </div>
            </div>
            <form action="{{ route('newsletter.subscribe') }}" method="POST">
              @csrf
              <input id="article-newsletter-email" name="email" type="email" aria-label="Email address" placeholder="Enter your email" value="{{ old('email') }}" maxlength="255" required>
              <button type="submit">Subscribe</button>
            </form>
            @if (session('newsletter_status'))
              <p role="status">{{ session('newsletter_status') }}</p>
            @endif
            @error('email')
              <p role="alert">{{ $message }}</p>
            @enderror
          </div>

        </div>
      </div>
    </div>
  </section>

  

  

  

  <script>
    // Make sidebar category list interactive
    document.querySelectorAll('.cat-item').forEach(c => {
      c.addEventListener('click', () => {
        document.querySelectorAll('.cat-item').forEach(x => {
          x.classList.remove('active');
          x.setAttribute('aria-pressed', 'false');
        });
        c.classList.add('active');
        c.setAttribute('aria-pressed', 'true');
      });
    });
  </script>

@endsection
