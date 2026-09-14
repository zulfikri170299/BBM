<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterableByYear
{
    /**
     * Boot the year filter trait for a model.
     */
    protected static function bootFilterableByYear()
    {
        static::addGlobalScope('year_filter', function (Builder $builder) {
            // Check if we are in an HTTP request with session available
            if (function_exists('request') && request()->hasSession()) {
                $year = session('filter_tahun', date('Y'));
                
                // Determine the column to filter on (default: created_at)
                $dateField = 'created_at';
                $model = $builder->getModel();
                if (property_exists($model, 'yearFilterField')) {
                    $dateField = $model->yearFilterField;
                }

                $table = $model->getTable();

                if (in_array($dateField, ['tahun'])) {
                    $builder->where($table . '.' . $dateField, $year);
                } else {
                    $builder->whereYear($table . '.' . $dateField, $year);
                }
            }
        });
    }
}
