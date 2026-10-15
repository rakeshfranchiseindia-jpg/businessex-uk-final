@extends('admin.layouts.app')

@php
    $id = $profile->getKey();
    $status = (int) $profile->{$config['status']};
    $imagePath = $profile->{$config['image']};
    $imageUrl = $imagePath ? (preg_match('#^https?://#', $imagePath) ? $imagePath : asset($imagePath)) : null;
    $initial = strtoupper(mb_substr($profile->{$config['name']} ?: '?', 0, 1));
    $hidden = ['user_id', $profile->getKeyName(), $config['status']];
@endphp

@section('title', $config['label'].' Profile #'.$id)
@section('subtitle', $profile->{$config['name']})

@section('content')
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.profiles.index', [$config['slug'], 'status' => 'all']) }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <div class="ms-auto d-flex flex-wrap gap-2">
            <a href="{{ route('admin.profiles.edit', [$config['slug'], $id]) }}" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
            @if ($status !== 1)
                <form method="POST" action="{{ route('admin.profiles.approve', [$config['slug'], $id]) }}" data-confirm="Approve this profile and make it live?">
                    @csrf
                    <button class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Approve</button>
                </form>
            @endif
            @if ($status !== 0)
                <form method="POST" action="{{ route('admin.profiles.deactivate', [$config['slug'], $id]) }}" data-confirm="Deactivate this profile? It will be hidden from the website.">
                    @csrf
                    <button class="btn btn-outline-secondary"><i class="bi bi-pause-circle me-1"></i>Deactivate</button>
                </form>
            @endif
            @if ($status !== 4)
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal"><i class="bi bi-x-circle me-1"></i>Reject</button>
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <div class="admin-avatar mx-auto mb-3 overflow-hidden" style="width:110px;height:110px;font-size:2.2rem">
                        @if ($imageUrl)
                            <img src="{{ $imageUrl }}" alt="" style="width:100%;height:100%;object-fit:cover" onerror="this.remove()">
                        @endif
                        <span>{{ $initial }}</span>
                    </div>
                    <h2 class="h5 mb-1">{{ $profile->{$config['name']} }}</h2>
                    <div class="text-muted small mb-2">{{ $profile->{$config['company']} }}</div>
                    @include('admin.partials.profile-status', ['status' => $status])
                </div>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Email</span><span class="text-end text-break ms-2">{{ $profile->{$config['email']} ?: '—' }}</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Mobile</span><span>{{ $profile->{$config['mobile']} ?: '—' }}</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Created</span><span>{{ optional($profile->created_at)->format('d M Y, H:i') }}</span></li>
                    @if ($profile->activated_at)
                        <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Activated</span><span>{{ \Illuminate\Support\Carbon::parse($profile->activated_at)->format('d M Y, H:i') }}</span></li>
                    @endif
                </ul>
            </div>

            <div class="card mb-3">
                <div class="card-header">Account owner</div>
                <div class="card-body small">
                    @if ($profile->user)
                        <div class="fw-semibold">{{ $profile->user->name }}</div>
                        <div class="text-muted">{{ $profile->user->email }}</div>
                        @if (auth('admin')->user()->canAccess('users'))
                            <a href="{{ route('admin.users.show', $profile->user->user_id) }}" class="d-inline-block mt-2">View user #{{ $profile->user->user_id }}</a>
                        @endif
                    @else
                        <span class="text-muted">No linked user account (user #{{ $profile->user_id ?? '—' }}).</span>
                    @endif
                </div>
            </div>

            @if (array_key_exists($config['slug'], \App\Services\Payments\MembershipPaymentService::PROFILE_TABLES))
                @php
                    $planLabels = array_flip(\App\Services\Payments\MembershipPaymentService::PLAN_CODES);
                    $currentPlanCode = (int) ($profile->membership_plan ?? 0);
                    $currentPlanLabel = $planLabels[$currentPlanCode] ?? null;
                @endphp
                <div class="card mb-3">
                    <div class="card-header">Membership plan</div>
                    <div class="card-body small">
                        <div class="mb-3">
                            <span class="text-muted">Current:</span>
                            @if ($profile->membership_paid && $currentPlanLabel)
                                <span class="badge bg-success">{{ ucfirst($currentPlanLabel) }}</span>
                            @else
                                <span class="badge bg-secondary">No active plan</span>
                            @endif
                        </div>
                        @if ($profile->membership_paid && $membership)
                            <div class="mb-3">
                                <span class="text-muted">Assigned by:</span>
                                @if ($membership->assignedByAdmin)
                                    {{ $membership->assignedByAdmin->admin_name }} ({{ $membership->assignedByAdmin->admin_email }})
                                @else
                                    Self
                                @endif
                            </div>
                        @endif
                        <form method="POST" action="{{ route('admin.profiles.plan.store', [$config['slug'], $id]) }}" class="d-flex gap-2" data-confirm="Grant this plan to the profile owner?">
                            @csrf
                            <select name="plan" class="form-select form-select-sm" required>
                                <option value="">Select plan…</option>
                                @foreach (array_keys(\App\Services\Payments\MembershipPaymentService::PLAN_CODES) as $planKey)
                                    <option value="{{ $planKey }}" @selected($currentPlanLabel === $planKey)>{{ ucfirst($planKey) }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-outline-primary">Assign</button>
                        </form>
                        @error('plan')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endif

            @if (isset($config['management']) && $management->isNotEmpty())
                <div class="card mb-3">
                    <div class="card-header">Management team</div>
                    <ul class="list-group list-group-flush small">
                        @foreach ($management as $member)
                            <li class="list-group-item">
                                <div class="fw-semibold">{{ $member->mgmt_name }}</div>
                                <div class="text-muted">{{ $member->mgmt_designation }}</div>
                                <div class="text-muted">{{ $member->mgmt_email }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($rejections->isNotEmpty())
                <div class="card">
                    <div class="card-header text-danger">Rejection history</div>
                    <ul class="list-group list-group-flush small">
                        @foreach ($rejections as $rejection)
                            <li class="list-group-item">
                                <div>{{ $rejection->rejected_reason }}</div>
                                <div class="text-muted">{{ $rejection->admin_email }} &middot; {{ optional($rejection->created_at)->format('d M Y') }}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            @if (isset($config['images']))
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Gallery &amp; documents</span>
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.profiles.images.store', [$config['slug'], $id]) }}" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                                @csrf
                                <select name="kind" class="form-select form-select-sm" style="width:auto">
                                    <option value="image">Image</option>
                                    <option value="document">Document</option>
                                </select>
                                <input type="file" name="file" class="form-control form-control-sm" style="width:auto" required>
                                <button class="btn btn-sm btn-outline-primary">Upload</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($images->isEmpty())
                            <span class="text-muted small">No images or documents uploaded.</span>
                        @else
                            <div class="row g-2">
                                @foreach ($images as $image)
                                    @php
                                        $isDocument = (int) $image->type === $config['images']['model']::TYPE_DOCUMENT;
                                        $path = $image->{$config['images']['path_column']};
                                        $url = $path ? (preg_match('#^https?://#', $path) ? $path : asset($path)) : null;
                                    @endphp
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div class="border rounded p-2 h-100 d-flex flex-column">
                                            @if ($isDocument)
                                                <a href="{{ $url }}" target="_blank" rel="noopener" class="small text-break mb-2"><i class="bi bi-file-earmark-text me-1"></i>{{ $image->{$config['images']['name_column']} ?: 'Document' }}</a>
                                            @else
                                                <a href="{{ $url }}" target="_blank" rel="noopener">
                                                    <img src="{{ $url }}" alt="" class="w-100 mb-2" style="height:90px;object-fit:cover;border-radius:4px">
                                                </a>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                                <form method="POST" action="{{ route('admin.profiles.images.toggle', [$config['slug'], $id, $image->getKey()]) }}">
                                                    @csrf
                                                    <button class="btn btn-sm {{ $image->is_active ? 'btn-outline-success' : 'btn-outline-secondary' }}">{{ $image->is_active ? 'Active' : 'Inactive' }}</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.profiles.images.destroy', [$config['slug'], $id, $image->getKey()]) }}" data-confirm="Remove this file?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">All profile details</div>
                <div class="card-body">
                    <dl class="row detail-list mb-0">
                        @foreach ($profile->getAttributes() as $column => $value)
                            @continue(in_array($column, $hidden, true) || $value === null || $value === '')
                            <dt class="col-sm-4">{{ \Illuminate\Support\Str::headline($column) }}</dt>
                            <dd class="col-sm-8">
                                @if (is_string($value) && preg_match('#^https?://#', $value))
                                    <a href="{{ $value }}" target="_blank" rel="noopener">{{ $value }}</a>
                                @else
                                    {!! nl2br(e($value)) !!}
                                @endif
                            </dd>
                        @endforeach
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.profiles.reject', [$config['slug'], $id]) }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject profile #{{ $id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="reason" class="form-label fw-medium">Reason for rejection<span class="text-danger">*</span></label>
                    <textarea id="reason" name="reason" class="form-control" rows="3" maxlength="190" required>{{ old('reason') }}</textarea>
                    <div class="form-text">Stored in the rejection history for this profile.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger">Reject profile</button>
                </div>
            </form>
        </div>
    </div>
@endsection
