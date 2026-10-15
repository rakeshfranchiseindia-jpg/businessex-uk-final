@extends('admin.layouts.app')

@section('title', $report->exists ? 'Edit Industry Report' : 'New Industry Report')

@section('content')
    <form method="POST" enctype="multipart/form-data"
          action="{{ $report->exists ? route('admin.industry-reports.update', $report) : route('admin.industry-reports.store') }}">
        @csrf
        @if ($report->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body row g-3">
                        @include('admin.partials.form.input', ['name' => 'industryreport_title', 'label' => 'Title', 'value' => $report->industryreport_title, 'required' => true, 'max' => 255])
                        @include('admin.partials.form.input', ['name' => 'industryreport_home_title', 'label' => 'Home page title', 'value' => $report->industryreport_home_title, 'max' => 100])
                        @include('admin.partials.form.textarea', ['name' => 'short_desc', 'label' => 'Short description', 'value' => $report->short_desc, 'required' => true, 'rows' => 3])
                        @include('admin.partials.form.editor', ['name' => 'industryreport_content', 'label' => 'Content', 'value' => $report->industryreport_content, 'required' => true])
                    </div>
                </div>
                @include('admin.partials.seo-fields', ['model' => $report])
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body row g-3">
                        @include('admin.partials.form.status', ['name' => 'industryreport_status', 'value' => $report->industryreport_status, 'on' => 'Published', 'off' => 'Draft'])
                        @include('admin.partials.form.select', ['name' => 'industry_sector', 'label' => 'Industry sector', 'options' => $sectors, 'value' => $report->industry_sector, 'placeholder' => 'Select sector'])
                        @include('admin.partials.form.select', ['name' => 'author_id', 'label' => 'Author', 'options' => $authors, 'value' => $report->author_id, 'placeholder' => 'Select author'])
                        @include('admin.partials.form.input', ['name' => 'industryreport_tags', 'label' => 'Tags', 'value' => $report->industryreport_tags, 'help' => 'Comma separated'])
                        @include('admin.partials.form.file', ['name' => 'image', 'label' => 'Main image', 'current' => $report->image_path])
                        @include('admin.partials.form.file', ['name' => 'listing_image', 'label' => 'Listing (thumbnail) image', 'current' => $report->listing_image_path])
                        @include('admin.partials.form.file', ['name' => 'pdf', 'label' => 'Report PDF', 'current' => $report->industryreport_pdf_path, 'isFile' => true, 'accept' => 'application/pdf', 'help' => 'PDF up to 20 MB.'])
                    </div>
                </div>
                @include('admin.partials.form.actions', ['cancel' => route('admin.industry-reports.index'), 'label' => $report->exists ? 'Update report' : 'Create report'])
            </div>
        </div>
    </form>
@endsection
