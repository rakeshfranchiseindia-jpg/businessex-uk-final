<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Author;
use App\Models\Admin\IndustryCategory;
use App\Models\Admin\IndustryReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndustryReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = IndustryReport::query()->with(['author', 'sector']);

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('industryreport_title', 'like', "%{$keyword}%")
                ->orWhere('industryreport_id', $keyword));
        }
        if (is_numeric($request->query('status'))) {
            $query->where('industryreport_status', (int) $request->query('status'));
        }

        $reports = $query->latest('created_at')->paginate($this->perPage())->withQueryString();

        return view('admin.industry-reports.index', compact('reports'));
    }

    public function create(): View
    {
        return $this->form(new IndustryReport(['industryreport_status' => 1]));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new IndustryReport([
            'created_by' => $this->admin()->admin_id,
            'industryreport_views' => 0,
            'industryreport_comments' => 0,
        ]));

        return redirect()->route('admin.industry-reports.index')->with('success', 'Industry report created.');
    }

    public function edit(IndustryReport $report): View
    {
        return $this->form($report);
    }

    public function update(Request $request, IndustryReport $report): RedirectResponse
    {
        $this->save($request, $report);

        return redirect()->route('admin.industry-reports.index')->with('success', 'Industry report updated.');
    }

    public function destroy(IndustryReport $report): RedirectResponse
    {
        $this->deleteUpload($report->image_path);
        $this->deleteUpload($report->listing_image_path);
        $this->deleteUpload($report->industryreport_pdf_path);
        $report->delete();

        return redirect()->route('admin.industry-reports.index')->with('success', 'Industry report deleted.');
    }

    private function form(IndustryReport $report): View
    {
        return view('admin.industry-reports.form', [
            'report' => $report,
            'authors' => Author::query()->orderBy('author_name')->pluck('author_name', 'author_id'),
            'sectors' => IndustryCategory::query()->where('parent_id', 0)->orderBy('category_name')->pluck('category_name', 'cat_id'),
        ]);
    }

    private function save(Request $request, IndustryReport $report): void
    {
        $data = $request->validate([
            'industryreport_title' => ['required', 'string', 'max:255'],
            'industryreport_home_title' => ['nullable', 'string', 'max:100'],
            'industry_sector' => ['nullable', 'integer', 'exists:industry_categories,cat_id'],
            'short_desc' => ['required', 'string', 'max:2000'],
            'industryreport_content' => ['required', 'string'],
            'author_id' => ['nullable', 'integer', 'exists:bx_author,author_id'],
            'industryreport_tags' => ['nullable', 'string', 'max:255'],
            'industryreport_status' => ['required', 'in:0,1'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'seo_desc' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'listing_image' => ['nullable', 'image', 'max:4096'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        $data['image_path'] = $this->storeUpload($request->file('image'), 'industry-reports', $report->image_path);
        $data['listing_image_path'] = $this->storeUpload($request->file('listing_image'), 'industry-reports', $report->listing_image_path);
        $data['industryreport_pdf_path'] = $this->storeUpload($request->file('pdf'), 'industry-reports', $report->industryreport_pdf_path);
        unset($data['image'], $data['listing_image'], $data['pdf']);

        $report->fill($data)->save();
    }
}
