<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Stock extends Model
{
    use HasFactory;

    protected $casts = [
        'production_date'  => 'date:d/m/Y',
    ];

    protected $fillable = ['product_code', 'on_hand', 'taken', 'production_date'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'code', 'product_code');
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
