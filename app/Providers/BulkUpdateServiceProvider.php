<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class BulkUpdateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        DB::macro('bulkUpdate', function ($query, $table_name, $records, $columns, $primary_key = "id") {
            if (empty($records)) return;
            $ids = $query->whereIn($primary_key, collect($records)->pluck($primary_key))
                ->pluck($primary_key);

            $cases = [];
            $bindings = [];
            foreach ($columns as $column) {
                $caseStatements = [];
                foreach ($records as $record) {
                    $parameterName = ":{$column}_{$record[$primary_key]}";
                    $caseStatements[] = "WHEN {$primary_key} = {$record[$primary_key]} THEN {$parameterName}";
                    $bindings[$parameterName] = is_null($record[$column]) ? null : $record[$column];
                }
                $caseStatements[] = "ELSE {$column}";
                $cases[] = "{$column} = CASE " . implode(' ', $caseStatements) . " END";
            }

            $sql = "UPDATE {$table_name} SET " . implode(', ', $cases) . " WHERE {$primary_key} IN ({$ids->implode(', ')})";
            return DB::statement($sql, $bindings);
        });
    }
}
