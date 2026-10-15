@if ($active)
    <span class="badge rounded-pill text-bg-success">{{ $on ?? 'Active' }}</span>
@else
    <span class="badge rounded-pill text-bg-secondary">{{ $off ?? 'Inactive' }}</span>
@endif
