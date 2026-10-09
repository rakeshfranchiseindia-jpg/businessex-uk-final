<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomeTestimonialsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('testimonials', function (Blueprint $table): void {
            $table->increments('id');
            $table->text('text');
            $table->string('name');
            $table->string('designation')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('testimonials');

        parent::tearDown();
    }

    public function test_homepage_shows_only_the_six_ordered_active_testimonials_with_ratings(): void
    {
        for ($id = 1; $id <= 7; $id++) {
            $this->insertTestimonial($id, 'Client ' . $id, true, $id);
        }
        $this->insertTestimonial(8, 'Hidden Client', false, 0);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('What Our Clients Say')
            ->assertSee('Client 1')
            ->assertSee('Client 6')
            ->assertDontSee('Client 7')
            ->assertDontSee('Hidden Client')
            ->assertSee('aria-label="1 out of 5 stars"', false)
            ->assertSee('aria-label="5 out of 5 stars"', false)
            ->assertSee(asset('assets/img/default-business-profile.png'));
    }

    private function insertTestimonial(int $id, string $name, bool $active, int $sortOrder): void
    {
        DB::table('testimonials')->insert([
            'id' => $id,
            'text' => 'Review written by ' . $name,
            'name' => $name,
            'designation' => 'Client',
            'rating' => $id,
            'image_path' => null,
            'is_active' => $active,
            'sort_order' => $sortOrder,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
