<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\IndustryCategory;
use App\Models\Admin\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Seo::query();

        if (is_numeric($request->query('profile_type'))) {
            $query->where('profile_type', (int) $request->query('profile_type'));
        }
        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$keyword}%")->orWhere('keyword', 'like', "%{$keyword}%"));
        }

        $entries = $query->latest('created_at')->paginate($this->perPage())->withQueryString();
        $categories = IndustryCategory::query()->whereIn('cat_id', $entries->pluck('cat_id')->filter())->pluck('category_name', 'cat_id');

        return view('admin.seo.index', compact('entries', 'categories'));
    }

    public function create(): View
    {
        return $this->form(new Seo());
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Seo());

        return redirect()->route('admin.seo.index')->with('success', 'SEO entry created.');
    }

    public function edit(Seo $seo): View
    {
        return $this->form($seo);
    }

    public function update(Request $request, Seo $seo): RedirectResponse
    {
        $this->save($request, $seo);

        return redirect()->route('admin.seo.index')->with('success', 'SEO entry updated.');
    }

    public function destroy(Seo $seo): RedirectResponse
    {
        $seo->delete();

        return redirect()->route('admin.seo.index')->with('success', 'SEO entry deleted.');
    }

    private function form(Seo $seo): View
    {
        return view('admin.seo.form', [
            'seo' => $seo,
            'categories' => IndustryCategory::query()->where('category_status', 1)->orderBy('category_name')->pluck('category_name', 'cat_id'),
        ]);
    }

    private function save(Request $request, Seo $seo): void
    {
        $seo->fill($request->validate([
            'profile_type' => ['required', 'integer', 'in:'.implode(',', array_keys(config('admin.profile_types')))],
            'cat_id' => ['nullable', 'integer', 'exists:industry_categories,cat_id'],
            'title' => ['required', 'string', 'max:500'],
            'keyword' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
        ]))->save();
    }
}
