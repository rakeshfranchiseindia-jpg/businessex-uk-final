@if ($paginator->hasPages() || $paginator->total() > 0)
    <div class="card-footer bg-white d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="small text-muted">Showing {{ $paginator->firstItem() ?? 0 }}&ndash;{{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }}</div>
        {{ $paginator->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
@endif
