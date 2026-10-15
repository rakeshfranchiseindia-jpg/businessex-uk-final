@extends('admin.layouts.app')

@section('title', 'SEO')
@section('subtitle', 'Meta content for listing pages by profile type and industry')

@section('content')
    <div class="card">
        <div class="card-body border-bottom d-flex flex-wrap gap-2 justify-content-between">
            @component('admin.partials.search', ['placeholder' => 'Title or keyword'])
                <select name="profile_type" class="form-select w-auto">
                    <option value="">Any profile type</option>
                    @foreach (config('admin.profile_types') as $value => $label)
                        <option value="{{ $value }}" @selected(request('profile_type') === (string) $value)>{{ $label }}</option>
                    @endforeach
                </select>
            @endcomponent
            <a href="{{ route('admin.seo.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New SEO entry</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Profile type</th><th>Industry</th><th>Title</th><th>Meta description</th><th>Updated</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($entries as $entry)
                    <tr>
                        <td>{{ config('admin.profile_types')[$entry->profile_type] ?? '—' }}</td>
                        <td class="small">{{ $categories[$entry->cat_id] ?? 'All industries' }}</td>
                        <td style="max-width:280px" class="fw-medium">{{ \Illuminate\Support\Str::limit($entry->title, 80) }}</td>
                        <td style="max-width:320px" class="small text-muted">{{ \Illuminate\Support\Str::limit($entry->meta_description, 110) }}</td>
                        <td class="small text-nowrap">{{ optional($entry->updated_at)->format('d M Y') }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.seo.edit', $entry) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @include('admin.partials.delete', ['action' => route('admin.seo.destroy', $entry)])
                        </td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 6])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $entries])
    </div>
@endsection
