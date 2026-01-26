<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuoteCalculation extends Model
{
    protected $fillable = [
        'gt',
        'gc',
        'total_power_w',
        'day_kwh',
        'night_kwh',
        'total_kwh',
        'panel_count',
        'battery_count',
        'inverter_count',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'gt' => 'decimal:2',
        'gc' => 'decimal:2',
        'total_power_w' => 'decimal:2',
        'day_kwh' => 'decimal:2',
        'night_kwh' => 'decimal:2',
        'total_kwh' => 'decimal:2',
        'panel_count' => 'integer',
        'battery_count' => 'integer',
        'inverter_count' => 'integer',
    ];

    public function devices(): HasMany
    {
        return $this->hasMany(QuoteDevice::class);
    }
}
