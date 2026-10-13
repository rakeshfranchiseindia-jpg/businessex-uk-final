<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

/**
 * Cached drop-in for the Schema::hasTable/hasColumn/... checks the
 * controllers use to tolerate optional legacy tables and columns.
 *
 * Every Schema::has* call is an information_schema query on MySQL; pages
 * were spending about half their queries on them. Column metadata is
 * stored under a single cache key (one cheap lookup per request at most)
 * and memoised for the rest of the request. The cache is flushed whenever
 * migrations run (see AppServiceProvider), so schema changes are picked up
 * immediately.
 */
class SchemaCache
{
    private const CACHE_KEY = 'schema-cache:v1';

    private const TTL_SECONDS = 86400;

    /** @var array<string, array<string, array{name: string, type: string}>|null>|null table => [lowercased column => meta], null when missing */
    private ?array $tables = null;

    public static function hasTable(string $table): bool
    {
        return self::instance()->columnTypes($table) !== null;
    }

    public static function hasColumn(string $table, string $column): bool
    {
        $columns = self::instance()->columnTypes($table);

        return $columns !== null && array_key_exists(strtolower($column), $columns);
    }

    /**
     * @param  array<int, string>  $columns
     */
    public static function hasColumns(string $table, array $columns): bool
    {
        foreach ($columns as $column) {
            if (! self::hasColumn($table, $column)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<int, string>
     */
    public static function getColumnListing(string $table): array
    {
        return array_column(self::instance()->columnTypes($table) ?? [], 'name');
    }

    public static function getColumnType(string $table, string $column): string
    {
        $type = self::instance()->columnTypes($table)[strtolower($column)]['type'] ?? null;

        if ($type === null) {
            throw new InvalidArgumentException("There is no column with name '{$column}' on table '{$table}'.");
        }

        return $type;
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);

        if (app()->resolved(static::class)) {
            app(static::class)->tables = null;
        }
    }

    private static function instance(): self
    {
        return app(static::class);
    }

    /**
     * @return array<string, array{name: string, type: string}>|null
     */
    private function columnTypes(string $table): ?array
    {
        $this->tables ??= Cache::get(self::CACHE_KEY, []);

        if (! array_key_exists($table, $this->tables)) {
            $columns = Schema::getColumns($table);

            // A table always has at least one column, so an empty result
            // means the table does not exist (yet) on this database.
            $this->tables[$table] = $columns === []
                ? null
                : collect($columns)->mapWithKeys(fn (array $column): array => [
                    strtolower($column['name']) => ['name' => $column['name'], 'type' => (string) $column['type_name']],
                ])->all();

            Cache::put(self::CACHE_KEY, $this->tables, self::TTL_SECONDS);
        }

        return $this->tables[$table];
    }
}
