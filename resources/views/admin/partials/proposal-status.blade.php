@php($tone = ['accepted' => 'success', 'rejected' => 'danger', 'declined' => 'danger', 'pending' => 'warning', 'sent' => 'info'][strtolower((string) $status)] ?? 'secondary')
<span class="badge rounded-pill text-bg-{{ $tone }}">{{ ucfirst((string) $status) }}</span>
