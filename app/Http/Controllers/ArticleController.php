<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use DOMDocument;
use DOMElement;
use DOMNode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    private const PAGE_SIZE = 9;

    private const DATE_FILTERS = [
        'day' => 1,
        'week' => 7,
        'month' => 30,
        'half-year' => 183,
        'year' => 365,
    ];

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:120'],
            'sort' => ['nullable', 'in:recent,popular,comments,title'],
            'period' => ['nullable', 'in:all,day,week,month,half-year,year'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $available = Schema::hasTable('bx_articles');
        $categories = $available ? $this->categories() : [];
        $categoryMap = collect($categories)->keyBy('slug');
        $selectedCategory = $categoryMap->get($filters['category'] ?? '');

        if (!empty($filters['category']) && !$selectedCategory) {
            abort(404, 'Article category not found.');
        }

        $articles = $available
            ? $this->articleQuery()
                ->where('bx_articles.article_status', 1)
                ->when(!empty($filters['q']), function ($query) use ($filters): void {
                    $term = '%' . trim($filters['q']) . '%';
                    $query->where(function ($search) use ($term): void {
                        $search->where('bx_articles.article_title', 'like', $term)
                            ->orWhere('bx_articles.short_desc', 'like', $term)
                            ->orWhere('bx_articles.article_content', 'like', $term)
                            ->orWhere('bx_articles.article_tags', 'like', $term)
                            ->orWhere('bx_articles.seo_keywords', 'like', $term);
                    });
                })
                ->when($selectedCategory, fn ($query) => $this->filterByTag($query, $selectedCategory['name']))
                ->when(!empty($filters['period']) && $filters['period'] !== 'all', function ($query) use ($filters): void {
                    $cutoff = CarbonImmutable::now()->subDays(self::DATE_FILTERS[$filters['period']]);
                    $query->where(function ($dateQuery) use ($cutoff): void {
                        $dateQuery->where('bx_articles.created_at', '>=', $cutoff)
                            ->orWhere(function ($fallbackDate) use ($cutoff): void {
                                $fallbackDate->whereNull('bx_articles.created_at')
                                    ->where('bx_articles.updated_at', '>=', $cutoff);
                            });
                    });
                })
                ->when(($filters['sort'] ?? 'recent') === 'popular', fn ($query) => $query->orderByDesc('bx_articles.article_views'))
                ->when(($filters['sort'] ?? 'recent') === 'comments', fn ($query) => $query->orderByDesc('bx_articles.article_comments'))
                ->when(($filters['sort'] ?? 'recent') === 'title', fn ($query) => $query->orderBy('bx_articles.article_title'))
                ->when(($filters['sort'] ?? 'recent') === 'recent', fn ($query) => $query->orderByRaw('COALESCE(bx_articles.created_at, bx_articles.updated_at) DESC'))
                ->orderByDesc('bx_articles.article_id')
                ->paginate(self::PAGE_SIZE)
                ->withQueryString()
            : $this->emptyPaginator($request);

        foreach ($articles as $article) {
            $article->image_url = $this->imageUrl($article->listing_image_path ?: $article->image_path, $article->article_id);
            $article->tag_list = $this->splitTags($article->article_tags);
            $article->read_minutes = $this->readingTime($article->article_content);
            $publishedAt = $article->created_at ?: $article->updated_at;
            $article->published_at_label = $publishedAt
                ? CarbonImmutable::parse($publishedAt)->format('d M Y')
                : 'Recently';
        }

        return view('pages.article', [
            'articles' => $articles,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'filters' => array_merge(['q' => '', 'category' => '', 'sort' => 'recent', 'period' => 'all'], $filters),
            'totalArticles' => $available ? DB::table('bx_articles')->where('article_status', 1)->count() : 0,
            'articlesAvailable' => $available,
            'page' => 'article',
            'showHeader' => true,
            'showFooter' => true,
        ]);
    }

    public function show(int $article): View
    {
        $this->ensureArticleTableExists();

        $record = $this->articleQuery()
            ->where('bx_articles.article_id', $article)
            ->where('bx_articles.article_status', 1)
            ->firstOrFail();

        DB::table('bx_articles')
            ->where('article_id', $record->article_id)
            ->increment('article_views');

        $record->article_views++;
        $record->tag_list = $this->splitTags($record->article_tags);
        $record->content_html = $this->sanitizeArticleContent($record->article_content);
        $record->image_url = $this->imageUrl($record->image_path ?: $record->listing_image_path, $record->article_id);
        $record->read_minutes = $this->readingTime($record->article_content);
        $record->author_name = $record->author_name ?: 'BusinessX Editorial';
        $record->author_role = $record->author_desig ?: 'Editorial Team';
        $publishedAt = $record->created_at ?: $record->updated_at;
        $record->published_at_label = $publishedAt
            ? CarbonImmutable::parse($publishedAt)->format('d M Y')
            : 'Recently';

        $relatedQuery = $this->articleQuery()
            ->where('bx_articles.article_status', 1)
            ->where('bx_articles.article_id', '!=', $record->article_id);

        if ($record->tag_list !== []) {
            $relatedQuery->where(function ($query) use ($record): void {
                foreach ($record->tag_list as $tag) {
                    $this->filterByTag($query, $tag, 'or');
                }
            });
        }

        $related = $relatedQuery
            ->orderByDesc('bx_articles.article_views')
            ->limit(4)
            ->get();

        if ($related->isEmpty()) {
            $related = $this->articleQuery()
                ->where('bx_articles.article_status', 1)
                ->where('bx_articles.article_id', '!=', $record->article_id)
                ->orderByDesc('bx_articles.article_views')
                ->limit(4)
                ->get();
        }

        foreach ($related as $relatedArticle) {
            $relatedArticle->image_url = $this->imageUrl(
                $relatedArticle->listing_image_path ?: $relatedArticle->image_path,
                $relatedArticle->article_id
            );
            $relatedArticle->read_minutes = $this->readingTime($relatedArticle->article_content);
            $publishedAt = $relatedArticle->created_at ?: $relatedArticle->updated_at;
            $relatedArticle->published_at_label = $publishedAt
                ? CarbonImmutable::parse($publishedAt)->format('d M Y')
                : 'Recently';
        }

        $popular = $this->articleQuery()
            ->where('bx_articles.article_status', 1)
            ->where('bx_articles.article_id', '!=', $record->article_id)
            ->orderByDesc('bx_articles.article_views')
            ->limit(5)
            ->get();

        foreach ($popular as $popularArticle) {
            $popularArticle->read_minutes = $this->readingTime($popularArticle->article_content);
            $publishedAt = $popularArticle->created_at ?: $popularArticle->updated_at;
            $popularArticle->published_at_label = $publishedAt
                ? CarbonImmutable::parse($publishedAt)->format('d M Y')
                : 'Recently';
        }

        $comments = collect();
        $commentCount = (int) $record->article_comments;
        if (Schema::hasTable('articles_comments')) {
            $comments = DB::table('articles_comments')
                ->where('article_id', $record->article_id)
                ->where('comment_status', 1)
                ->orderByDesc('created_at')
                ->get(['comment_name', 'comment_detail', 'created_at']);
            $commentCount = $comments->count();
        }
        $record->article_comments = $commentCount;

        return view('pages.artical-detail', [
            'article' => $record,
            'comments' => $comments,
            'relatedArticles' => $related,
            'popularArticles' => $popular,
            'tagCategories' => array_slice($this->categories(), 0, 16),
            'page' => 'article',
            'showHeader' => false,
            'showFooter' => false,
        ]);
    }

    public function legacyDetail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'min:1'],
        ]);

        return redirect()->route('articles.show', ['article' => $validated['id']]);
    }

    private function articleQuery(): Builder
    {
        $query = DB::table('bx_articles')->select('bx_articles.*');

        if (Schema::hasTable('bx_author')) {
            $query->leftJoin('bx_author', 'bx_author.author_id', '=', 'bx_articles.author_id')
                ->addSelect('bx_author.author_name', 'bx_author.author_desig');
        } else {
            $query->addSelect(DB::raw('NULL as author_name'), DB::raw('NULL as author_desig'));
        }

        return $query;
    }

    private function filterByTag(Builder $query, string $tag, string $boolean = 'and'): Builder
    {
        $normalized = mb_strtolower(trim($tag));
        $driver = DB::connection()->getDriverName();
        $tagList = $driver === 'sqlite'
            ? "LOWER(',' || REPLACE(bx_articles.article_tags, ', ', ',') || ',')"
            : "LOWER(CONCAT(',', REPLACE(bx_articles.article_tags, ', ', ','), ','))";

        return $query->whereRaw($tagList . ' LIKE ?', ['%,' . $normalized . ',%'], $boolean);
    }

    private function categories(): array
    {
        if (!Schema::hasTable('bx_articles')) {
            return [];
        }

        $tagCounts = [];
        DB::table('bx_articles')
            ->where('article_status', 1)
            ->pluck('article_tags')
            ->each(function (?string $articleTags) use (&$tagCounts): void {
                foreach ($this->splitTags($articleTags) as $tag) {
                    $slug = Str::slug($tag);
                    if ($slug !== '') {
                        $tagCounts[$slug] ??= ['name' => $tag, 'slug' => $slug, 'count' => 0];
                        $tagCounts[$slug]['count']++;
                    }
                }
            });

        return array_values($tagCounts);
    }

    private function splitTags(?string $tags): array
    {
        if (!$tags) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map('trim', explode(',', $tags)))));
    }

    private function imageUrl(?string $path, int $articleId): string
    {
        if ($path && preg_match('#^https?://#i', $path)) {
            return $path;
        }

        if ($path) {
            $relativePath = ltrim(str_replace('\\', '/', $path), '/');
            if (!str_contains($relativePath, '..') && is_file(public_path($relativePath))) {
                return asset($relativePath);
            }
        }

        return asset('assets/img/article-' . ((($articleId - 1) % 4) + 1) . '.jpg');
    }

    private function readingTime(?string $content): int
    {
        $wordCount = str_word_count(strip_tags((string) $content));

        return max(1, (int) ceil($wordCount / 200));
    }

    private function sanitizeArticleContent(?string $content): string
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="article-content">' . (string) $content . '</div>',
            LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $container = $document->getElementById('article-content');
        if (!$container) {
            return e(strip_tags((string) $content));
        }

        $this->sanitizeNodes($container);
        $html = '';
        foreach ($container->childNodes as $node) {
            $html .= $document->saveHTML($node);
        }

        return $html;
    }

    private function sanitizeNodes(DOMNode $parent): void
    {
        $allowed = ['p', 'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'strong', 'em', 'b', 'i', 'blockquote', 'a', 'br'];
        $removeEntirely = ['script', 'style', 'iframe', 'object', 'svg', 'form'];

        foreach (iterator_to_array($parent->childNodes) as $child) {
            if (!$child instanceof DOMElement) {
                if ($child->nodeType !== XML_TEXT_NODE) {
                    $parent->removeChild($child);
                }
                continue;
            }

            $tag = strtolower($child->tagName);
            if (in_array($tag, $removeEntirely, true)) {
                $parent->removeChild($child);
                continue;
            }

            $this->sanitizeNodes($child);

            if (!in_array($tag, $allowed, true)) {
                while ($child->firstChild) {
                    $parent->insertBefore($child->firstChild, $child);
                }
                $parent->removeChild($child);
                continue;
            }

            if ($tag === 'a') {
                $href = trim($child->getAttribute('href'));
            } else {
                $href = '';
            }

            while ($child->attributes->length > 0) {
                $child->removeAttributeNode($child->attributes->item(0));
            }

            if ($tag === 'a' && $href !== '' && preg_match('~^(https?://|mailto:|/|#)~i', $href)) {
                $child->setAttribute('href', $href);
            }
        }
    }

    private function emptyPaginator(Request $request): LengthAwarePaginator
    {
        return new \Illuminate\Pagination\LengthAwarePaginator(
            [],
            0,
            self::PAGE_SIZE,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(),
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    private function ensureArticleTableExists(): void
    {
        if (!Schema::hasTable('bx_articles')) {
            abort(503, 'Article content is not available until the BusinessX article database has been configured.');
        }
    }
}
