<?php

namespace Tests\Feature;

use App\Support\SchemaCache;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use Tests\TestCase;

class SchemaCacheTest extends TestCase
{
    public function test_answers_match_the_schema_builder(): void
    {
        Schema::create('widgets', function (Blueprint $table): void {
            $table->id();
            $table->string('Title');
            $table->integer('stock');
        });

        $this->assertTrue(SchemaCache::hasTable('widgets'));
        $this->assertFalse(SchemaCache::hasTable('missing_table'));
        $this->assertTrue(SchemaCache::hasColumn('widgets', 'title'));
        $this->assertTrue(SchemaCache::hasColumns('widgets', ['id', 'TITLE', 'stock']));
        $this->assertFalse(SchemaCache::hasColumns('widgets', ['id', 'colour']));
        $this->assertSame(['id', 'Title', 'stock'], SchemaCache::getColumnListing('widgets'));
        $this->assertSame([], SchemaCache::getColumnListing('missing_table'));
        $this->assertSame(Schema::getColumnType('widgets', 'stock'), SchemaCache::getColumnType('widgets', 'stock'));

        $this->expectException(InvalidArgumentException::class);
        SchemaCache::getColumnType('widgets', 'colour');
    }

    public function test_lookups_are_memoised_within_a_request(): void
    {
        Schema::create('widgets', fn (Blueprint $table) => $table->id());

        SchemaCache::hasTable('widgets');
        SchemaCache::hasTable('missing_table');

        $queries = 0;
        DB::listen(function () use (&$queries): void {
            $queries++;
        });

        for ($i = 0; $i < 5; $i++) {
            SchemaCache::hasTable('widgets');
            SchemaCache::hasColumn('widgets', 'id');
            SchemaCache::hasTable('missing_table');
        }

        $this->assertSame(0, $queries);
    }

    public function test_running_migrations_invalidates_the_cache(): void
    {
        $this->assertFalse(SchemaCache::hasTable('widgets'));

        Schema::create('widgets', fn (Blueprint $table) => $table->id());
        $this->assertFalse(SchemaCache::hasTable('widgets'), 'Stale until migrations end.');

        event(new MigrationsEnded('up'));

        $this->assertTrue(SchemaCache::hasTable('widgets'));
    }
}
