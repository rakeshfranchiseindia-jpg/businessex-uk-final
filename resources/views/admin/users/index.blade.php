@extends('admin.layouts.app')

@section('title', 'Users')
@section('subtitle', 'Registered member accounts')

@section('content')
    <div class="card">
        <div class="card-body border-bottom">
            @component('admin.partials.search', ['placeholder' => 'ID, name, email, mobile, company...'])
                <select name="profile" class="form-select w-auto">
                    <option value="">All users</option>
                    <option value="with" @selected(request('profile') === 'with')>With profile</option>
                    <option value="without" @selected(request('profile') === 'without')>Without profile</option>
                </select>
                <select name="source" class="form-select w-auto">
                    <option value="">Any source</option>
                    @foreach (config('admin.reg_source') as $value => $label)
                        <option value="{{ $value }}" @selected(request('source') === (string) $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control w-auto" title="Registered from">
                <input type="date" name="to" value="{{ request('to') }}" class="form-control w-auto" title="Registered to">
            @endcomponent
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Company</th>
                    <th>Source</th>
                    <th class="text-center">Profiles</th>
                    <th>Verified</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="text-muted">#{{ $user->user_id }}</td>
                        <td><a href="{{ route('admin.users.show', $user) }}" class="fw-semibold text-decoration-none">{{ $user->name ?: '—' }}</a></td>
                        <td class="small">
                            <div>{{ $user->email }}</div>
                            <div class="text-muted">{{ $user->mobile }}</div>
                        </td>
                        <td class="small">{{ $user->company_name ?: '—' }}</td>
                        <td class="small">{{ config('admin.reg_source')[$user->reg_source] ?? '—' }}</td>
                        <td class="text-center"><span class="badge {{ $user->profiles_count ? 'text-bg-primary' : 'text-bg-light' }}">{{ $user->profiles_count }}</span></td>
                        <td>
                            @if ($user->email_verified_at)
                                <i class="bi bi-patch-check-fill text-success" title="Verified {{ $user->email_verified_at->format('d M Y') }}"></i>
                            @else
                                <i class="bi bi-dash-circle text-muted" title="Not verified"></i>
                            @endif
                        </td>
                        <td>@include('admin.partials.active-badge', ['active' => $user->is_active])</td>
                        <td class="small text-nowrap">{{ optional($user->created_at)->format('d M Y') }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 10])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $users])
    </div>
@endsection
