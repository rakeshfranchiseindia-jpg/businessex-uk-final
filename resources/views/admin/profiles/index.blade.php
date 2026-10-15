@extends('admin.layouts.app')

@section('title', $config['label'].' Profiles')
@section('subtitle', 'Review, approve and manage '.strtolower($config['label']).' profiles')

@section('content')
    <ul class="nav nav-tabs nav-tabs-status mb-3">
        @foreach ($tabs as $key => $definition)
            <li class="nav-item">
                <a class="nav-link {{ $tab === $key ? 'active' : '' }}" href="{{ route('admin.profiles.index', [$config['slug'], 'status' => $key]) }}">
                    {{ $definition['label'] }} <span class="badge rounded-pill {{ $tab === $key ? 'text-bg-primary' : 'text-bg-light' }} ms-1">{{ $counts[$key] }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="card">
        <div class="card-body border-bottom">
            @component('admin.partials.search', ['placeholder' => 'ID, name, email, mobile, company...'])
                <input type="hidden" name="status" value="{{ $tab }}">
                <input type="date" name="from" value="{{ request('from') }}" class="form-control w-auto" title="Created from">
                <input type="date" name="to" value="{{ request('to') }}" class="form-control w-auto" title="Created to">
                @if ($config['slug'] !== 'lender')
                    <select name="paid" class="form-select w-auto">
                        <option value="">Any payment</option>
                        <option value="1" @selected(request('paid') === '1')>Paid</option>
                        <option value="0" @selected(request('paid') === '0')>Unpaid</option>
                    </select>
                @endif
            @endcomponent
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name / Headline</th>
                    <th>Contact</th>
                    <th>City</th>
                    <th>Status</th>
                    @if ($config['slug'] !== 'lender')<th>Plan</th>@endif
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($profiles as $profile)
                    @php($id = $profile->getKey())
                    <tr>
                        <td class="text-muted">
                            #{{ $id }}
                            @if ($profile->{$config['code']})<div class="small">{{ $profile->{$config['code']} }}</div>@endif
                        </td>
                        <td style="max-width: 320px">
                            <a href="{{ route('admin.profiles.show', [$config['slug'], $id]) }}" class="fw-semibold text-decoration-none">{{ $profile->{$config['name']} ?: 'Unnamed' }}</a>
                            <div class="small text-muted text-truncate">{{ $profile->{$config['headline']} ?: $profile->{$config['company']} }}</div>
                        </td>
                        <td class="small">
                            <div>{{ $profile->{$config['email']} }}</div>
                            <div class="text-muted">{{ $profile->{$config['mobile']} }}</div>
                        </td>
                        <td class="small">{{ $profile->{$config['city']} ?: '—' }}</td>
                        <td>@include('admin.partials.profile-status', ['status' => $profile->{$config['status']}])</td>
                        @if ($config['slug'] !== 'lender')
                            <td class="small">
                                {{ config('admin.membership')[$profile->membership_plan] ?? '—' }}
                                @if ($profile->membership_paid)<span class="badge text-bg-success ms-1">Paid</span>@endif
                            </td>
                        @endif
                        <td class="small text-nowrap">{{ optional($profile->created_at)->format('d M Y') }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.profiles.show', [$config['slug'], $id]) }}" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
                            @if ((int) $profile->{$config['status']} !== 1)
                                <form method="POST" action="{{ route('admin.profiles.approve', [$config['slug'], $id]) }}" data-confirm="Approve this profile and make it live?">
                                    @csrf
                                    <button class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check-lg"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 8, 'message' => 'No '.strtolower($tabs[$tab]['label']).' '.strtolower($config['label']).' profiles found.'])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $profiles])
    </div>
@endsection
