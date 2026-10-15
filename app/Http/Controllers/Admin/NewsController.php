<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Author;
use App\Models\Admin\News;
use App\Models\Admin\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $query = News::query()->with('author');

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('news_title', 'like', "%{$keyword}%")
                ->orWhere('news_tags', 'like', "%{$keyword}%")
                ->orWhere('news_id', $keyword));
        }
        if (is_numeric($request->query('status'))) {
            $query->where('news_status', (int) $request->query('status'));
        }

        $news = $query->latest('created_at')->paginate($this->perPage())->withQueryString();

        return view('admin.news.index', compact('news'));
    }

    public function create(): View
    {
        return $this->form(new News(['news_status' => 1]));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new News(['created_by' => $this->admin()->admin_id, 'news_views' => 0, 'news_comments' => 0]));

        return redirect()->route('admin.news.index')->with('success', 'News created.');
    }

    public function edit(News $news): View
    {
        return $this->form($news);
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $this->save($request, $news);

        return redirect()->route('admin.news.index')->with('success', 'News updated.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $this->deleteUpload($news->image_path);
        $this->deleteUpload($news->listing_image_path);
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News deleted.');
    }

    private function form(News $news): View
    {
        return view('admin.news.form', [
            'news' => $news,
            'authors' => Author::query()->orderBy('author_name')->pluck('author_name', 'author_id'),
            'tags' => Tag::query()->where('tag_status', 1)->orderBy('tag_name')->pluck('tag_name'),
        ]);
    }

    private function save(Request $request, News $news): void
    {
        $data = $request->validate([
            'news_title' => ['required', 'string', 'max:255'],
            'short_desc' => ['required', 'string', 'max:255'],
            'news_content' => ['required', 'string'],
            'author_id' => ['nullable', 'integer', 'exists:bx_author,author_id'],
            'news_tags' => ['nullable', 'string', 'max:255'],
            'news_status' => ['required', 'in:0,1'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'seo_desc' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'listing_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['image_path'] = $this->storeUpload($request->file('image'), 'news', $news->image_path);
        $data['listing_image_path'] = $this->storeUpload($request->file('listing_image'), 'news', $news->listing_image_path);
        unset($data['image'], $data['listing_image']);

        $news->fill($data)->save();
    }
}
