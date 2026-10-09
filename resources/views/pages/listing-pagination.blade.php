@if ($listings->hasPages())
  <div class="pagination">
    @if ($listings->onFirstPage())
      <span aria-disabled="true" aria-label="Previous">‹</span>
    @else
      <a href="{{ $listings->previousPageUrl() }}" aria-label="Previous">‹</a>
    @endif

    @foreach (array_filter(\Illuminate\Pagination\UrlWindow::make($listings->onEachSide(1))) as $element)
      @if (is_string($element))
        <a href="#" class="ellipsis" aria-hidden="true">{{ $element }}</a>
      @else
        @foreach ($element as $page => $url)
          @if ($page === $listings->currentPage())
            <a href="{{ $url }}" class="active" aria-current="page">{{ $page }}</a>
          @else
            <a href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    @if ($listings->hasMorePages())
      <a href="{{ $listings->nextPageUrl() }}" aria-label="Next">›</a>
    @else
      <span aria-disabled="true" aria-label="Next">›</span>
    @endif
  </div>
@endif
