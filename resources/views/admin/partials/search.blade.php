{{-- Usage: @component("admin.partials.search", ["placeholder" => "..."]) extra filter inputs @endcomponent --}}
<form method="GET" class="filter-bar d-flex flex-wrap gap-2 align-items-center">
    <div class="input-group" style="max-width: 320px">
        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
        <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="{{ $placeholder ?? 'Search...' }}">
    </div>
    {{ $slot ?? '' }}
    <button class="btn btn-navy">Filter</button>
    @if (request()->except('page'))
        <a href="{{ url()->current() }}" class="btn btn-link text-muted">Reset</a>
    @endif
</form>
