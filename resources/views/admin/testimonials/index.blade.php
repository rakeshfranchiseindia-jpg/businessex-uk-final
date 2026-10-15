@extends('admin.layouts.app')

@section('title', 'Testimonials')
@section('subtitle', 'Shown on the website home page')

@section('content')
    <div class="card">
        <div class="card-body border-bottom d-flex justify-content-end">
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New testimonial</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th></th><th>Name</th><th>Testimonial</th><th>Rating</th><th class="text-center">Order</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($testimonials as $testimonial)
                    <tr>
                        <td style="width:60px">
                            @if ($testimonial->image_path)
                                <img src="{{ preg_match('#^https?://#', $testimonial->image_path) ? $testimonial->image_path : asset($testimonial->image_path) }}" class="thumb rounded-circle" alt="" onerror="this.style.visibility='hidden'">
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $testimonial->name }}</div>
                            <div class="small text-muted">{{ $testimonial->designation }}</div>
                        </td>
                        <td class="small" style="max-width:380px">{{ \Illuminate\Support\Str::limit($testimonial->text, 140) }}</td>
                        <td class="text-warning text-nowrap">
                            @for ($i = 1; $i <= 5; $i++)<i class="bi {{ $i <= (int) $testimonial->rating ? 'bi-star-fill' : 'bi-star' }}"></i>@endfor
                        </td>
                        <td class="text-center">{{ $testimonial->sort_order }}</td>
                        <td>@include('admin.partials.active-badge', ['active' => $testimonial->is_active])</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @include('admin.partials.delete', ['action' => route('admin.testimonials.destroy', $testimonial)])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 7])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $testimonials])
    </div>
@endsection
