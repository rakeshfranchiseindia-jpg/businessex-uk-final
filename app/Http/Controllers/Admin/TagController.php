<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tag::query();

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where('tag_name', 'like', "%{$keyword}%");
        }

        $tags = $query->orderBy('tag_name')->paginate($this->perPage())->withQueryString();

        return view('admin.tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('admin.tags.form', ['tag' => new Tag(['tag_status' => 1])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Tag());

        return redirect()->route('admin.tags.index')->with('success', 'Tag created.');
    }

    public function edit(Tag $tag): View
    {
        return view('admin.tags.form', compact('tag'));
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $this->save($request, $tag);

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted.');
    }

    private function save(Request $request, Tag $tag): void
    {
        $data = $request->validate([
            'tag_name' => ['required', 'string', 'max:255', Rule::unique('content_tags', 'tag_name')->ignore($tag->tag_id, 'tag_id')],
            'tag_status' => ['required', 'in:0,1'],
        ]);

        $data['tag_slug'] = Str::slug($data['tag_name']);

        $tag->fill($data)->save();
    }
}
