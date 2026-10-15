@php
    $admin = auth('admin')->user();
    $pendingCounts = $admin->canAccess('profiles')
        ? \Illuminate\Support\Facades\Cache::remember('admin.sidebar.pending', 60, function () {
            $counts = [];
            foreach (\App\Support\Admin\ProfileTypes::all() as $slug => $type) {
                $counts[$slug] = \App\Support\Admin\ProfileTypes::query($slug)
                    ->whereIn($type['status'], [\App\Support\Admin\ProfileTypes::STATUS_PENDING, \App\Support\Admin\ProfileTypes::STATUS_AWAITING])
                    ->count();
            }
            return $counts;
        })
        : [];
    $currentType = request()->route('type');
@endphp
<aside class="admin-sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="adminSidebar" aria-label="Admin navigation">
    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
            <i class="bi bi-briefcase-fill text-warning"></i> BusinessX <span class="badge">ADMIN</span>
        </a>
        <button type="button" class="btn-close btn-close-white me-3 d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Close"></button>
    </div>

    <nav class="admin-nav nav flex-column">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        @if ($admin->canAccess('profiles'))
            <div class="nav-section">Profiles</div>
            @foreach (\App\Support\Admin\ProfileTypes::all() as $slug => $type)
                {{-- Lender, Incubator and Broker temporarily hidden from the sidebar nav only --}}
                @continue(in_array($slug, ['lender', 'incubator', 'broker'], true))
                <a class="nav-link {{ $currentType === $slug ? 'active' : '' }}" href="{{ route('admin.profiles.index', $slug) }}">
                    <i class="bi {{ ['business' => 'bi-shop', 'startup' => 'bi-rocket-takeoff', 'investor' => 'bi-cash-coin', 'mentor' => 'bi-person-workspace', 'lender' => 'bi-bank', 'incubator' => 'bi-buildings', 'broker' => 'bi-person-badge'][$slug] }}"></i>
                    {{ $type['label'] }}s
                    @if (($pendingCounts[$slug] ?? 0) > 0)
                        <span class="count" title="Pending approval">{{ $pendingCounts[$slug] }}</span>
                    @endif
                </a>
            @endforeach
        @endif

        @if ($admin->canAccess('users') || $admin->canAccess('interactions'))
            <div class="nav-section">Members</div>
            @if ($admin->canAccess('users'))
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i> Users</a>
            @endif
            @if ($admin->canAccess('interactions'))
                <a class="nav-link {{ request()->routeIs('admin.conversations.*') ? 'active' : '' }}" href="{{ route('admin.conversations.index') }}"><i class="bi bi-chat-dots"></i> Conversations</a>
                <a class="nav-link {{ request()->routeIs('admin.proposals.*') ? 'active' : '' }}" href="{{ route('admin.proposals.index') }}"><i class="bi bi-file-earmark-text"></i> Proposals</a>
                <a class="nav-link {{ request()->routeIs('admin.instant-responses.*') ? 'active' : '' }}" href="{{ route('admin.instant-responses.index') }}"><i class="bi bi-lightning"></i> Instant Responses</a>
            @endif
        @endif

        @if ($admin->canAccess('content') || $admin->canAccess('reports'))
            <div class="nav-section">Content</div>
            @if ($admin->canAccess('content'))
                {{-- News module unused on the front-end; hidden from sidebar only --}}
                {{-- <a class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}" href="{{ route('admin.news.index') }}"><i class="bi bi-newspaper"></i> News</a> --}}
                <a class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}" href="{{ route('admin.articles.index') }}"><i class="bi bi-journal-richtext"></i> Articles</a>
                <a class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}" href="{{ route('admin.comments.index') }}"><i class="bi bi-chat-left-quote"></i> Article Comments</a>
            @endif
            @if ($admin->canAccess('reports'))
                {{-- Industry Reports has no public-facing page; hidden from sidebar only --}}
                {{-- <a class="nav-link {{ request()->routeIs('admin.industry-reports.*') ? 'active' : '' }}" href="{{ route('admin.industry-reports.index') }}"><i class="bi bi-graph-up"></i> Industry Reports</a> --}}
            @endif
            @if ($admin->canAccess('content'))
                {{-- BEX Shorts module unused on the front-end; hidden from sidebar only --}}
                {{-- <a class="nav-link {{ request()->routeIs('admin.bex-shorts.*') ? 'active' : '' }}" href="{{ route('admin.bex-shorts.index') }}"><i class="bi bi-lightning-charge"></i> BEX Shorts</a> --}}
                {{-- Tags module unused on the front-end; hidden from sidebar only --}}
                {{-- <a class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}"><i class="bi bi-tags"></i> Tags</a> --}}
                {{-- SEO module not read anywhere on the front-end; hidden from sidebar only --}}
                {{-- <a class="nav-link {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}" href="{{ route('admin.seo.index') }}"><i class="bi bi-search"></i> SEO</a> --}}
            @endif
        @endif

        @if ($admin->canAccess('marketing') || $admin->canAccess('payments'))
            <div class="nav-section">Marketing &amp; Sales</div>
            @if ($admin->canAccess('marketing'))
                {{-- Banners never render on the front-end; hidden from sidebar only --}}
                {{-- <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}"><i class="bi bi-badge-ad"></i> Banners</a> --}}
                <a class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}"><i class="bi bi-star"></i> Testimonials</a>
            @endif
            @if ($admin->canAccess('payments'))
                {{-- Coupons can't be applied in checkout yet; hidden from sidebar only --}}
                {{-- <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}"><i class="bi bi-ticket-perforated"></i> Coupons</a> --}}
                <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}"><i class="bi bi-credit-card"></i> Payments</a>
            @endif
        @endif

        @if ($admin->canAccess('enquiries'))
            <div class="nav-section">Enquiries</div>
            <a class="nav-link {{ request()->routeIs('admin.enquiries.contact') ? 'active' : '' }}" href="{{ route('admin.enquiries.contact') }}"><i class="bi bi-envelope"></i> Contact Us</a>
            <a class="nav-link {{ request()->routeIs('admin.enquiries.newsletter') ? 'active' : '' }}" href="{{ route('admin.enquiries.newsletter') }}"><i class="bi bi-envelope-paper"></i> Newsletter</a>
            <a class="nav-link {{ request()->routeIs('admin.enquiries.chatbot') ? 'active' : '' }}" href="{{ route('admin.enquiries.chatbot') }}"><i class="bi bi-robot"></i> Chatbot Leads</a>
        @endif
    </nav>
</aside>
