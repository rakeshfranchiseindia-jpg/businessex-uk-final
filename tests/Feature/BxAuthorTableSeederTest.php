<?php

namespace Tests\Feature;

use Database\Seeders\BxAuthorTableSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BxAuthorTableSeederTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('bx_author', function (Blueprint $table): void {
            $table->increments('author_id');
            $table->string('author_name');
            $table->string('author_email');
            $table->string('author_desig');
            $table->string('author_dept');
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('bx_author');

        parent::tearDown();
    }

    public function test_author_seeder_updates_existing_ids_and_can_be_rerun(): void
    {
        DB::table('bx_author')->insert([
            'author_id' => 1,
            'author_name' => 'Existing Author',
            'author_email' => 'existing@example.test',
            'author_desig' => 'Editor',
            'author_dept' => 'Editorial',
            'is_active' => 1,
        ]);

        $seeder = new BxAuthorTableSeeder();
        $seeder->run();

        $this->assertDatabaseCount('bx_author', 3);
        $this->assertDatabaseHas('bx_author', [
            'author_id' => 1,
            'author_name' => 'Rajesh Kumar',
            'author_email' => 'admin@businessex.com',
        ]);

        $seeder->run();

        $this->assertDatabaseCount('bx_author', 3);
    }
}
