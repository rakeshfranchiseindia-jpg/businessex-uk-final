<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TestimonialSeederTest extends TestCase
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

    public function test_seeder_inserts_five_display_ready_testimonials_and_is_safe_to_rerun(): void
    {
        $seeder = new \Database\Seeders\TestimonialSeeder();
        $seeder->run();

        $this->assertDatabaseCount('testimonials', 5);
        $this->assertSame(5, DB::table('testimonials')->where('is_active', 1)->count());
        $this->assertSame(5, DB::table('testimonials')->whereBetween('rating', [1, 5])->count());
        $this->assertSame(5, DB::table('testimonials')->whereNotNull('image_path')->count());

        $seeder->run();

        $this->assertDatabaseCount('testimonials', 5);
    }
}
