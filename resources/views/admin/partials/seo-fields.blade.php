<div class="card mt-3">
    <div class="card-header">SEO</div>
    <div class="card-body row g-3">
        @include('admin.partials.form.input', ['name' => 'seo_title', 'label' => 'Meta title', 'value' => $model->seo_title, 'max' => 255])
        @include('admin.partials.form.input', ['name' => 'seo_keywords', 'label' => 'Meta keywords', 'value' => $model->seo_keywords, 'max' => 255])
        @include('admin.partials.form.textarea', ['name' => 'seo_desc', 'label' => 'Meta description', 'value' => $model->seo_desc, 'max' => 255, 'rows' => 2])
    </div>
</div>
