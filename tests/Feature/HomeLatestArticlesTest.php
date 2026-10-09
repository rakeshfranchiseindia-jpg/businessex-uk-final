<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomeLatestArticlesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('bx_author', function (Blueprint $table): void {
            $table->integer('author_id')->primary();
            $table->string('author_name');
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
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        DB::table('bx_author')->insert([
            'author_id' => 1,
            'author_name' => 'BusinessX Editorial',
        ]);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('bx_articles');
        Schema::dropIfExists('bx_author');

        parent::tearDown();
    }

    public function test_homepage_shows_the_latest_twelve_published_articles(): void
    {
        for ($id = 1; $id <= 14; $id++) {
            $this->insertArticle($id, sprintf('Published Home Article %02d', $id), true);
        }
        $this->insertArticle(15, 'Draft Home Article', false);

        $response = $this->get(route('home'))
            ->assertOk()
            ->assertSee('Insights, Articles & News')
            ->assertSee('BusinessX Editorial');

        for ($id = 3; $id <= 14; $id++) {
            $response->assertSee(sprintf('Published Home Article %02d', $id));
        }

        $response->assertDontSee('Published Home Article 01')
            ->assertDontSee('Published Home Article 02')
            ->assertDontSee('Draft Home Article')
            ->assertSee(route('articles.show', 14), false);
    }

    private function insertArticle(int $id, string $title, bool $published): void
    {
        DB::table('bx_articles')->insert([
            'article_id' => $id,
            'article_title' => $title,
            'short_desc' => 'Summary for ' . $title,
            'article_content' => '<p>Content for ' . $title . '</p>',
            'author_id' => 1,
            'image_path' => null,
            'listing_image_path' => null,
            'article_tags' => 'business',
            'article_status' => $published,
            'created_at' => now()->subDays(15 - $id),
            'updated_at' => now()->subDays(15 - $id),
        ]);
    }
}
