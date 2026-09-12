<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait AppliesDateRangeFilter
{
    /**
     * Restrict a query to rows created within an optional [start, end] date range.
     * Dates are compared on their calendar day (whereDate), so the end date is inclusive.
     */
    protected function applyDateRangeFilter(Builder $query, ?string $start, ?string $end, string $column = 'created_at'): Builder
    {
        $query
            ->when($start !== null && $start !== '', fn (Builder $q) => $q->whereDate($column, '>=', $start))
            ->when($end !== null && $end !== '', fn (Builder $q) => $q->whereDate($column, '<=', $end));

        return $query;
    }
}
