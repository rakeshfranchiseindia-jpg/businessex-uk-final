<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Author;
use App\Models\Admin\BexShort;
use App\Models\Admin\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BexShortController extends Controller
{
    public function index(Request $request): View
    {
        $query = BexShort::query()->with('author');

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('bex_title', 'like', "%{$keyword}%")->orWhere('bex_id', $keyword));
        }

        $shorts = $query->latest('created_at')->paginate($this->perPage())->withQueryString();

        return view('admin.bex-shorts.index', compact('shorts'));
    }

    public function create(): View
    {
        return $this->form(new BexShort(['status' => 1]));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new BexShort(['created_by' => $this->admin()->admin_id]));

        return redirect()->route('admin.bex-shorts.index')->with('success', 'BEX short created.');
    }

    public function edit(BexShort $bexShort): View
    {
        return $this->form($bexShort);
    }

    public function update(Request $request, BexShort $bexShort): RedirectResponse
    {
        $this->save($request, $bexShort);

        return redirect()->route('admin.bex-shorts.index')->with('success', 'BEX short updated.');
    }

    public function destroy(BexShort $bexShort): RedirectResponse
    {
        $this->deleteUpload($bexShort->image_path);
        $bexShort->delete();

        return redirect()->route('admin.bex-shorts.index')->with('success', 'BEX short deleted.');
    }

    private function form(BexShort $short): View
    {
        return view('admin.bex-shorts.form', [
            'short' => $short,
            'authors' => Author::query()->orderBy('author_name')->pluck('author_name', 'author_id'),
            'tags' => Tag::query()->where('tag_status', 1)->orderBy('tag_name')->pluck('tag_name'),
        ]);
    }

    private function save(Request $request, BexShort $short): void
    {
        $data = $request->validate([
            'bex_title' => ['required', 'string', 'max:100'],
            'bex_description' => ['required', 'string'],
            'author_id' => ['nullable', 'integer', 'exists:bx_author,author_id'],
            'reference_page_name' => ['nullable', 'string', 'max:150'],
            'reference_page_link' => ['nullable', 'url', 'max:250'],
            'associated_tag' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:0,1'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'seo_desc' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['image_path'] = $this->storeUpload($request->file('image'), 'bex-shorts', $short->image_path);
        unset($data['image']);

        $short->fill($data)->save();
    }
}
