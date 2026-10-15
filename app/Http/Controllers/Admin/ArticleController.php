<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Article;
use App\Models\Admin\Author;
use App\Models\Admin\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::query()->with('author')->withCount('comments');

        if ($keyword = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q->where('article_title', 'like', "%{$keyword}%")
                ->orWhere('article_tags', 'like', "%{$keyword}%")
                ->orWhere('article_id', $keyword));
        }
        if (is_numeric($request->query('status'))) {
            $query->where('article_status', (int) $request->query('status'));
        }

        $articles = $query->latest('created_at')->paginate($this->perPage())->withQueryString();

        return view('admin.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return $this->form(new Article(['article_status' => 1]));
    }

    public function store(Request $request): RedirectResponse
    {
        $article = new Article(['created_by' => $this->admin()->admin_id, 'article_views' => 0, 'article_comments' => 0]);
        $this->save($request, $article);

        return redirect()->route('admin.articles.index')->with('success', 'Article created.');
    }

    public function edit(Article $article): View
    {
        return $this->form($article);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $this->save($request, $article);

        return redirect()->route('admin.articles.index')->with('success', 'Article updated.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $this->deleteUpload($article->image_path);
        $this->deleteUpload($article->listing_image_path);
        $article->comments()->delete();
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted.');
    }

    private function form(Article $article): View
    {
        return view('admin.articles.form', [
            'article' => $article,
            'authors' => Author::query()->orderBy('author_name')->pluck('author_name', 'author_id'),
            'tags' => Tag::query()->where('tag_status', 1)->orderBy('tag_name')->pluck('tag_name'),
        ]);
    }

    private function save(Request $request, Article $article): void
    {
        $data = $request->validate([
            'article_title' => ['required', 'string', 'max:255'],
            'short_desc' => ['required', 'string', 'max:2000'],
            'article_content' => ['required', 'string'],
            'author_id' => ['nullable', 'integer', 'exists:bx_author,author_id'],
            'article_tags' => ['nullable', 'string', 'max:255'],
            'article_status' => ['required', 'in:0,1'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'seo_desc' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'listing_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['image_path'] = $this->storeUpload($request->file('image'), 'articles', $article->image_path);
        $data['listing_image_path'] = $this->storeUpload($request->file('listing_image'), 'articles', $article->listing_image_path);
        unset($data['image'], $data['listing_image']);

        $article->fill($data)->save();
    }
}
