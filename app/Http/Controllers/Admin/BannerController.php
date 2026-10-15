<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Banner::query();

        if (is_numeric($request->query('page_id'))) {
            $query->where('page', (int) $request->query('page_id'));
        }

        $banners = $query->orderBy('page')->orderBy('location')->paginate($this->perPage())->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.form', ['banner' => new Banner(['is_active' => 1])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Banner());

        return redirect()->route('admin.banners.index')->with('success', 'Banner created.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $this->save($request, $banner);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted.');
    }

    private function save(Request $request, Banner $banner): void
    {
        $banner->fill($request->validate([
            'page' => ['required', 'integer', 'in:'.implode(',', array_keys(config('admin.banner_page')))],
            'location' => ['required', 'integer', 'in:'.implode(',', array_keys(config('admin.banner_location')))],
            'dfp_id' => ['required', 'string', 'max:255'],
            'dfp_slot' => ['required', 'string', 'max:255'],
            'width' => ['required', 'integer', 'min:1', 'max:2000'],
            'height' => ['required', 'integer', 'min:1', 'max:2000'],
            'is_active' => ['required', 'in:0,1'],
        ]))->save();
    }
}
