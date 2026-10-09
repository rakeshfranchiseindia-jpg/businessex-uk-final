<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class IndustryFooterCategoriesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('industry_categories', function (Blueprint $table): void {
            $table->increments('cat_id');
            $table->string('category_name');
            $table->string('category_slug');
            $table->integer('parent_id')->default(0);
            $table->tinyInteger('category_status')->default(1);
            $table->timestamps();
        });

        DB::table('industry_categories')->insert([
            $this->category(1, 'Test Industry', 0),
            $this->category(2, 'Subcategory One', 1),
            $this->category(3, 'Subcategory Two', 1),
            $this->category(4, 'Subcategory Three', 1),
            $this->category(5, 'Subcategory Four', 1),
            $this->category(6, 'Subcategory Five', 1),
            $this->category(7, 'Inactive Category', 0, 0),
        ]);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('industry_categories');

        parent::tearDown();
    }

    public function test_footer_shows_four_subcategories_and_view_all_links_for_each_listing_tab(): void
    {
        $response = $this->get(route('home'))->assertOk();

        foreach (['business', 'startup', 'investor'] as $type) {
            $listingRoute = $type . '-listing';
            $response->assertSee(route($listingRoute, ['type' => $type, 'industry' => 2]));
            $response->assertSee(route($listingRoute, ['type' => $type, 'industry_parent' => 1]));
        }

        $response->assertSee('Subcategory Four')
            ->assertSee('View all')
            ->assertDontSee('Subcategory Five')
            ->assertDontSee('Inactive Category');
    }

    public function test_listing_pages_use_database_categories_for_filtering(): void
    {
        foreach (['business', 'startup', 'investor'] as $type) {
            $this->get(route($type . '-listing', [
                'type' => $type,
                'industry' => 2,
            ]))
                ->assertOk()
                ->assertSee('"id":2', false)
                ->assertSee('const categoryId = params.get', false);
        }
    }

    private function category(int $id, string $name, int $parentId, int $status = 1): array
    {
        return [
            'cat_id' => $id,
            'category_name' => $name,
            'category_slug' => strtolower(str_replace(' ', '-', $name)),
            'parent_id' => $parentId,
            'category_status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
