@php
    $classes = [0 => 'secondary', 1 => 'success', 2 => 'warning', 3 => 'warning', 4 => 'danger'];
    $status = is_numeric($status) ? (int) $status : null;
@endphp
<span class="badge rounded-pill text-bg-{{ $classes[$status] ?? 'light' }}">{{ config('admin.profile_status')[$status] ?? 'Unknown' }}</span>
