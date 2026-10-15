@extends('admin.layouts.app')

@section('title', 'Chatbot Leads')

@section('content')
    <div class="card">
        <div class="card-body border-bottom">
            @component('admin.partials.search', ['placeholder' => 'Name, email, mobile or message'])
            @endcomponent
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Name</th><th>Email</th><th>Mobile</th><th>Message</th><th>Received</th></tr></thead>
                <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td class="fw-medium">{{ $row->name }}</td>
                        <td class="small"><a href="mailto:{{ $row->email }}">{{ $row->email }}</a></td>
                        <td class="small">{{ $row->mobile }}</td>
                        <td class="small" style="max-width:420px">{{ $row->message }}</td>
                        <td class="small text-nowrap">{{ optional($row->created_at)->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    @include('admin.partials.empty', ['colspan' => 5])
                @endforelse
                </tbody>
            </table>
        </div>
        @include('admin.partials.pagination', ['paginator' => $rows])
    </div>
@endsection
