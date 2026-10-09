@extends('layouts.app')

@section('title')
Business for Sale &amp; Investors in UK - BusinessX
@endsection
@section('description')
BusinessX - The UK's leading business exchange network. Buy or sell a business, find investors, startups and mentors.
@endsection

@section('head')
  <link rel="stylesheet" href="{{ asset('css/chatbot.css?v=20261006') }}">
  <style>
    body { font-family: 'Inter', sans-serif; background: var(--white); }

    /* ============ Hero ============ */
    .bx-hero {
      position: relative;
      background: linear-gradient(100deg, rgba(16,40,74,0.94) 0%, rgba(16,40,74,0.86) 45%, rgba(20,45,82,0.72) 100%), url("{{ asset('assets/img/hero-bg.jpg') }}") center/cover no-repeat;
      color: var(--white);
      padding: 72px 0 80px;
      overflow: hidden;
    }
    .hero-grid {
      display: grid;
      grid-template-columns: 1.12fr 0.88fr;
      gap: 56px;
      align-items: center;
    }
    .hero-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(19,141,227,0.14);
      border: 1px solid rgba(19,141,227,0.4);
      color: var(--gold-400);
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      padding: 8px 16px;
      border-radius: 999px;
      margin-bottom: 22px;
    }
    .bx-hero h1 {
      font-size: clamp(30px, 4vw, 46px);
      font-weight: 800;
      line-height: 1.16;
      letter-spacing: -0.02em;
      text-transform: uppercase;
      margin-bottom: 18px;
    }
    .bx-hero h1 .hl { color: var(--gold-400); }
    .hero-sub {
      font-size: 16px;
      line-height: 1.7;
      color: rgba(255,255,255,0.82);
      max-width: 560px;
      margin-bottom: 14px;
    }
    .hero-sub strong { color: var(--gold-400); font-weight: 700; }
    .hero-create {
      margin-top: 34px;
    }
    .hero-create h3 { font-size: 19px; font-weight: 700; margin-bottom: 14px; }
    .hero-create-row { display: flex; gap: 0; max-width: 480px; box-shadow: var(--shadow-lg); border-radius: 10px; overflow: hidden; }
    .hero-create-row select {
      flex: 1;
      padding: 15px 16px;
      border: none;
      background: var(--white);
      color: var(--gray-700);
      font-size: 14px;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='%236B7280'%3E%3Cpath d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
      background-size: 12px;
      cursor: pointer;
    }
    .hero-create-row .btn-create {
      background: var(--gold-500);
      color: var(--navy-900);
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 0.04em;
      padding: 15px 26px;
      text-transform: uppercase;
      white-space: nowrap;
    }
    .hero-create-row .btn-create:hover { background: var(--gold-600); }
    .hero-trust { display: flex; align-items: center; gap: 10px; margin-top: 26px; font-size: 13px; color: rgba(255,255,255,0.7); }
    .hero-trust .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--green-500); }

    /* Register card */
    .reg-card {
      background: var(--white);
      border-radius: var(--radius-xl);
      box-shadow: 0 24px 60px rgba(0,0,0,0.35);
      overflow: hidden;
      max-width: 405px;
      justify-self: end;
      width: 100%;
    }
    .reg-card-head {
      background: var(--gold-500);
      color: var(--navy-900);
      text-align: center;
      padding: 16px;
      font-size: 16px;
      font-weight: 800;
      letter-spacing: 0.06em;
    }
    .reg-card-body { padding: 24px 22px 26px; }
    .reg-card-body .input-wrap {
      position: relative;
      margin-bottom: 12px;
    }
    .reg-card-body .input-ic {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      width: 17px;
      height: 17px;
      color: var(--gray-400);
      pointer-events: none;
    }
    .reg-card-body input, .reg-card-body select {
      width: 100%;
      padding: 12px 12px 12px 38px;
      border: 1px solid var(--gray-300);
      border-radius: 8px;
      font-size: 13.5px;
      color: var(--gray-800);
      background: var(--white);
    }
    .reg-card-body input:focus, .reg-card-body select:focus {
      outline: none;
      border-color: var(--gold-500);
      box-shadow: 0 0 0 3px rgba(19,141,227,0.18);
    }
    .reg-card-body .phone-wrap { display: flex; gap: 8px; }
    .reg-card-body .phone-wrap .cc,
    .reg-card-body .phone-wrap .phone-country-code {
      display: flex; align-items: center; gap: 4px;
      padding: 0 10px;
      border: 1px solid var(--gray-300);
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      color: var(--gray-700);
      background-color: var(--gray-50);
      flex-shrink: 0;
    }
    .reg-card-body .btn-submit {
      width: 100%;
      background: var(--navy-800);
      color: var(--white);
      padding: 14px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 0.03em;
      margin-top: 6px;
      text-transform: uppercase;
    }
    .reg-card-body .btn-submit:hover { background: var(--navy-700); }
    .reg-card-foot {
      text-align: center;
      font-size: 12.5px;
      color: var(--gray-500);
      margin-top: 14px;
    }
    .reg-card-foot a { color: var(--gold-600); font-weight: 600; }

    /* ============ Stat strip ============ */
    .stat-strip { background: var(--navy-800); border-top: 1px solid rgba(19,141,227,0.25); padding: 26px 0; }
    .stat-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; }
    .stat-item { display: flex; align-items: center; gap: 13px; justify-content: center; }
    .stat-item .ic {
      width: 44px; height: 44px;
      border-radius: 50%;
      background: rgba(19,141,227,0.12);
      color: var(--gold-400);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .stat-item .ic svg { width: 20px; height: 20px; }
    .stat-item .num { font-size: 22px; font-weight: 800; color: var(--gold-400); line-height: 1.15; }
    .stat-item .lbl { font-size: 12px; color: rgba(255,255,255,0.65); }

    /* ============ Sections shared ============ */
    .h-section { padding: 76px 0; }
    .h-section.soft { background: var(--gray-50); }
    .sec-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; margin-bottom: 38px; flex-wrap: wrap; }
    .sec-head .eyebrow2 {
      display: inline-block;
      font-size: 12px;
      font-weight: 800;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--gold-600);
      margin-bottom: 8px;
    }
    .sec-head h2 { font-size: clamp(26px, 3vw, 34px); font-weight: 800; color: var(--navy-900); letter-spacing: -0.01em; }
    .sec-head .sub { font-size: 15px; color: var(--gray-500); margin-top: 8px; }
    .sec-head .sub strong { color: var(--navy-700); }
    .sec-tools { display: flex; align-items: center; gap: 12px; }
    .view-all {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 14px;
      font-weight: 700;
      color: var(--gold-600);
      padding: 10px 4px;
    }
    .view-all:hover { color: var(--gold-500); }
    .view-all svg { width: 15px; height: 15px; }
    .car-arrow {
      width: 38px; height: 38px;
      border-radius: 50%;
      border: 1px solid var(--gray-300);
      background: var(--white);
      color: var(--navy-700);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
    }
    .car-arrow:hover { background: var(--navy-800); border-color: var(--navy-800); color: var(--white); }
    .car-arrow svg { width: 16px; height: 16px; }
    .cards-row {
      display: grid;
      grid-auto-flow: column;
      grid-auto-columns: calc(25% - 18px);
      gap: 24px;
      overflow-x: auto;
      scroll-behavior: smooth;
      padding-bottom: 8px;
      scrollbar-width: none;
    }
    .cards-row::-webkit-scrollbar { display: none; }

    /* ============ Why Business-X ============ */
    .why-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; }
    .why-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-xl);
      padding: 30px 26px;
      text-align: center;
      transition: all 0.25s ease;
      border-top: 3px solid transparent;
    }
    .why-card:hover { border-top-color: var(--gold-500); box-shadow: var(--shadow-lg); transform: translateY(-4px); }
    .why-card .pic {
      height: 150px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }
    .why-card .pic img { max-height: 150px; max-width: 170px; object-fit: contain; }
    .why-card h4 { font-size: 17px; font-weight: 700; color: var(--navy-900); margin-bottom: 10px; }
    .why-card p { font-size: 13.5px; color: var(--gray-500); line-height: 1.7; }

    /* ============ Business / Startup cards ============ */
    .bizx-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-lg);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: all 0.25s ease;
    }
    .bizx-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-4px); border-color: var(--gold-300); }
    .bizx-card .thumb { background: var(--gray-50); border-bottom: 1px solid var(--gray-100); }
    .bizx-card .thumb img { width: 100%; height: 165px; object-fit: cover; }
    .bizx-card .body { padding: 18px; display: flex; flex-direction: column; flex: 1; }
    .bizx-card .cat { font-size: 11px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: var(--gold-600); margin-bottom: 6px; }
    .bizx-card h3 { font-size: 15px; font-weight: 700; color: var(--navy-900); line-height: 1.45; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 43px; }
    .bizx-card .invest { font-size: 13px; color: var(--gray-600); margin-bottom: 12px; }
    .bizx-card .invest strong { color: var(--navy-900); font-weight: 700; }
    .bizx-card .meta {
      display: flex;
      flex-wrap: wrap;
      gap: 6px 14px;
      font-size: 12px;
      color: var(--gray-500);
      padding: 10px 0 14px;
      border-top: 1px dashed var(--gray-200);
      margin-top: auto;
    }
    .bizx-card .meta span { display: inline-flex; align-items: center; gap: 5px; }
    .bizx-card .meta svg { width: 13px; height: 13px; color: var(--gold-600); }
    .btn-card {
      display: block;
      width: 100%;
      text-align: center;
      padding: 11px;
      border-radius: 8px;
      border: 1.5px solid var(--navy-800);
      color: var(--navy-800);
      font-size: 13.5px;
      font-weight: 700;
      transition: all 0.2s;
    }
    .btn-card:hover { background: var(--gold-500); border-color: var(--gold-500); color: var(--navy-900); }

    /* ============ Investor / Mentor cards ============ */
    .person-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-lg);
      padding: 22px 20px;
      position: relative;
      display: flex;
      flex-direction: column;
      transition: all 0.25s ease;
    }
    .person-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-4px); border-color: var(--gold-300); }
    .plan-ribbon {
      position: absolute;
      top: 14px;
      left: 0;
      background: var(--gold-500);
      color: var(--navy-900);
      font-size: 10px;
      font-weight: 800;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      padding: 5px 14px 5px 12px;
      clip-path: polygon(0 0, 100% 0, calc(100% - 10px) 50%, 100% 100%, 0 100%);
    }
    .person-card .avatar-wrap {
      width: 84px; height: 84px;
      border-radius: 50%;
      overflow: hidden;
      margin: 6px auto 14px;
      border: 3px solid var(--gold-400);
      background: var(--gray-100);
    }
    .person-card .avatar-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .person-card h3 { font-size: 16px; font-weight: 700; color: var(--navy-900); text-align: center; }
    .person-card .company { font-size: 12.5px; color: var(--gray-500); text-align: center; margin: 3px 0 12px; }
    .person-card .meta {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 6px 14px;
      font-size: 12px;
      color: var(--gray-500);
      padding-bottom: 12px;
      border-bottom: 1px dashed var(--gray-200);
      margin-bottom: 12px;
    }
    .person-card .meta span { display: inline-flex; align-items: center; gap: 5px; }
    .person-card .meta svg { width: 13px; height: 13px; color: var(--gold-600); }
    .person-card .summary {
      font-size: 13px;
      color: var(--gray-600);
      line-height: 1.65;
      margin-bottom: 16px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .person-card .btn-card { margin-top: auto; }
    .sum-label { font-size: 11px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: var(--gray-400); display: block; margin-bottom: 4px; }

    /* ============ Popular opportunities chips ============ */
    .opp-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-xl);
      padding: 34px;
      box-shadow: var(--shadow-md);
    }
    .opp-card h3 { font-size: 19px; font-weight: 700; color: var(--navy-900); margin-bottom: 18px; padding-bottom: 12px; border-left: 4px solid var(--gold-500); padding-left: 14px; }
    .chip-cloud { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 30px; }
    .chip-cloud:last-child { margin-bottom: 0; }
    .chip {
      display: inline-block;
      background: var(--gold-100);
      color: var(--gray-800);
      font-size: 13px;
      font-weight: 600;
      padding: 9px 18px;
      border-radius: 999px;
      border: 1px solid var(--gold-200);
      transition: all 0.2s;
    }
    .chip:hover { background: var(--gold-500); border-color: var(--gold-500); color: var(--navy-900); transform: translateY(-2px); }

    /* ============ Bx Insights ============ */
    .art-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
    .art-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-lg);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: all 0.25s ease;
    }
    .art-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-4px); }
    .art-card .thumb { position: relative; }
    .art-card .thumb img { width: 100%; height: 175px; object-fit: cover; }
    .art-card .date {
      position: absolute;
      left: 14px;
      bottom: -14px;
      background: var(--gold-500);
      color: var(--navy-900);
      font-size: 11px;
      font-weight: 800;
      padding: 7px 12px;
      border-radius: 6px;
      box-shadow: var(--shadow-md);
      letter-spacing: 0.04em;
    }
    .art-card .body { padding: 24px 18px 18px; display: flex; flex-direction: column; flex: 1; }
    .art-card h3 { font-size: 15px; font-weight: 700; color: var(--navy-900); line-height: 1.5; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .art-card p { font-size: 13px; color: var(--gray-500); line-height: 1.65; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .art-card .author { display: flex; align-items: center; gap: 10px; margin-top: 16px; padding-top: 14px; border-top: 1px dashed var(--gray-200); font-size: 12.5px; color: var(--gray-600); }
    .art-card .author .a-avatar {
      width: 30px; height: 30px;
      border-radius: 50%;
      background: var(--navy-800);
      color: var(--gold-400);
      font-size: 12px;
      font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }

    /* ============ Membership plans ============ */
    .plans-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; max-width: 1080px; margin: 0 auto; align-items: stretch; }
    .planx-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-xl);
      padding: 34px 30px;
      text-align: center;
      position: relative;
      display: flex;
      flex-direction: column;
      transition: all 0.25s ease;
    }
    .planx-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-4px); }
    .planx-card.featured { border: 2px solid var(--gold-500); box-shadow: var(--shadow-lg); }
    .planx-badge {
      position: absolute;
      top: -16px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--navy-900);
      color: var(--gold-400);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      padding: 8px 20px;
      border-radius: 999px;
      white-space: nowrap;
    }
    .planx-card .p-name { font-size: 13px; font-weight: 800; letter-spacing: 0.16em; text-transform: uppercase; color: var(--gray-600); }
    .planx-card.featured .p-name { color: var(--gold-600); }
    .planx-card .p-sub { font-size: 13px; color: var(--gray-500); margin-top: 6px; }
    .planx-card .p-price { margin: 20px 0 4px; }
    .planx-card .p-price .amt { font-size: 42px; font-weight: 800; color: var(--navy-900); letter-spacing: -0.02em; }
    .planx-card.featured .p-price .amt { color: var(--gold-600); }
    .planx-card .p-price .per { font-size: 14px; color: var(--gray-500); font-weight: 600; }
    .planx-card .p-note { font-size: 12px; color: var(--gray-400); margin-bottom: 20px; }
    .planx-card ul { text-align: left; margin: 0 0 24px; display: flex; flex-direction: column; gap: 11px; }
    .planx-card ul li { display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; color: var(--gray-700); }
    .planx-card ul li .ck {
      width: 18px; height: 18px;
      border-radius: 50%;
      background: var(--gold-100);
      color: var(--gold-600);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .planx-card ul li .ck svg { width: 10px; height: 10px; }
    .planx-card .btn-card { margin-top: auto; }
    .planx-card.featured .btn-card { background: var(--gold-500); border-color: var(--gold-500); color: var(--navy-900); }
    .planx-card.featured .btn-card:hover { background: var(--gold-600); border-color: var(--gold-600); }

    /* ============ Testimonials ============ */
    .test-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; }
    .test-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-xl);
      padding: 30px 26px;
      position: relative;
      transition: all 0.25s ease;
    }
    .test-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-4px); }
    .test-card .qmark {
      font-family: Georgia, serif;
      font-size: 64px;
      line-height: 1;
      color: var(--gold-300);
      height: 40px;
    }
    .test-card p { font-size: 14px; color: var(--gray-600); line-height: 1.75; margin-bottom: 20px; }
    .test-card .who { display: flex; align-items: center; gap: 13px; }
    .test-card .who img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid var(--gold-300); }
    .test-card .who .n { font-size: 14px; font-weight: 700; color: var(--navy-900); }
    .test-card .who .r { font-size: 12px; color: var(--gray-500); }
    .test-stars { display: inline-flex; gap: 2px; color: var(--gold-500); margin-bottom: 12px; }
    .test-stars svg { width: 14px; height: 14px; }

    /* ============ Newsletter ============ */
    .news-band {
      position: relative;
      background: var(--navy-900);
      color: var(--white);
      padding: 60px 0;
      overflow: hidden;
    }
    .news-band::after {
      content: 'BX';
      position: absolute;
      right: -30px;
      bottom: -70px;
      font-size: 300px;
      font-weight: 800;
      color: rgba(19,141,227,0.05);
      line-height: 1;
      pointer-events: none;
    }
    .news-grid { display: grid; grid-template-columns: 0.9fr 1.1fr; gap: 48px; align-items: center; position: relative; z-index: 2; }
    .news-grid h3 { font-size: clamp(24px, 2.6vw, 30px); font-weight: 800; margin-bottom: 10px; }
    .news-grid h3 .hl { color: var(--gold-400); }
    .news-grid .n-sub { font-size: 14.5px; color: rgba(255,255,255,0.7); }
    .news-form { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .news-form .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }
    .news-form .news-field { display: flex; flex-direction: column; gap: 6px; }
    .news-form input,
    .news-form .phone-country-code {
      width: 100%;
      padding: 14px 16px;
      border-radius: 8px;
      border: 1px solid rgba(255,255,255,0.18);
      background: rgba(255,255,255,0.06);
      color: var(--white);
      font-size: 14px;
    }
    .news-form input::placeholder { color: rgba(255,255,255,0.5); }
    .news-form .phone-country-code {
      flex: 0 0 40%;
      width: 40%;
      min-height: 50px;
      padding-right: 36px;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='%23ffffff'%3E%3Cpath d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      background-size: 12px;
      color: var(--white);
    }
    .news-form input:focus,
    .news-form .phone-country-code:focus { outline: none; border-color: var(--gold-500); background-color: rgba(255,255,255,0.09); }
    .news-form .news-field-error { color: #fecaca; font-size: 12px; }
    .news-feedback {
      grid-column: 1 / -1;
      margin: 0;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 14px;
      line-height: 1.5;
    }
    .news-feedback-success { background: #ecfdf5; color: #166534; }
    .news-feedback-error { background: #fef2f2; color: #991b1b; }
    .news-form .btn-sub {
      grid-column: 1 / -1;
      justify-self: start;
      background: var(--gold-500);
      color: var(--navy-900);
      padding: 14px 34px;
      border-radius: 999px;
      font-size: 14px;
      font-weight: 800;
      letter-spacing: 0.03em;
    }
    .news-form .btn-sub:hover { background: var(--gold-600); }

    /* ============ Services strip ============ */
    .services-strip { background: var(--white); border-bottom: 1px solid var(--gray-200); }
    .services-row { display: grid; grid-template-columns: repeat(4, 1fr); }
    .service-item {
      display: flex; align-items: flex-start; gap: 14px;
      padding: 28px 24px;
      border-left: 1px solid var(--gray-200);
      transition: background 0.2s;
    }
    .service-item:first-child { border-left: none; padding-left: 0; }
    .service-item:hover { background: var(--gray-50); }
    .service-item .ic {
      width: 46px; height: 46px;
      border-radius: 12px;
      background: var(--gold-100);
      color: var(--gold-600);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .service-item .ic svg { width: 22px; height: 22px; }
    .service-item h4 { font-size: 15px; font-weight: 700; color: var(--navy-900); margin-bottom: 4px; }
    .service-item p { font-size: 12.5px; color: var(--gray-500); line-height: 1.55; }

    /* ============ Upcoming & Past Events (dark band) ============ */
    .events-band {
      background: var(--navy-900);
      border-top: 1px solid rgba(19,141,227,0.25);
      padding: 76px 0;
    }
    .events-band .eyebrow2 { color: var(--gold-400); }
    .events-band .sec-head h2 { color: var(--white); }
    .events-band .sec-head .sub { color: rgba(255,255,255,0.6); }
    .events-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 22px; }
    .event-card {
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: var(--radius-lg);
      padding: 24px;
      display: flex;
      gap: 20px;
      transition: all 0.25s ease;
    }
    .event-card:hover {
      border-color: rgba(19,141,227,0.55);
      background: rgba(255,255,255,0.07);
      transform: translateY(-3px);
    }
    .event-date {
      flex-shrink: 0;
      width: 76px;
      height: 88px;
      background: var(--gold-500);
      border-radius: 10px;
      color: var(--navy-900);
      display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .event-date .d { font-size: 22px; font-weight: 800; line-height: 1.1; }
    .event-date .m { font-size: 11px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-top: 4px; }
    .event-date .y { font-size: 10.5px; font-weight: 700; opacity: 0.72; margin-top: 1px; }
    .event-card h3 { font-size: 16.5px; font-weight: 700; color: var(--white); margin-bottom: 6px; line-height: 1.4; }
    .event-card .venue {
      display: flex; align-items: center; gap: 6px;
      font-size: 12.5px; font-weight: 600; color: var(--gold-400);
      margin-bottom: 10px;
    }
    .event-card .venue svg { width: 13px; height: 13px; flex-shrink: 0; }
    .event-card p {
      font-size: 12.5px; color: rgba(255,255,255,0.62); line-height: 1.65;
      margin-bottom: 14px;
      display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    .btn-event {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--gold-500); color: var(--navy-900);
      font-size: 11.5px; font-weight: 800; letter-spacing: 0.07em; text-transform: uppercase;
      padding: 9px 20px; border-radius: 999px;
      transition: all 0.2s;
    }
    .btn-event:hover { background: var(--gold-400); }
    .btn-event svg { width: 13px; height: 13px; }

    /* ============ Top Franchise Opportunities (dark band) ============ */
    .franchise-band {
      background: var(--navy-800);
      border-top: 1px solid rgba(19,141,227,0.25);
      padding: 76px 0;
    }
    .franchise-band .eyebrow2 { color: var(--gold-400); }
    .franchise-band .sec-head h2 { color: var(--white); }
    .franchise-band .sec-head .sub { color: rgba(255,255,255,0.6); }
    .franchise-band .view-all { color: var(--gold-400); }
    .franchise-band .view-all:hover { color: var(--gold-500); }
    .franchise-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }
    .fran-card {
      background: var(--white);
      border-radius: var(--radius-lg);
      overflow: hidden;
      display: flex; flex-direction: column;
      transition: all 0.25s ease;
    }
    .fran-card:hover { transform: translateY(-4px); box-shadow: 0 22px 46px rgba(0,0,0,0.4); }
    .fran-card .thumb {
      height: 150px;
      background: var(--gray-50);
      border-bottom: 1px solid var(--gray-100);
      display: flex; align-items: center; justify-content: center;
      padding: 20px;
    }
    .fran-card .thumb img { max-height: 100%; max-width: 100%; object-fit: contain; }
    .fran-card .body { padding: 18px; display: flex; flex-direction: column; flex: 1; }
    .fran-card .cat { font-size: 11px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: var(--gold-600); margin-bottom: 5px; }
    .fran-card h3 { font-size: 16px; font-weight: 700; color: var(--navy-900); margin-bottom: 12px; }
    .fran-card .specs {
      display: flex; flex-direction: column; gap: 8px;
      font-size: 12.5px;
      padding-bottom: 13px; margin-bottom: 12px;
      border-bottom: 1px dashed var(--gray-200);
    }
    .fran-card .specs .row { display: flex; justify-content: space-between; gap: 10px; }
    .fran-card .specs .k { color: var(--gray-500); }
    .fran-card .specs .v { font-weight: 700; color: var(--navy-800); text-align: right; }
    .fran-card .locs { font-size: 11.5px; color: var(--gray-500); line-height: 1.55; margin-bottom: 14px; }
    .fran-card .btn-card { margin-top: auto; }

    /* ============ Did You Find CTA (gold band) ============ */
    .cta-band {
      position: relative;
      background: linear-gradient(115deg, var(--gold-500) 0%, var(--gold-600) 100%);
      padding: 62px 0;
      overflow: hidden;
    }
    .cta-band::after {
      content: 'BX';
      position: absolute;
      right: -20px;
      bottom: -74px;
      font-size: 280px;
      font-weight: 800;
      color: rgba(16,40,74,0.06);
      line-height: 1;
      pointer-events: none;
    }
    .cta-inner {
      display: grid;
      grid-template-columns: 1.15fr 0.85fr;
      gap: 40px;
      align-items: center;
      position: relative; z-index: 2;
    }
    .cta-band h2 {
      font-size: clamp(24px, 3vw, 34px);
      font-weight: 800;
      color: var(--navy-900);
      text-transform: uppercase;
      letter-spacing: -0.01em;
      margin-bottom: 8px;
    }
    .cta-band .cta-sub { font-size: 16px; font-weight: 600; color: rgba(16,40,74,0.75); }
    .cta-form {
      display: flex;
      background: var(--white);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 18px 42px rgba(16,40,74,0.2);
      max-width: 520px;
      justify-self: end;
      width: 100%;
    }
    .cta-form select {
      flex: 1;
      border: none;
      padding: 16px 18px;
      font-size: 14px;
      color: var(--gray-700);
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' fill='%236B7280'%3E%3Cpath d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      background-size: 12px;
      cursor: pointer;
      min-width: 0;
    }
    .cta-form select:focus { outline: none; }
    .cta-form button {
      background: var(--navy-900);
      color: var(--gold-400);
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      padding: 16px 28px;
      white-space: nowrap;
      transition: background 0.2s;
    }
    .cta-form button:hover { background: var(--navy-800); }

    /* ============ Responsive (added sections) ============ */
    @media (max-width: 1080px) {
      .services-row { grid-template-columns: repeat(2, 1fr); }
      .service-item { border-top: 1px solid var(--gray-200); padding-left: 24px; }
      .service-item:nth-child(-n+2) { border-top: none; }
      .service-item:nth-child(3) { border-left: none; padding-left: 0; }
      .events-grid { grid-template-columns: 1fr; }
      .franchise-grid { grid-template-columns: repeat(2, 1fr); }
      .cta-inner { grid-template-columns: 1fr; gap: 26px; }
      .cta-form { justify-self: start; }
    }
    @media (max-width: 640px) {
      .services-row { grid-template-columns: 1fr; }
      .service-item { border-left: none; border-top: 1px solid var(--gray-200); padding: 20px 0; }
      .service-item:first-child { border-top: none; }
      .franchise-grid { grid-template-columns: 1fr; }
      .event-card { flex-direction: column; }
      .cta-form { flex-direction: column; gap: 10px; background: transparent; box-shadow: none; }
      .cta-form select { border-radius: 8px; border: 1px solid var(--gray-300); }
      .cta-form button { border-radius: 8px; }
    }

    /* ============ Responsive ============ */
    @media (max-width: 1080px) {
      .hero-grid { grid-template-columns: 1fr; gap: 40px; }
      .reg-card { justify-self: start; max-width: 480px; }
      .stat-grid { grid-template-columns: repeat(3, 1fr); }
      .why-grid { grid-template-columns: repeat(2, 1fr); }
      .cards-row { grid-auto-columns: calc(33.33% - 16px); }
      .art-grid { grid-template-columns: repeat(2, 1fr); }
      .plans-grid { grid-template-columns: 1fr; max-width: 460px; }
      .test-grid { grid-template-columns: 1fr; max-width: 560px; margin: 0 auto; }
      .news-grid { grid-template-columns: 1fr; gap: 28px; }
      .footer-main-layout { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
      .h-section { padding: 54px 0; }
      .bx-hero { padding: 52px 0 60px; }
      .hero-create-row { flex-direction: column; gap: 10px; background: transparent; box-shadow: none; }
      .hero-create-row select { border-radius: 8px; }
      .hero-create-row .btn-create { border-radius: 8px; text-align: center; }
      .stat-grid { grid-template-columns: repeat(2, 1fr); }
      .stat-item { justify-content: flex-start; }
      .why-grid { grid-template-columns: 1fr; }
      .cards-row { grid-auto-columns: 86%; }
      .art-grid { grid-template-columns: 1fr; }
      .opp-card { padding: 24px 20px; }
      .news-form { grid-template-columns: 1fr; }
      .sec-head { flex-direction: column; align-items: flex-start; }
    }
  </style>
@endsection

@section('content')


  
  

  <!-- ====== Hero ====== -->
  <section class="bx-hero">
    <div class="container">
      <div class="hero-grid">
        <div>
          <span class="hero-eyebrow">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            BusinessX - Exit, Exchange, Excel
          </span>
          <h1>Leading <span class="hl">Business</span> Exchange Network</h1>
          <p class="hero-sub"><strong>1500+ Businesses, 1400+ Startups, 1800+ Investors, 200+ Mentors and 50+ Incubators</strong> are registered in our community so far!</p>
          <div class="hero-create">
            <h3>Why wait, create your profile now</h3>
            <form class="hero-create-row" id="heroCreateForm">
              <select id="heroProfile" required>
                <option value="">Select a profile...</option>
                <option value="business">Business | Looking To Sell</option>
                <option value="startup">Startup | Looking For Funds</option>
                <option value="investor">Investor | Looking To Invest/Buy</option>
                <option value="mentor">Mentor | Looking To Guide/Coach</option>
              </select>
              <button type="submit" class="btn-create">Create Profile</button>
            </form>
          </div>
          <div class="hero-trust">
            <span class="dot"></span>
            Verified members only &nbsp;•&nbsp; Confidential &amp; secure &nbsp;•&nbsp; Free registration
          </div>
        </div>
        <form class="reg-card" id="heroRegCard" method="post" action="{{ route('registration.quick-store') }}">
          @csrf
          <input type="hidden" name="_quick_registration" value="1">
          <div class="reg-card-head">Quick register</div>
          <div class="reg-card-body">
            @if (session('verification_notice'))
              <p role="status" style="margin-bottom:16px;padding:12px;border-radius:6px;background:#ecfdf5;color:#166534;">{{ session('verification_notice') }}</p>
            @endif
            @if (session('verification_error'))
              <p role="alert" style="margin-bottom:16px;padding:12px;border-radius:6px;background:#fef2f2;color:#991b1b;">{{ session('verification_error') }}</p>
            @endif
            @if (old('_quick_registration') && $errors->any())
              <p role="alert" style="margin-bottom:16px;padding:12px;border-radius:6px;background:#fef2f2;color:#991b1b;">Please correct the highlighted fields.</p>
            @endif
            <div class="input-wrap">
              <svg class="input-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              <select id="cardProfile" name="profile_type" required>
                <option value="">Select a profile...</option>
                <option value="business" @selected(old('profile_type') === 'business')>Business | Looking To Sell</option>
                <option value="startup" @selected(old('profile_type') === 'startup')>Startup | Looking For Funds</option>
                <option value="investor" @selected(old('profile_type') === 'investor')>Investor | Looking To Invest/Buy</option>
                <option value="mentor" @selected(old('profile_type') === 'mentor')>Mentor | Looking To Guide/Coach</option>
              </select>
            </div>
            <div class="input-wrap">
              <svg class="input-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Your Name" maxlength="100" autocomplete="name" required>
              @error('name') <p role="alert" style="margin:6px 0;color:#991b1b;font-size:12px;">{{ $message }}</p> @enderror
            </div>
            <div class="input-wrap">
              <div class="phone-wrap phone-input-group">
                @include('components.phone-country-code')
                <input type="tel" name="mobile" value="{{ old('mobile') }}" placeholder="Enter Your Mobile No." style="padding-left:12px;" maxlength="20" autocomplete="tel" required>
              </div>
              @error('mobile') <p role="alert" style="margin:6px 0;color:#991b1b;font-size:12px;">{{ $message }}</p> @enderror
            </div>
            <div class="input-wrap">
              <svg class="input-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Your Email ID" maxlength="255" autocomplete="email" required>
              @error('email') <p role="alert" style="margin:6px 0;color:#991b1b;font-size:12px;">{{ $message }}</p> @enderror
            </div>
            <div class="input-wrap">
              <svg class="input-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
              <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Enter Company Name" maxlength="255" autocomplete="organization" required>
              @error('company_name') <p role="alert" style="margin:6px 0;color:#991b1b;font-size:12px;">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-submit">Submit</button>
            <div class="reg-card-foot">Already a member? <a href="{{ route('login') }}">Sign In</a></div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- ====== Stats strip ====== -->
  <section class="stat-strip">
    <div class="container">
      <div class="stat-grid">
        <div class="stat-item">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg></span>
          <div><div class="num">1,500+</div><div class="lbl">Businesses</div></div>
        </div>
        <div class="stat-item">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg></span>
          <div><div class="num">1,400+</div><div class="lbl">Startups</div></div>
        </div>
        <div class="stat-item">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v12M8 10h8M8 14h8"/></svg></span>
          <div><div class="num">1,800+</div><div class="lbl">Investors</div></div>
        </div>
        <div class="stat-item">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
          <div><div class="num">200+</div><div class="lbl">Mentors</div></div>
        </div>
        <div class="stat-item">
          <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></span>
          <div><div class="num">120+</div><div class="lbl">Countries</div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== BusinessX Services ====== -->
  <section class="services-strip">
    <div class="container">
      <div class="services-row">
        <div class="service-item">
          <span class="ic">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </span>
          <div>
            <h4>Business Valuation</h4>
            <p>Know what your business is truly worth before you take it to market.</p>
          </div>
        </div>
        <div class="service-item">
          <span class="ic">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          </span>
          <div>
            <h4>Business Plan</h4>
            <p>Investor-ready plans that clearly present your growth story and numbers.</p>
          </div>
        </div>
        <div class="service-item">
          <span class="ic">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </span>
          <div>
            <h4>Due Diligence</h4>
            <p>Verified information and complete checks for confident decision making.</p>
          </div>
        </div>
        <div class="service-item">
          <span class="ic">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </span>
          <div>
            <h4>Certified Business Broker</h4>
            <p>Expert brokers to guide your sale, exchange or acquisition end to end.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== Why Business-X ====== -->
  <section class="h-section soft" id="why">
    <div class="container">
      <div class="sec-head" style="justify-content:center;text-align:center;">
        <div style="max-width:640px;">
          <span class="eyebrow2">Why Choose Us</span>
          <h2>Why Business-X</h2>
          <p class="sub">BusinessX - Exit, Exchange, Excel</p>
        </div>
      </div>
      <div class="why-grid">
        <div class="why-card">
          <div class="pic"><img src="{{ asset('assets/img/business-ex.jpg') }}" alt="Single Platform For Entire Ecosystem"></div>
          <h4>Single Platform For Entire Ecosystem</h4>
          <p>An online interactive platform connecting Businesses, Startups, Investors, Mentors, Lenders, Incubators and Brokers, across industries and geographies.</p>
        </div>
        <div class="why-card">
          <div class="pic"><img src="{{ asset('assets/img/why-scale-up.svg') }}" alt="Help Companies Scale Up"></div>
          <h4>Help Companies Scale Up</h4>
          <p>BusinessX offers a platform for high-growth potential companies to promote their investment opportunities to investors or to gain expertise from renowned mentors, in a secure environment.</p>
        </div>
        <div class="why-card">
          <div class="pic"><img src="{{ asset('assets/img/why-connected-network.svg') }}" alt="A Connected Network"></div>
          <h4>A Connected Network</h4>
          <p>Provides an opportunity to connect to a broader network to share deals and grow your connections, while keeping your important details confidential.</p>
        </div>
        <div class="why-card">
          <div class="pic"><img src="{{ asset('assets/img/why-customizable.svg') }}" alt="Put Your Mark On It"></div>
          <h4>Put Your Mark On It</h4>
          <p>Our platform is fully customizable. You decide the information you want to share. Automatically receive recommendations based on your profile and preferences.</p>
        </div>
        <div class="why-card">
          <div class="pic"><img src="{{ asset('assets/img/why-authentic-community.svg') }}" alt="Authentic Community"></div>
          <h4>Authentic Community</h4>
          <p>Meet and interact with genuine and interested customers registered with BusinessX, and deepen relationships that help your business grow.</p>
        </div>
        <div class="why-card">
          <div class="pic"><img src="{{ asset('assets/img/why-portfolio.svg') }}" alt="Portfolio Management Made Easy"></div>
          <h4>Portfolio Management Made Easy</h4>
          <p>Keep track of all your conversations and proposals in one place. Track user preferences (location, industry, investment) and receive curated opportunities.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== Business For Sale Opportunities ====== -->
  <section class="h-section" id="business">
    <div class="container">
      <div class="sec-head">
        <div>
          <span class="eyebrow2">Bx Listing</span>
          <h2>Business For Sale Opportunities</h2>
          <p class="sub">BusinessX currently has <strong>{{ number_format($businessOpportunityCount) }} active business opportunities</strong></p>
        </div>
        <div class="sec-tools">
         <a href="{{ route('business-listing') }}" class="view-all">View All
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
          <button class="car-arrow" data-car-prev="bizRow" aria-label="Previous">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          </button>
          <button class="car-arrow" data-car-next="bizRow" aria-label="Next">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>
        </div>
      </div>
      <div class="cards-row" id="bizRow">
        @forelse ($businessOpportunities as $business)
          <article class="bizx-card" data-business-opportunity>
            <div class="thumb"><img src="{{ $business->image_url }}" alt="{{ $business->display_title }}"></div>
            <div class="body">
              <span class="cat">{{ $business->display_industry }}</span>
              <h3>{{ $business->display_title }}</h3>
              @if ((float) $business->inv_asking_price > 0)
                <div class="invest">Seeking Investment: <strong>&#163; {{ number_format((float) $business->inv_asking_price) }}</strong></div>
              @endif
              <div class="meta">
                <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>Phone</span>
                <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Email</span>
                @if ($business->ofc_city)
                  <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>{{ $business->ofc_city }}</span>
                @endif
              </div>
              @auth
                <a href="{{ route('profile-details', ['type' => 'business', 'id' => $business->business_id]) }}" class="btn-card">Contact Business</a>
              @else
                <a href="{{ route('login') }}" class="btn-card">Contact Business</a>
              @endauth
            </div>
          </article>
        @empty
          <p>No business opportunities are available at the moment.</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- ====== Upcoming & Past Events ====== -->
  {{--<section class="events-band" id="events">
    <div class="container">
      <div class="sec-head">
        <div>
          <span class="eyebrow2">BusinessX Events</span>
          <h2>Upcoming &amp; Past Events</h2>
          <p class="sub">Expos, summits and awards that connect brands, investors and entrepreneurs</p>
        </div>
      </div>
      <div class="events-grid">
        <div class="event-card">
          <div class="event-date">
            <span class="d">10-11</span>
            <span class="m">Oct</span>
            <span class="y">2026</span>
          </div>
          <div class="event-body">
            <h3>Franchise India 2026 Mumbai</h3>
            <span class="venue">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              Jio World Convention Centre, Mumbai
            </span>
            <p>The definitive epicentre for franchise, retail, and F&amp;B leaders. Designed to unite top-tier brands with astute investors and ambitious entrepreneurs, this mega-event accelerates strategic partnerships, fuels retail expansion, and empowers decision-makers with unmatched industry foresight.</p>
            <a href="https://www.franchiseindia.com" class="btn-event">Register Now
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
          </div>
        </div>
        <div class="event-card">
          <div class="event-date">
            <span class="d">27</span>
            <span class="m">Oct</span>
            <span class="y">2026</span>
          </div>
          <div class="event-body">
            <h3>Entrepreneur Game Changer Summit 2026</h3>
            <span class="venue">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              Rajasthan International Centre, Jaipur
            </span>
            <p>Entrepreneur Game Changer Awards 2026 | State Honours celebrate entrepreneurs and businesses driving high-impact change across India. These awards recognise visionary leaders who are building innovative, resilient, and scalable ventures that strengthen regional economies.</p>
            <a href="https://www.franchiseindia.com" class="btn-event">Register Now
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
          </div>
        </div>
        <div class="event-card">
          <div class="event-date">
            <span class="d">28-29</span>
            <span class="m">Nov</span>
            <span class="y">2026</span>
          </div>
          <div class="event-body">
            <h3>FROEXPO Gujarat</h3>
            <span class="venue">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              Mahatma Mandir, Convention and Exhibition
            </span>
            <p>The 146th edition of India's largest franchise and retail opportunity expo returns to Gandhinagar, Gujarat in 2026. This premier business and tradeshow serves as a crucial platform for individuals and entities looking to explore and engage with the thriving franchise and retail sectors.</p>
            <a href="https://www.franchiseindia.com" class="btn-event">Register Now
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
          </div>
        </div>
        <div class="event-card">
          <div class="event-date">
            <span class="d">1-2</span>
            <span class="m">Dec</span>
            <span class="y">2026</span>
          </div>
          <div class="event-body">
            <h3>Indian Restaurant Congress &amp; Awards 2026</h3>
            <span class="venue">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              Bharat Mandapam, New Delhi
            </span>
            <p>India's premier platform for the restaurant and foodservice industry. The 15th edition brings together the nation's top restaurateurs, chefs, and hospitality leaders — 2 days with 2000+ delegates, 500+ brands, 75+ exhibitors, and 150+ awards.</p>
            <a href="https://www.franchiseindia.com" class="btn-event">Register Now
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>--}}

  <!-- ====== Featured Investors ====== -->
  <section class="h-section soft" id="investors">
    <div class="container">
      <div class="sec-head">
        <div>
          <span class="eyebrow2">Bx Listing</span>
          <h2>Featured Investors</h2>
          <p class="sub">BusinessX currently has <strong>{{ number_format($verifiedInvestorCount) }} verified investors</strong></p>
        </div>
        <div class="sec-tools">
         <a href="{{ route('investor-listing') }}" class="view-all">View All
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
          <button class="car-arrow" data-car-prev="invRow" aria-label="Previous">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          </button>
          <button class="car-arrow" data-car-next="invRow" aria-label="Next">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>
        </div>
      </div>
      <div class="cards-row" id="invRow">
        @forelse ($featuredInvestors as $investor)
          <article class="person-card" data-featured-investor>
            <span class="plan-ribbon">Verified</span>
            <div class="avatar-wrap"><img src="{{ $investor->image_url }}" alt="{{ $investor->display_name }}"></div>
            <h3>{{ $investor->display_name }}</h3>
            <div class="company">{{ $investor->display_company }}</div>
            <div class="meta">
              <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>Phone</span>
              <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Email</span>
              @if ($investor->display_city)
                <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>{{ $investor->display_city }}</span>
              @endif
            </div>
            <span class="sum-label">Summary</span>
            <p class="summary">{{ $investor->display_summary ?: 'Investor supporting business growth and development.' }}</p>
            @auth
              <a href="{{ route('profile-details', ['type' => 'investor', 'id' => $investor->investor_id]) }}" class="btn-card">Send Proposal</a>
            @else
              <a href="{{ route('login') }}" class="btn-card">Send Proposal</a>
            @endauth
          </article>
        @empty
          <p>No verified investors are available at the moment.</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- ====== Top Franchise Opportunities ====== -->
  {{--<section class="franchise-band" id="franchise">
    <div class="container">
      <div class="sec-head">
        <div>
          <span class="eyebrow2">Top Franchise Opportunities</span>
          <h2>Franchise Brands Looking To Expand</h2>
          <p class="sub">Explore proven franchise brands and their investment requirements</p>
        </div>
        <div class="sec-tools">
          <a href="{{ route('business-listing') }}" class="view-all">View All
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
      </div>
      <div class="franchise-grid">
        <div class="fran-card">
          <div class="thumb"><img src="{{ asset('assets/img/franchise-kathi.jpg') }}" alt="Kathi Junction"></div>
          <div class="body">
            <span class="cat">Quick Service Restaurants</span>
            <h3>Kathi Junction</h3>
            <div class="specs">
              <div class="row"><span class="k">Investment Range</span><span class="v">&#8377;5 Lakh - &#8377;10 Lakh</span></div>
              <div class="row"><span class="k">Space Required</span><span class="v">100 - 1500 Sq.ft</span></div>
            </div>
            <p class="locs">Delhi, Haryana, Himachal Pradesh, +16 More</p>
            <a href="https://www.franchiseindia.com" class="btn-card">Know More</a>
          </div>
        </div>
        <div class="fran-card">
          <div class="thumb"><img src="{{ asset('assets/img/franchise-podar.jpg') }}" alt="Podar Smarter Schools"></div>
          <div class="body">
            <span class="cat">Schools</span>
            <h3>Podar Smarter Schools</h3>
            <div class="specs">
              <div class="row"><span class="k">Investment Range</span><span class="v">&#8377;2 Cr - &#8377;5 Cr</span></div>
              <div class="row"><span class="k">Space Required</span><span class="v">65000 - 90000 Sq.ft</span></div>
            </div>
            <p class="locs">Haryana, Rajasthan, Chhattisgarh, +16 More</p>
            <a href="https://www.franchiseindia.com" class="btn-card">Know More</a>
          </div>
        </div>
        <div class="fran-card">
          <div class="thumb"><img src="{{ asset('assets/img/franchise-sankalp.jpg') }}" alt="Sankalp Group"></div>
          <div class="body">
            <span class="cat">Fine Dine Restaurants</span>
            <h3>Sankalp Group</h3>
            <div class="specs">
              <div class="row"><span class="k">Investment Range</span><span class="v">&#8377;50 Lakh - &#8377;1 Cr</span></div>
              <div class="row"><span class="k">Space Required</span><span class="v">1500 - 2500 Sq.ft</span></div>
            </div>
            <p class="locs">Delhi, Haryana, Himachal Pradesh, Punjab, +11 More</p>
            <a href="https://www.franchiseindia.com" class="btn-card">Know More</a>
          </div>
        </div>
        <div class="fran-card">
          <div class="thumb"><img src="{{ asset('assets/img/franchise-prestige.jpg') }}" alt="TTK Prestige"></div>
          <div class="body">
            <span class="cat">Kitchen</span>
            <h3>TTK Prestige</h3>
            <div class="specs">
              <div class="row"><span class="k">Investment Range</span><span class="v">&#8377;20 Lakh - &#8377;30 Lakh</span></div>
              <div class="row"><span class="k">Space Required</span><span class="v">400 - 1000 Sq.ft</span></div>
            </div>
            <p class="locs">Delhi, Haryana, Himachal Pradesh, +15 More</p>
            <a href="https://www.franchiseindia.com" class="btn-card">Know More</a>
          </div>
        </div>
      </div>
    </div>
  </section>--}}

  <!-- ====== High Growth Potential Startups ====== -->
  <section class="h-section" id="startups">
    <div class="container">
      <div class="sec-head">
        <div>
          <span class="eyebrow2">Bx Listing</span>
          <h2>High Growth Potential Startups</h2>
          <p class="sub">BusinessX offers <strong>{{ number_format($activeStartupCount) }} active startups</strong></p>
        </div>
        <div class="sec-tools">
         <a href="{{ route('startup-listing') }}" class="view-all">View All
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
          <button class="car-arrow" data-car-prev="stRow" aria-label="Previous">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          </button>
          <button class="car-arrow" data-car-next="stRow" aria-label="Next">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>
        </div>
      </div>
      <div class="cards-row" id="stRow">
        @forelse ($highGrowthStartups as $startup)
          <article class="bizx-card" data-high-growth-startup>
            <div class="thumb"><img src="{{ $startup->image_url }}" alt="{{ $startup->display_title }}"></div>
            <div class="body">
              <span class="cat">{{ $startup->display_industry }}</span>
              <h3>{{ $startup->display_title }}</h3>
              @if ((float) $startup->inv_asking_price > 0)
                <div class="invest">Seeking Investment: <strong>&#163; {{ number_format((float) $startup->inv_asking_price) }}</strong></div>
              @endif
              <div class="meta">
                <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>Phone</span>
                <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Email</span>
                @if ($startup->ofc_city)
                  <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>{{ $startup->ofc_city }}</span>
                @endif
              </div>
              @auth
                <a href="{{ route('profile-details', ['type' => 'startup', 'id' => $startup->startup_id]) }}" class="btn-card">Enquire Now</a>
              @else
                <a href="{{ route('login') }}" class="btn-card">Enquire Now</a>
              @endauth
            </div>
          </article>
        @empty
          <p>No startups are available at the moment.</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- ====== World Class Mentors ====== -->
  <section class="h-section soft" id="mentors">
    <div class="container">
      <div class="sec-head">
        <div>
          <span class="eyebrow2">Bx Listing</span>
          <h2>World Class Mentors</h2>
          <p class="sub">BusinessX offers <strong>{{ number_format($activeMentorCount) }} mentors</strong> across the network</p>
        </div>
        <div class="sec-tools">
         <a href="{{ route('mentor-listing') }}" class="view-all">View All
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
          <button class="car-arrow" data-car-prev="menRow" aria-label="Previous">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          </button>
          <button class="car-arrow" data-car-next="menRow" aria-label="Next">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>
        </div>
      </div>
      <div class="cards-row" id="menRow">
        @forelse ($worldClassMentors as $mentor)
          <article class="person-card" data-world-class-mentor>
            <span class="plan-ribbon">Mentor</span>
            <div class="avatar-wrap"><img src="{{ $mentor->image_url }}" alt="{{ $mentor->mentor_name ?: 'Mentor profile' }}"></div>
            <h3>{{ $mentor->mentor_name ?: 'Mentor' }}</h3>
            <div class="company">{{ $mentor->display_company }}</div>
            <div class="meta">
              <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>Phone</span>
              <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Email</span>
              <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>{{ $mentor->display_city ?: 'Location not specified' }}</span>
            </div>
            <span class="sum-label">Summary</span>
            <p class="summary">{{ $mentor->display_summary ?: 'Experienced mentor supporting business growth and development.' }}</p>
            @auth
              <a href="{{ route('profile-details', ['type' => 'mentor', 'id' => $mentor->mentor_id]) }}" class="btn-card">Send Proposal</a>
            @else
              <a href="{{ route('login') }}" class="btn-card">Send Proposal</a>
            @endauth
          </article>
        @empty
          <p>No mentors are available at the moment.</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- ====== All Popular Business Opportunities ====== -->
  <section class="h-section" id="opportunities">
    <div class="container">
      <div class="sec-head" style="justify-content:center;text-align:center;">
        <div style="max-width:680px;">
          <span class="eyebrow2">Explore</span>
          <h2>All Popular Business Opportunities</h2>
        </div>
      </div>
      <div class="opp-card">
        <h3>View Opportunities By Industry</h3>
        <div class="chip-cloud">
          @forelse ($industryCategories as $category)
            <a href="{{ route('business-listing', ['industry_parent' => $category->id]) }}" class="chip">
              {{ $category->name }} <span class="chip-count">({{ number_format($category->count) }})</span>
            </a>
          @empty
            <p>Industry categories are not available yet.</p>
          @endforelse
        </div>
        <h3>View Opportunities By Location</h3>
        <div class="chip-cloud">
          @forelse ($ukCities as $city)
            <a href="{{ route('business-listing', ['city' => $city->name]) }}" class="chip">
              {{ $city->name }} <span class="chip-count">({{ number_format($city->count) }})</span>
            </a>
          @empty
            <p>UK cities are not available yet.</p>
          @endforelse
        </div>
        <h3>View Opportunities By Investment</h3>
        <div class="chip-cloud">
            @forelse ($investmentRanges as $range)
              <a href="{{ route('business-listing', [
                'investment_min' => $range['min'],
                'investment_max' => $range['max'],
                'investment_max_exclusive' => $range['max_exclusive'] ? 1 : 0,
              ]) }}" class="chip">
                {{ $range['label'] }} <span class="chip-count">({{ number_format($range['count'] ?? 0) }})</span>
              </a>
            @empty
              <p>Investment ranges are not available yet.</p>
            @endforelse
        </div>
      </div>
    </div>
  </section>

  <!-- ====== Bx Insights ====== -->
  <section class="h-section soft" id="insights">
    <div class="container">
      <div class="sec-head">
        <div>
          <span class="eyebrow2">Bx Insights</span>
          <h2>Insights, Articles &amp; News</h2>
          <p class="sub">Expert knowledge, market trends and business exchange guidance</p>
        </div>
        <div class="sec-tools">
          <a href="{{ route('article') }}" class="view-all">View All
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>
      </div>
      <div class="art-grid">
        @forelse ($latestArticles as $article)
          <article class="art-card">
            <a class="thumb" href="{{ route('articles.show', $article->article_id) }}">
              <img src="{{ $article->image_url }}" alt="{{ $article->article_title }}">
              <span class="date">{{ $article->published_at_label }}</span>
            </a>
            <div class="body">
              <h3><a href="{{ route('articles.show', $article->article_id) }}">{{ $article->article_title }}</a></h3>
              <p>{{ \Illuminate\Support\Str::limit(strip_tags($article->short_desc), 150) }}</p>
              <div class="author">
                <span class="a-avatar">BX</span>
                <span>{{ $article->author_name }}</span>
              </div>
            </div>
          </article>
        @empty
          <p>No published articles are available yet.</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- ====== Membership Plans ====== -->
  <section class="h-section" id="plans">
    <div class="container">
      <div class="sec-head" style="justify-content:center;text-align:center;">
        <div style="max-width:640px;">
          <span class="eyebrow2">Membership</span>
          <h2>Membership Plans</h2>
          <p class="sub">Choose the right plan for you</p>
        </div>
      </div>
      <div class="plans-grid">
        <div class="planx-card">
          <div class="p-name">Free</div>
          <div class="p-sub">Get started with basic features</div>
          <div class="p-price"><span class="amt">£0</span></div>
          <div class="p-note">Forever free</div>
          <ul>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Create Business Profile</li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Basic Directory Listing</li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Connect with Members</li>
          </ul>
          <a href="{{ route('pricing') }}" class="btn-card">Select Plan</a>
        </div>
        <div class="planx-card featured">
          <div class="planx-badge">★ Most Popular</div>
          <div class="p-name">Premium</div>
          <div class="p-sub">Grow your business with premium tools</div>
          <div class="p-price"><span class="amt">£299</span> <span class="per">/ Year</span></div>
          <div class="p-note">Billed annually</div>
          <ul>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Everything in Free Plan</li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Verified Member Badge</li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Receive Leads &amp; Inquiries</li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Featured Listing in Directory</li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Business Matchmaking</li>
          </ul>
          <a href="{{ route('pricing') }}" class="btn-card">Get Started</a>
        </div>
        <div class="planx-card">
          <div class="p-name">Elite</div>
          <div class="p-sub">Maximum visibility. Global impact.</div>
          <div class="p-price"><span class="amt">£999</span> <span class="per">/ Year</span></div>
          <div class="p-note">Billed annually</div>
          <ul>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Everything in Premium Plan</li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Top Placement in Directory</li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Homepage Featured Listing</li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4"><polyline points="20 6 9 17 4 12"/></svg></span>Priority Leads &amp; Inquiries</li>
          </ul>
          <a href="{{ route('pricing') }}" class="btn-card">Select Plan</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== Did You Find Anything Interested ====== -->
  <section class="cta-band" id="cta">
    <div class="container">
      <div class="cta-inner">
        <div>
          <h2>Did You Find Anything Interested?</h2>
          <p class="cta-sub">Why wait, create your profile now and start connecting with verified buyers, investors and mentors.</p>
        </div>
        <form class="cta-form" id="ctaProfileForm">
          <select id="ctaProfile" aria-label="Select a profile" required>
            <option value="">Select a profile...</option>
            <option value="business">Business | Looking To Sell</option>
            <option value="startup">Startup | Looking For Funds</option>
            <option value="investor">Investor | Looking To Invest/Buy</option>
            <option value="mentor">Mentor | Looking To Guide/Coach</option>
          </select>
          <button type="submit">Create Profile</button>
        </form>
      </div>
    </div>
  </section>

  <!-- ====== What Our Clients Say ====== -->
  <section class="h-section soft" id="testimonials">
    <div class="container">
      <div class="sec-head" style="justify-content:center;text-align:center;">
        <div style="max-width:640px;">
          <span class="eyebrow2">Testimonials</span>
          <h2>What Our Clients Say</h2>
        </div>
      </div>
      <div class="test-grid">
        @forelse ($testimonials as $testimonial)
          <article class="test-card">
            <span class="qmark" aria-hidden="true">&ldquo;</span>
            <span class="test-stars" role="img" aria-label="{{ $testimonial->rating }} out of 5 stars">
              @for ($star = 1; $star <= 5; $star++)
                <svg viewBox="0 0 24 24" fill="{{ $star <= $testimonial->rating ? 'currentColor' : 'none' }}" stroke="currentColor" aria-hidden="true"><polygon points="12,2 15,8.5 22,9.3 17,14 18.2,21 12,17.7 5.8,21 7,14 2,9.3 9,8.5"/></svg>
              @endfor
            </span>
            <p>{{ $testimonial->text }}</p>
            <div class="who">
              <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->name }}">
              <div>
                <div class="n">{{ $testimonial->name }}</div>
                @if ($testimonial->designation)
                  <div class="r">{{ $testimonial->designation }}</div>
                @endif
              </div>
            </div>
          </article>
        @empty
          <p>No testimonials are available yet.</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- ====== Newsletter ====== -->
  <section class="news-band" id="newsletter-subscription">
    <div class="container">
      <div class="news-grid">
        <div>
          <h3>Get <span class="hl">Industry First</span> Insights</h3>
          <p class="n-sub">Sign up for our exclusive newsletter — market trends, new listings and funding opportunities, straight to your inbox.</p>
        </div>
        <form class="news-form" action="{{ route('newsletter.subscribe') }}" method="POST">
          @csrf
          <div class="news-field">
            <label class="sr-only" for="home-newsletter-name">Name</label>
            <input id="home-newsletter-name" name="name" type="text" aria-label="Name" placeholder="Name" value="{{ old('name') }}" maxlength="255" autocomplete="name" required>
            @error('name') <span class="news-field-error" role="alert">{{ $message }}</span> @enderror
          </div>
          <div class="news-field">
            <label class="sr-only" for="home-newsletter-email">Email</label>
            <input id="home-newsletter-email" name="email" type="email" aria-label="Email address" placeholder="Email" value="{{ old('email') }}" maxlength="255" autocomplete="email" required>
            @error('email') <span class="news-field-error" role="alert">{{ $message }}</span> @enderror
          </div>
          <div class="news-field">
            <label class="sr-only" for="home-newsletter-phone">Phone</label>
            <div class="phone-input-group">
              @include('components.phone-country-code')
              <input id="home-newsletter-phone" name="phone" type="tel" aria-label="Phone number" placeholder="Phone" value="{{ old('phone') }}" maxlength="32" autocomplete="tel" required>
            </div>
            @error('phone') <span class="news-field-error" role="alert">{{ $message }}</span> @enderror
          </div>
          <div class="news-field">
            <label class="sr-only" for="home-newsletter-city">City</label>
            <input id="home-newsletter-city" name="city" type="text" aria-label="City" placeholder="City" value="{{ old('city') }}" maxlength="100" autocomplete="address-level2" required>
            @error('city') <span class="news-field-error" role="alert">{{ $message }}</span> @enderror
          </div>
          <button type="submit" class="btn-sub">Subscribe Now</button>
        </form>
        @if (session('newsletter_status'))
          <p class="news-feedback news-feedback-success" role="status">{{ session('newsletter_status') }}</p>
        @endif
        @if (session('newsletter_error'))
          <p class="news-feedback news-feedback-error" role="alert">{{ session('newsletter_error') }}</p>
        @endif
      </div>
    </div>
  </section>

  

  <script>
    // Carousel arrows
    document.querySelectorAll('[data-car-next]').forEach(btn => {
      btn.addEventListener('click', () => {
        const row = document.getElementById(btn.dataset.carNext);
        const card = row.querySelector(':scope > *');
        if (row && card) row.scrollBy({ left: card.getBoundingClientRect().width + 24, behavior: 'smooth' });
      });
    });
    document.querySelectorAll('[data-car-prev]').forEach(btn => {
      btn.addEventListener('click', () => {
        const row = document.getElementById(btn.dataset.carPrev);
        const card = row.querySelector(':scope > *');
        if (row && card) row.scrollBy({ left: -(card.getBoundingClientRect().width + 24), behavior: 'smooth' });
      });
    });

    // Hero "Create Profile" → registration with type
    document.getElementById('heroCreateForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const profile = document.getElementById('heroProfile');
      const v = profile.value;
      if (!v) {
        profile.reportValidity();
        return;
      }
      window.location.href = '{{ route('registration') }}?type=' + encodeURIComponent(v);
    });

    // Mid-page CTA "Did You Find Anything Interested" → registration with type
    document.getElementById('ctaProfileForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const profile = document.getElementById('ctaProfile');
      const v = profile.value;
      if (!v) {
        profile.reportValidity();
        return;
      }
      window.location.href = '{{ route('registration') }}?type=' + encodeURIComponent(v);
    });

    // Sync the two profile selects
    document.getElementById('heroProfile').addEventListener('change', function() {
      document.getElementById('cardProfile').value = this.value;
    });
    document.getElementById('cardProfile').addEventListener('change', function() {
      document.getElementById('heroProfile').value = this.value;
    });

  </script>

  @include('layouts.partials.chatbot')
  @push('scripts')
    <script src="{{ asset('js/chatbot.js?v=20261006') }}"></script>
  @endpush

@endsection
