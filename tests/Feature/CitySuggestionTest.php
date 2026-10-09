<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CitySuggestionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('bx_cities', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('city');
            $table->string('state');
            $table->integer('country')->default(5);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('bx_cities');

        parent::tearDown();
    }

    public function test_suggestions_match_uk_city_prefixes_only(): void
    {
        DB::table('bx_cities')->insert([
            ['city' => 'London', 'state' => 'England', 'country' => 5],
            ['city' => 'Londonderry', 'state' => 'Northern Ireland', 'country' => 5],
            ['city' => 'Lahore', 'state' => 'Punjab', 'country' => 1],
        ]);

        $this->getJson(route('uk-cities.suggest', ['q' => 'lon']))
            ->assertOk()
            ->assertExactJson(['cities' => ['London', 'Londonderry']]);
    }

    public function test_suggestions_require_at_least_two_characters(): void
    {
        $this->getJson(route('uk-cities.suggest', ['q' => 'L']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('q');
    }

    public function test_suggestions_treat_query_wildcards_as_literal_characters(): void
    {
        DB::table('bx_cities')->insert([
            ['city' => 'London', 'state' => 'England', 'country' => 5],
            ['city' => '%ondon', 'state' => 'England', 'country' => 5],
        ]);

        $this->getJson(route('uk-cities.suggest', ['q' => 'lo%']))
            ->assertOk()
            ->assertExactJson(['cities' => []]);
    }
}
