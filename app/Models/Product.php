<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $fillable = ['code', 'name', 'description'];

    /**
     * Set the relationship for `code` column
     *
     * @return Query
     */
    public function stock()
    {
        return $this->hasMany(Stock::class, 'product_code', 'code');
    }

    /**
     * Include stock_sum_on_hand field in query, and coerce null values to 0
     * TODO: coerce further to make this string result in to an actual number
     * @return Query
     */
    public function scopeWithStockSum($query)
    {
        // return $query->withSum('stock', 'on_hand');
        return $query->withSum([
            'stock' => fn ($q) => $q->select(DB::raw('COALESCE(SUM(on_hand), 0)')),
        ], 'on_hand');
    }

    /**
     * Include products only for which there is available stock
     * Filter out zero stock on_hand
     *
     * @return Query
     */
    public function scopeHasAvailableStock($query)
    {
        return $query->has('stock', '>', 0 );
    }

    /**
     * Order by sum of stock
     *
     * @return Query
     */
    public function scopeOrderByStock($query, $direction)
    {
        if (in_array( strtoupper($direction), ['ASC', 'DESC'] )) {
            $query->orderBy('stock_sum_on_hand', $direction);
        }
        return $query;
    }

    /**
     * Set the options for activity logging
     *
     * @return Spatie\Activitylog\LogOptions;
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

}
