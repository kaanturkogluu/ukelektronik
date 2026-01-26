<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteDevice extends Model
{
    protected $fillable = [
        'quote_calculation_id',
        'name',
        'watt',
        'qty',
        'power_w',
        'day_hours',
        'night_hours',
        'day_wh',
        'night_wh',
        'total_wh',
        'day_kwh',
        'night_kwh',
        'total_kwh',
    ];

    protected $casts = [
        'watt' => 'decimal:2',
        'qty' => 'integer',
        'power_w' => 'decimal:2',
        'day_hours' => 'decimal:2',
        'night_hours' => 'decimal:2',
        'day_wh' => 'decimal:2',
        'night_wh' => 'decimal:2',
        'total_wh' => 'decimal:2',
        'day_kwh' => 'decimal:2',
        'night_kwh' => 'decimal:2',
        'total_kwh' => 'decimal:2',
    ];

    public function calculation(): BelongsTo
    {
        return $this->belongsTo(QuoteCalculation::class);
    }
}
