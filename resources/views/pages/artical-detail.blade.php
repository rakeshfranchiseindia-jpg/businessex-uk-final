@extends('layouts.app')

@section('title')
{{ $article->seo_title ?: $article->article_title }} | BusinessX
@endsection
@section('description')
{{ $article->seo_desc ?: $article->short_desc }}
@endsection

@section('head')
<style>
    body { background: var(--gray-50); }
    .detail-header { background: var(--white); color: var(--navy-900); border-bottom: 1px solid var(--gray-200); }
    .detail-header .container { min-height: 72px; display: flex; align-items: center; gap: 28px; }
    .detail-brand { display: inline-flex; align-items: center; }
    .detail-brand img { display: block; width: 211px; height: auto; }
    .detail-nav { display: flex; align-items: center; gap: 24px; margin-left: auto; font-size: 13px; font-weight: 600; }
    .detail-nav a { color: var(--navy-800); }
    .detail-nav a:hover, .detail-nav a[aria-current="page"] { color: var(--gold-400); }
    .detail-signin { padding: 8px 13px; border: 1px solid var(--gray-300); border-radius: 5px; font-size: 12px; font-weight: 700; }
    .featured-detail { padding: 34px 0 72px; background: var(--gray-50); }
    .breadcrumbs { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 24px; color: var(--gray-500); font-size: 12px; }
    .breadcrumbs a:hover { color: var(--gold-600); }
    .breadcrumbs .sep { color: var(--gray-300); }
    .breadcrumbs .current { color: var(--navy-700); font-weight: 600; }
    .featured-grid { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 32px; align-items: start; }
    .article-detail { min-width: 0; padding: 32px; background: var(--white); border: 1px solid var(--gray-200); border-radius: 8px; }
    .cat-badge { display: inline-block; margin-bottom: 14px; padding: 4px 9px; color: var(--blue-500); background: var(--blue-100); border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .article-detail h1 { margin-bottom: 14px; color: var(--navy-900); font-size: clamp(28px, 3vw, 38px); line-height: 1.2; }
    .article-detail .lede { margin-bottom: 22px; color: var(--gray-600); font-size: 16px; line-height: 1.65; }
    .article-detail .meta { display: flex; align-items: center; flex-wrap: wrap; gap: 12px; padding-bottom: 18px; margin-bottom: 22px; border-bottom: 1px solid var(--gray-200); color: var(--gray-500); font-size: 12px; }
    .article-detail .meta .author { display: flex; align-items: center; gap: 9px; }
    .article-detail .meta .avatar { width: 36px; height: 36px; flex: 0 0 auto; border-radius: 50%; background: linear-gradient(135deg, #52A9EF, #1454B8); }
    .article-detail .meta .name { color: var(--gray-800); font-weight: 700; }
    .article-detail .meta-divider { width: 1px; height: 22px; background: var(--gray-200); }
    .article-detail .meta-item { display: inline-flex; align-items: center; gap: 5px; }
    .article-detail .meta-item svg { width: 14px; height: 14px; color: var(--gray-400); }
    .article-detail .hero-img { width: 100%; aspect-ratio: 16 / 9; overflow: hidden; margin-bottom: 28px; border-radius: 6px; background: var(--gray-100); }
    .article-detail .content p { margin-bottom: 18px; color: var(--gray-700); font-size: 15px; line-height: 1.75; }
    .article-detail .content h2 { margin: 30px 0 12px; color: var(--navy-900); font-size: 21px; line-height: 1.35; }
    .article-detail .content blockquote { margin: 22px 0; padding: 16px 20px; border-left: 3px solid var(--gold-500); border-radius: 0 5px 5px 0; background: var(--gray-50); color: var(--gray-700); font-size: 15px; line-height: 1.7; font-style: italic; }
    .article-detail .content ul { margin: 0 0 20px; padding: 0; list-style: none; }
    .article-detail .content ul li { position: relative; margin-bottom: 9px; padding-left: 22px; color: var(--gray-700); font-size: 14px; line-height: 1.65; }
    .article-detail .content ul li::before { position: absolute; top: 8px; left: 0; width: 8px; height: 8px; border-radius: 50%; background: var(--gold-500); content: ''; }
    .tags-row { display: flex; flex-wrap: wrap; gap: 7px; padding-top: 20px; margin-top: 28px; border-top: 1px solid var(--gray-200); }
    .tag-pill { padding: 4px 10px; border-radius: 999px; background: var(--gray-100); color: var(--gray-700); font-size: 11px; }
    .share-row { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; padding: 14px 0; margin-top: 20px; border-top: 1px solid var(--gray-200); border-bottom: 1px solid var(--gray-200); }
    .share-label { color: var(--gray-700); font-size: 12px; font-weight: 700; }
    .share-icons { display: flex; gap: 7px; }
    .share-icons a, .share-icons button { display: grid; place-items: center; width: 32px; height: 32px; border-radius: 50%; background: var(--gray-100); color: var(--gray-600); }
    .share-icons a:hover, .share-icons button:hover { background: var(--gold-500); color: var(--white); }
    .share-icons svg { width: 14px; height: 14px; }
    .comments-section { padding-top: 26px; margin-top: 26px; border-top: 1px solid var(--gray-200); }
    .comments-section h2 { margin-bottom: 18px; color: var(--navy-900); font-size: 22px; }
    .comment-form { display: grid; gap: 12px; margin-bottom: 28px; }
    .comment-form textarea { width: 100%; min-height: 130px; padding: 12px; border: 1px solid var(--gray-200); border-radius: 6px; color: var(--gray-800); resize: vertical; }
    .comment-form textarea:focus { outline: 2px solid rgba(19,141,227,.28); border-color: var(--gold-500); }
    .comment-submit { justify-self: start; padding: 10px 18px; border-radius: 5px; color: var(--white); background: var(--navy-700); font-size: 13px; font-weight: 700; }
    .comment-submit:hover { background: var(--navy-800); }
    .comment-notice { padding: 12px 14px; margin-bottom: 16px; border-radius: 5px; color: #245b36; background: #e9f6ed; font-size: 13px; }
    .comment-error { color: #a12622; font-size: 12px; }
    .comment-item { padding: 16px 0; border-top: 1px solid var(--gray-100); }
    .comment-item h3 { margin-bottom: 4px; color: var(--navy-800); font-size: 14px; }
    .comment-item time, .comment-login { color: var(--gray-500); font-size: 12px; }
    .comment-item p { margin-top: 8px; color: var(--gray-700); font-size: 14px; line-height: 1.65; white-space: pre-line; }
    .detail-sidebar { position: sticky; top: 92px; }
    .side-card { padding: 20px; margin-bottom: 16px; border: 1px solid var(--gray-200); border-radius: 8px; background: var(--white); }
    .side-card h3 { margin-bottom: 13px; color: var(--navy-900); font-size: 15px; }
    .author-card { text-align: center; }
    .author-img { width: 68px; height: 68px; margin: 0 auto 12px; border-radius: 50%; background: linear-gradient(135deg, #52A9EF, #1454B8); }
    .author-card h4 { color: var(--navy-900); font-size: 15px; }
    .author-card .role { margin: 4px 0 10px; color: var(--gold-600); font-size: 11px; font-weight: 700; }
    .author-card .bio { margin-bottom: 14px; color: var(--gray-600); font-size: 12px; line-height: 1.6; }
    .follow-btn { width: 100%; padding: 9px 12px; border: 1px solid var(--gold-500); border-radius: 5px; color: var(--gold-600); background: var(--white); font-size: 12px; font-weight: 700; }
    .follow-btn:hover, .follow-btn[aria-pressed="true"] { color: var(--navy-900); background: var(--gold-100); }
    .popular-item { display: flex; gap: 10px; padding: 10px 0; counter-increment: popular; border-bottom: 1px solid var(--gray-100); }
    .popular-list { counter-reset: popular; }
    .popular-item::before { color: var(--gold-500); font-size: 20px; font-weight: 800; content: counter(popular); }
    .popular-item:last-child { border-bottom: 0; }
    .popular-list h5, .related-list h5 { color: var(--gray-800); font-size: 12px; line-height: 1.45; }
    .popular-meta, .related-date { margin-top: 4px; color: var(--gray-400); font-size: 10px; }
    .related-item { display: flex; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--gray-100); }
    .related-item:last-child { border-bottom: 0; }
    .related-img { width: 52px; height: 48px; flex: 0 0 auto; border-radius: 4px; background: var(--navy-700); }
    .tag-cloud { display: flex; flex-wrap: wrap; gap: 6px; }
    .tag-cloud a { padding: 4px 8px; border-radius: 999px; background: var(--gray-100); color: var(--gray-700); font-size: 10px; }
    .tag-cloud a:hover { color: var(--navy-900); background: var(--gold-100); }
    .detail-footer { padding: 20px 0; border-top: 3px solid var(--gold-500); color: rgba(255,255,255,.7); background: var(--navy-900); font-size: 12px; }
    .detail-footer .container { display: flex; justify-content: space-between; gap: 12px; }
    .detail-footer a:hover { color: var(--gold-400); }
    @media (max-width: 980px) {
      .featured-grid { grid-template-columns: 1fr; }
      .detail-sidebar { position: static; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
      .detail-sidebar .side-card { margin: 0; }
      .author-card, .detail-sidebar .side-card:last-child { grid-column: 1 / -1; }
    }
    @media (max-width: 700px) {
      .detail-header .container { min-height: 64px; gap: 12px; }
      .detail-brand img { width: 180px; height: auto; }
      .detail-nav { display: none; }
      .detail-signin { margin-left: auto; }
      .featured-detail { padding: 24px 0 44px; }
      .article-detail { padding: 22px 18px; }
      .article-detail h1 { font-size: 28px; }
      .article-detail .meta { gap: 9px; }
      .meta-divider { display: none; }
      .detail-sidebar { grid-template-columns: 1fr; }
      .author-card, .detail-sidebar .side-card:last-child { grid-column: auto; }
      .detail-footer .container { flex-direction: column; align-items: center; text-align: center; }
    }
  </style>
@endsection

@section('content')

  <header class="detail-header">
    <div class="container">
      <a class="detail-brand" href="{{ route('home') }}" aria-label="BusinessX home"><img src="{{ asset('assets/img/businessx-logo.png?v=20261008') }}" alt="BusinessX"></a>
      <nav class="detail-nav" aria-label="Main navigation">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('business-listing') }}">Bx Listing</a>
        <a href="{{ route('registration') }}">Registration</a>
        <a href="{{ route('pricing') }}">Pricing</a>
        <a href="{{ route('article') }}" aria-current="page">Bx Insights</a>
      </nav>
      @auth
        <span class="detail-signin">{{ auth()->user()->name }}</span>
      @else
        <a class="detail-signin" href="{{ route('login') }}">Sign In</a>
      @endauth
    </div>
  </header>

  <main>
  <section class="featured-detail" id="featured">
    <div class="container">

      <div class="breadcrumbs">
        <a href="{{ route('home') }}">Home</a>
        <span class="sep">/</span>
        <a href="{{ route('article') }}">Articles & News</a>
        <span class="sep">/</span>
        @if ($article->tag_list)
          <a href="{{ route('article', ['category' => \Illuminate\Support\Str::slug($article->tag_list[0])]) }}">{{ $article->tag_list[0] }}</a>
        @endif
        <span class="sep">/</span>
        <span class="current">{{ $article->article_title }}</span>
      </div>

      <div class="featured-grid">

        <!-- Main article content -->
        <article class="article-detail">
          @if ($article->tag_list)
            <span class="cat-badge">{{ $article->tag_list[0] }}</span>
          @endif
          <h1>{{ $article->article_title }}</h1>
          <p class="lede">{{ $article->short_desc }}</p>

          <div class="meta">
            <div class="author">
              <div class="avatar"></div>
              <div>
                <div class="name">{{ $article->author_name }}</div>
                <div>{{ $article->author_role }}</div>
              </div>
            </div>
            <span class="meta-divider"></span>
            <div class="meta-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              {{ $article->published_at_label }}
            </div>
            <span class="meta-divider"></span>
            <div class="meta-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              {{ $article->read_minutes }} min read
            </div>
            <span class="meta-divider"></span>
            <div class="meta-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              {{ number_format((int) $article->article_comments) }} Comments
            </div>
            <span class="meta-divider"></span>
            <div class="meta-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
              {{ number_format((int) $article->article_views) }} Views
            </div>
          </div>

          <div class="hero-img"><img src="{{ $article->image_url }}" alt="{{ $article->article_title }}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;"></div>

          <div class="content">{!! $article->content_html !!}</div>

          <div class="tags-row">
            @foreach ($article->tag_list as $tag)
              <a class="tag-pill" href="{{ route('article', ['category' => \Illuminate\Support\Str::slug($tag)]) }}">#{{ $tag }}</a>
            @endforeach
          </div>

          <div class="share-row">
            <span class="share-label">Share this article</span>
            <div class="share-icons">
              <a href="#" aria-label="Share on Twitter"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.8c-.8.4-1.6.6-2.5.7.9-.5 1.6-1.4 1.9-2.4-.8.5-1.8.9-2.7 1.1-.8-.9-2-1.4-3.2-1.4-2.4 0-4.4 2-4.4 4.4 0 .3 0 .7.1 1-3.7-.2-7-1.9-9.1-4.6-.4.7-.6 1.5-.6 2.3 0 1.5.8 2.9 2 3.7-.7 0-1.4-.2-2-.5v.1c0 2.1 1.5 3.9 3.5 4.3-.4.1-.7.2-1.1.2-.3 0-.5 0-.8-.1.5 1.7 2.2 3 4.1 3-1.5 1.2-3.4 1.9-5.4 1.9h-1c2 1.3 4.4 2 6.9 2 8.3 0 12.8-6.8 12.8-12.8v-.6c.9-.6 1.6-1.4 2.2-2.3z"/></svg></a>
              <a href="#" aria-label="Share on LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5c0 1.381-1.119 2.5-2.5 2.5s-2.5-1.119-2.5-2.5 1.119-2.5 2.5-2.5 2.5 1.119 2.5 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.6 4.92-4.985 4.92 0v8.399h5.029v-9.965c0-7.879-8.435-7.589-9.95-3.96v-2.075z"/></svg></a>
              <a href="#" aria-label="Share on Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.5c-2.857 0-3.992 1-4.5 3.5v4.5z"/></svg></a>
              <a href="#" aria-label="Share by email"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></a>
              <a href="#" aria-label="Copy link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></a>
            </div>
          </div>

          <section class="comments-section" id="comments" aria-labelledby="comments-heading">
            <h2 id="comments-heading">Comments ({{ number_format($article->article_comments) }})</h2>
            @if (session('comment_submitted'))
              <div class="comment-notice" role="status">{{ session('comment_submitted') }}</div>
            @endif

            @auth
              <form class="comment-form" method="post" action="{{ route('articles.comments.store', $article->article_id) }}">
                @csrf
                <label for="comment-detail">Join the discussion</label>
                <textarea id="comment-detail" name="comment_detail" maxlength="3000" required>{{ old('comment_detail') }}</textarea>
                @error('comment_detail')
                  <span class="comment-error">{{ $message }}</span>
                @enderror
                <button class="comment-submit" type="submit">Post comment</button>
              </form>
            @else
              <p class="comment-login"><a href="{{ route('login') }}">Sign in</a> to leave a comment.</p>
            @endauth

            <div class="comment-list">
              @forelse ($comments as $comment)
                <article class="comment-item">
                  <h3>{{ $comment->comment_name }}</h3>
                  @if ($comment->created_at)
                    <time datetime="{{ \Illuminate\Support\Carbon::parse($comment->created_at)->toDateString() }}">
                      {{ \Illuminate\Support\Carbon::parse($comment->created_at)->format('d M Y') }}
                    </time>
                  @endif
                  <p>{{ $comment->comment_detail }}</p>
                </article>
              @empty
                <p class="comment-login">No comments yet. Be the first to comment.</p>
              @endforelse
            </div>
          </section>
        </article>

        <!-- Sidebar -->
        <aside class="detail-sidebar">

          <div class="side-card author-card">
            <div class="author-img"></div>
            <h4>{{ $article->author_name }}</h4>
            <div class="role">{{ $article->author_role }}</div>
            <p class="bio">More articles and insights from {{ $article->author_name }}.</p>
            <button class="follow-btn" type="button" aria-pressed="false">+ Follow Author</button>
          </div>

          <div class="side-card">
            <h3>Popular Articles</h3>
            <div class="popular-list">
              @foreach ($popularArticles as $popularArticle)
              <a class="popular-item" href="{{ route('articles.show', $popularArticle->article_id) }}">
                <div>
                  <h5>{{ $popularArticle->article_title }}</h5>
                  <div class="popular-meta">{{ $popularArticle->published_at_label }} · {{ $popularArticle->read_minutes }} min read</div>
                </div>
              </a>
              @endforeach
              @if ($popularArticles->isEmpty())
                <p>No other published articles yet.</p>
              @endif
            </div>
          </div>

          <div class="side-card">
            <h3>Related Articles</h3>
            <div class="related-list">
              @foreach ($relatedArticles as $relatedArticle)
              <a class="related-item" href="{{ route('articles.show', $relatedArticle->article_id) }}">
                <img class="related-img" src="{{ $relatedArticle->image_url }}" alt="" style="object-fit:cover;">
                <div>
                  <h5>{{ $relatedArticle->article_title }}</h5>
                  <div class="related-date">{{ $relatedArticle->published_at_label }}</div>
                </div>
              </a>
              @endforeach
            </div>
          </div>

          <div class="side-card">
            <h3>Tags</h3>
            <div class="tag-cloud">
              @foreach ($tagCategories as $tagCategory)
                <a href="{{ route('article', ['category' => $tagCategory['slug']]) }}">{{ $tagCategory['name'] }}</a>
              @endforeach
            </div>
          </div>

        </aside>
      </div>
    </div>
  </section>
  </main>

  

  <script>
    const followButton = document.querySelector('.follow-btn');
    followButton.addEventListener('click', () => {
      const following = followButton.getAttribute('aria-pressed') !== 'true';
      followButton.setAttribute('aria-pressed', String(following));
      followButton.textContent = following ? 'Following' : '+ Follow Author';
    });

    document.querySelectorAll('.share-icons a[href="#"]').forEach(link => {
      link.addEventListener('click', async event => {
        event.preventDefault();
        if (link.getAttribute('aria-label') === 'Copy link') {
          try {
            await navigator.clipboard.writeText(window.location.href);
            link.setAttribute('aria-label', 'Link copied');
          } catch {
            link.setAttribute('aria-label', 'Copy link unavailable');
          }
        }
      });
    });
  </script>

@endsection
