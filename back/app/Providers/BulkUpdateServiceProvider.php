<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        DB::macro('bulkUpdate', function ($query, $table_name, $records, $columns) {
            if (empty($records)) return;
            $ids = $query->whereIn("id", collect($records)->pluck('id'))
                ->pluck('id');

            $cases = [];
            $bindings = [];
            foreach ($columns as $column) {
                $caseStatements = [];
                foreach ($records as $record) {
                    $parameterName = ":{$column}_{$record['id']}";
                    $caseStatements[] = "WHEN id = {$record['id']} THEN {$parameterName}";
                    $bindings[$parameterName] = is_null($record[$column]) ? null : $record[$column];
                }
                $caseStatements[] = "ELSE {$column}";
                $cases[] = "{$column} = CASE " . implode(' ', $caseStatements) . " END";
            }

            $sql = "UPDATE {$table_name} SET " . implode(', ', $cases) . " WHERE id IN (" . $ids->implode(', ') . ")";
            return DB::statement($sql, $bindings);
        });
    }
}
