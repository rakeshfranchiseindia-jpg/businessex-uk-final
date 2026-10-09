<?php

namespace Tests\Feature;

use Carbon\CarbonImmutable;
use App\Models\UserAccount;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ArticlePagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('bx_author', function (Blueprint $table): void {
            $table->integer('author_id')->primary();
            $table->string('author_name');
            $table->string('author_email');
            $table->string('author_desig')->nullable();
            $table->string('author_dept');
            $table->boolean('is_active');
            $table->timestamps();
        });

        Schema::create('bx_articles', function (Blueprint $table): void {
            $table->integer('article_id')->primary();
            $table->string('article_title');
            $table->text('short_desc');
            $table->longText('article_content');
            $table->integer('author_id');
            $table->string('image_path')->nullable();
            $table->string('listing_image_path')->nullable();
            $table->string('article_tags')->nullable();
            $table->boolean('article_status')->default(false);
            $table->string('seo_title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('seo_desc')->nullable();
            $table->unsignedInteger('article_views')->default(0);
            $table->unsignedInteger('article_comments')->default(0);
            $table->unsignedInteger('created_by')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        DB::table('bx_author')->insert([
            'author_id' => 1,
            'author_name' => 'Test Author',
            'author_email' => 'test@example.test',
            'author_desig' => 'Editor',
            'author_dept' => 'Editorial',
            'is_active' => 1,
        ]);

        Schema::create('articles_comments', function (Blueprint $table): void {
            $table->increments('comment_id');
            $table->integer('article_id');
            $table->string('comment_name', 255);
            $table->string('comment_email', 255);
            $table->text('comment_detail');
            $table->tinyInteger('comment_status')->default(0);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('bx_articles');
        Schema::dropIfExists('bx_author');
        Schema::dropIfExists('articles_comments');

        parent::tearDown();
    }

    public function test_listing_shows_published_articles_and_searches_by_keyword(): void
    {
        $this->insertArticle(1, 'Published global trade report', 'trade, exports');
        $this->insertArticle(2, 'Another published story', 'business');
        $this->insertArticle(3, 'Hidden global trade report', 'trade', false);

        $this->get(route('article', ['q' => 'global trade']))
            ->assertOk()
            ->assertSee('Published global trade report')
            ->assertDontSee('Hidden global trade report')
            ->assertDontSee('Another published story');
    }

    public function test_tag_filter_sort_and_pagination_use_database_results(): void
    {
        for ($id = 1; $id <= 20; $id++) {
            $this->insertArticle(
                $id,
                sprintf('Published Article %02d', $id),
                $id % 2 ? 'trade, exports' : 'business'
            );
        }

        $this->get(route('article', ['category' => 'trade', 'sort' => 'popular']))
            ->assertOk()
            ->assertSee('Published Article 19')
            ->assertDontSee('Published Article 01')
            ->assertDontSee('Published Article 02')
            ->assertSee('page=2');

        $this->get(route('article', ['category' => 'trade', 'sort' => 'popular', 'page' => 2]))
            ->assertOk()
            ->assertSee('Published Article 01')
            ->assertDontSee('Published Article 11');
    }

    public function test_detail_increments_views_and_sanitizes_stored_html(): void
    {
        $this->insertArticle(
            7,
            'Safe article detail',
            'trade',
            true,
            '<p>Safe content</p><script>alert(1)</script><a href="javascript:alert(2)" onclick="alert(3)">safe link</a>'
        );

        $this->get(route('articles.show', 7))
            ->assertOk()
            ->assertSee('Safe content')
            ->assertSee('safe link')
            ->assertDontSee('alert(1)')
            ->assertDontSee('alert(2)')
            ->assertDontSee('alert(3)');

        $this->assertDatabaseHas('bx_articles', [
            'article_id' => 7,
            'article_views' => 8,
        ]);
    }

    public function test_detail_returns_not_found_for_unpublished_articles(): void
    {
        $this->insertArticle(9, 'Draft article', 'trade', false);

        $this->get(route('articles.show', 9))->assertNotFound();
    }

    public function test_authenticated_user_can_submit_a_comment_for_a_published_article(): void
    {
        $this->insertArticle(12, 'Commentable article', 'business');
        $user = new UserAccount();
        $user->setRawAttributes([
            'user_id' => 42,
            'name' => 'Signed In Member',
            'email' => 'member@example.test',
        ], true);

        $this->actingAs($user)
            ->post(route('articles.comments.store', 12), [
                'comment_detail' => 'This article was useful.',
            ])
            ->assertRedirect(route('articles.show', 12))
            ->assertSessionHas('comment_submitted');

        $this->assertDatabaseHas('articles_comments', [
            'article_id' => 12,
            'comment_name' => 'Signed In Member',
            'comment_email' => 'member@example.test',
            'comment_detail' => 'This article was useful.',
            'comment_status' => 0,
        ]);

        $this->get(route('articles.show', 12))
            ->assertOk()
            ->assertDontSee('This article was useful.')
            ->assertSee('It will appear after it has been reviewed.');
    }

    public function test_guest_cannot_submit_article_comment_and_only_approved_comments_are_displayed(): void
    {
        $this->insertArticle(13, 'Moderated article', 'business');
        DB::table('articles_comments')->insert([
            [
                'article_id' => 13,
                'comment_name' => 'Approved Member',
                'comment_email' => 'approved@example.test',
                'comment_detail' => 'Visible approved comment.',
                'comment_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'article_id' => 13,
                'comment_name' => 'Pending Member',
                'comment_email' => 'pending@example.test',
                'comment_detail' => 'Hidden pending comment.',
                'comment_status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->post(route('articles.comments.store', 13), [
            'comment_detail' => 'Guest comment.',
        ])->assertRedirect(route('login'));

        $this->get(route('articles.show', 13))
            ->assertOk()
            ->assertSee('Approved Member')
            ->assertSee('Visible approved comment.')
            ->assertDontSee('Pending Member')
            ->assertDontSee('Hidden pending comment.')
            ->assertSee('Comments (1)');
    }

    public function test_date_filter_and_legacy_detail_url_work(): void
    {
        $this->insertArticle(10, 'Old date-filter article', 'trade', true, '<p>Old article body.</p>', CarbonImmutable::now()->subDays(45)->toDateTimeString());
        $this->insertArticle(11, 'Recent date-filter article', 'trade', true, '<p>Recent article body.</p>', CarbonImmutable::now()->subDays(5)->toDateTimeString());

        $this->get(route('article', ['q' => 'date-filter', 'period' => 'month']))
            ->assertOk()
            ->assertSee('Recent date-filter article')
            ->assertDontSee('Old date-filter article');

        $this->get(route('article-detail', ['id' => 11]))
            ->assertRedirect(route('articles.show', 11));
    }

    public function test_article_seeder_adds_ten_published_articles_and_is_safe_to_rerun(): void
    {
        $seeder = new \Database\Seeders\BxArticlesTableSeeder();
        $seeder->run();

        $this->assertSame(10, DB::table('bx_articles')->where('article_status', 1)->count());
        $this->assertSame(10, DB::table('bx_articles')->distinct()->count('article_title'));

        $seeder->run();

        $this->assertSame(10, DB::table('bx_articles')->count());
        $this->assertSame(10, DB::table('bx_articles')->where('article_status', 1)->count());
    }

    private function insertArticle(
        int $id,
        string $title,
        string $tags,
        bool $published = true,
        string $content = '<p>Article body for testing.</p>',
        ?string $publishedAt = null
    ): void {
        DB::table('bx_articles')->insert([
            'article_id' => $id,
            'article_title' => $title,
            'short_desc' => 'A summary of ' . $title,
            'article_content' => $content,
            'author_id' => 1,
            'image_path' => '',
            'listing_image_path' => '',
            'article_tags' => $tags,
            'article_status' => $published,
            'seo_title' => $title,
            'seo_keywords' => $tags,
            'seo_desc' => 'Description for ' . $title,
            'article_views' => $id,
            'article_comments' => 0,
            'created_at' => $publishedAt ?? '2026-08-24 06:31:29',
            'updated_at' => $publishedAt ?? '2026-08-24 06:31:29',
        ]);
    }
}
