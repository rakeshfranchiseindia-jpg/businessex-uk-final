@forelse ($listings as $listing)
  <article class="biz-card" data-listing-card="{{ $listingType }}">
    <div class="biz-card-logo">
      <img src="{{ $listing->image_url }}" alt="{{ $listing->name }}" loading="lazy">
      <span class="logo-mark">{{ $listing->initials }}</span>
    </div>
    <div class="biz-card-body">
      <div class="row1">
        <span class="verified-badge">{{ ucfirst($listingType) }} Listing</span>
      </div>
      <h3>{{ $listing->name }}</h3>
      <div class="location">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        {{ $listing->city }}
      </div>
      <p class="desc">{{ \Illuminate\Support\Str::limit($listing->summary, 220) }}</p>
      <div class="tags">
        <span class="tag">{{ $listing->industry }}</span>
        @if ($listing->amount)
          <span class="tag">{{ $listing->amount }}</span>
        @endif
      </div>
    </div>
    <div class="biz-card-meta">
      @foreach ($listing->details as $label => $value)
        <div class="meta-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 2"/></svg>
          {{ $label }} <strong>{{ $value }}</strong>
        </div>
      @endforeach
      <div class="view-profile">
        <a href="{{ route('profile-details', ['type' => $listingType, 'id' => $listing->id]) }}" class="btn-view-profile">View Profile</a>
      </div>
    </div>
  </article>
@empty
  <p class="listing-empty">No {{ $listingLabel }} match your search. Try changing or clearing the filters.</p>
@endforelse
