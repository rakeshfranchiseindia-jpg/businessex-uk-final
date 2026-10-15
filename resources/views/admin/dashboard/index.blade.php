@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Overview of members, profiles and activity')

@section('content')
    <form method="GET" class="d-flex flex-wrap gap-2 align-items-end mb-4">
        <div>
            <label class="form-label small text-muted mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-control">
        </div>
        <div>
            <label class="form-label small text-muted mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-control">
        </div>
        <button class="btn btn-navy">Apply</button>
        @if (request('from') || request('to'))
            <a href="{{ route('admin.dashboard') }}" class="btn btn-link text-muted">Reset</a>
        @endif
    </form>

    @php
        $tiles = [
            ['label' => 'Registered Users', 'value' => number_format($stats['users']), 'icon' => 'bi-people', 'tone' => 'primary', 'route' => auth('admin')->user()->canAccess('users') ? route('admin.users.index') : null],
            ['label' => 'Total Profiles', 'value' => number_format($stats['profiles']), 'icon' => 'bi-person-vcard', 'tone' => 'info', 'route' => null],
            ['label' => 'Pending Approval', 'value' => number_format($stats['pending']), 'icon' => 'bi-hourglass-split', 'tone' => 'warning', 'route' => null],
            ['label' => 'Revenue (paid)', 'value' => number_format($stats['revenue'], 2), 'icon' => 'bi-cash-stack', 'tone' => 'success', 'route' => auth('admin')->user()->canAccess('payments') ? route('admin.payments.index', ['status' => 1]) : null],
            ['label' => 'Conversations', 'value' => number_format($stats['conversations']), 'icon' => 'bi-chat-dots', 'tone' => 'secondary', 'route' => auth('admin')->user()->canAccess('interactions') ? route('admin.conversations.index') : null],
            ['label' => 'Proposals', 'value' => number_format($stats['proposals']), 'icon' => 'bi-file-earmark-text', 'tone' => 'secondary', 'route' => auth('admin')->user()->canAccess('interactions') ? route('admin.proposals.index') : null],
            ['label' => 'Articles', 'value' => number_format($stats['articles']), 'icon' => 'bi-journal-richtext', 'tone' => 'secondary', 'route' => auth('admin')->user()->canAccess('content') ? route('admin.articles.index') : null],
            ['label' => 'Contact Enquiries', 'value' => number_format($stats['enquiries']), 'icon' => 'bi-envelope', 'tone' => 'secondary', 'route' => auth('admin')->user()->canAccess('enquiries') ? route('admin.enquiries.contact') : null],
        ];
    @endphp

    <div class="row g-3 mb-4">
        @foreach ($tiles as $tile)
            <div class="col-6 col-md-4 col-xl-3">
                <div class="card stat-card h-100 {{ $tile['route'] ? 'position-relative' : '' }}">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="icon bg-{{ $tile['tone'] }}-subtle text-{{ $tile['tone'] }}-emphasis"><i class="bi {{ $tile['icon'] }}"></i></span>
                        <div class="min-w-0">
                            <div class="value">{{ $tile['value'] }}</div>
                            <div class="text-muted small text-truncate">{{ $tile['label'] }}</div>
                        </div>
                        @if ($tile['route'])
                            <a href="{{ $tile['route'] }}" class="stretched-link" aria-label="{{ $tile['label'] }}"></a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header">Profiles by type</div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Active</th>
                            <th class="text-end">Pending</th>
                            <th class="text-end">Inactive</th>
                            <th class="text-end">Rejected</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($profiles as $slug => $row)
                            <tr>
                                <td class="fw-medium">
                                    @if (auth('admin')->user()->canAccess('profiles'))
                                        <a href="{{ route('admin.profiles.index', [$slug, 'status' => 'all']) }}" class="text-decoration-none">{{ $row['label'] }}</a>
                                    @else
                                        {{ $row['label'] }}
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">{{ number_format($row['total']) }}</td>
                                <td class="text-end text-success">{{ number_format($row['active']) }}</td>
                                <td class="text-end">
                                    @if ($row['pending'] && auth('admin')->user()->canAccess('profiles'))
                                        <a href="{{ route('admin.profiles.index', [$slug, 'status' => 'pending']) }}" class="badge text-bg-warning text-decoration-none">{{ $row['pending'] }}</a>
                                    @else
                                        {{ number_format($row['pending']) }}
                                    @endif
                                </td>
                                <td class="text-end text-muted">{{ number_format($row['inactive']) }}</td>
                                <td class="text-end text-danger">{{ number_format($row['rejected']) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">Users by registration source</div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    @if ($usersBySource->sum())
                        <canvas id="sourceChart" height="240"></canvas>
                    @else
                        <p class="text-muted mb-0">No registrations in this period.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header">User registrations &mdash; last 12 months</div>
                <div class="card-body"><canvas id="registrationChart" height="110"></canvas></div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Latest users
                    @if (auth('admin')->user()->canAccess('users'))
                        <a href="{{ route('admin.users.index') }}" class="small text-decoration-none">View all</a>
                    @endif
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($recentUsers as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="min-w-0">
                                <div class="fw-medium text-truncate">{{ $user->name ?: 'â€”' }}</div>
                                <div class="small text-muted text-truncate">{{ $user->email }}</div>
                            </div>
                            <span class="small text-muted text-nowrap ms-2">{{ optional($user->created_at)->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No users yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const palette = ['#1d4ed8', '#f5a623', '#10b981', '#ef4444', '#8b5cf6', '#06b6d4', '#64748b'];
            const source = @json($usersBySource);
            const sourceCanvas = document.getElementById('sourceChart');
            if (sourceCanvas) {
                new Chart(sourceCanvas, {
                    type: 'doughnut',
                    data: { labels: Object.keys(source), datasets: [{ data: Object.values(source), backgroundColor: palette, borderWidth: 0 }] },
                    options: { plugins: { legend: { position: 'bottom' } }, cutout: '62%' }
                });
            }
            const regs = @json($registrations);
            new Chart(document.getElementById('registrationChart'), {
                type: 'bar',
                data: { labels: Object.keys(regs), datasets: [{ label: 'Registrations', data: Object.values(regs), backgroundColor: '#1d4ed8', borderRadius: 6, maxBarThickness: 32 }] },
                options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } } }
            });
        })();
    </script>
@endpush
