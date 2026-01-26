<?php

namespace App\Http\Controllers;

use App\Models\QuoteCalculation;
use App\Models\QuoteDevice;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gt' => 'required|numeric|min:0',
            'gc' => 'required|numeric|min:0',
            'total_power_w' => 'required|numeric|min:0',
            'day_kwh' => 'required|numeric|min:0',
            'night_kwh' => 'required|numeric|min:0',
            'total_kwh' => 'required|numeric|min:0',
            'panel_count' => 'required|integer|min:0',
            'battery_count' => 'required|integer|min:0',
            'inverter_count' => 'required|integer|min:0',
            'devices' => 'required|array',
            'devices.*.name' => 'required|string|max:255',
            'devices.*.watt' => 'required|numeric|min:0',
            'devices.*.qty' => 'required|integer|min:1',
            'devices.*.power_w' => 'required|numeric|min:0',
            'devices.*.day_hours' => 'required|numeric|min:0|max:24',
            'devices.*.night_hours' => 'required|numeric|min:0|max:24',
            'devices.*.day_wh' => 'required|numeric|min:0',
            'devices.*.night_wh' => 'required|numeric|min:0',
            'devices.*.total_wh' => 'required|numeric|min:0',
            'devices.*.day_kwh' => 'required|numeric|min:0',
            'devices.*.night_kwh' => 'required|numeric|min:0',
            'devices.*.total_kwh' => 'required|numeric|min:0',
        ]);

        $calculation = QuoteCalculation::create([
            'gt' => $validated['gt'],
            'gc' => $validated['gc'],
            'total_power_w' => $validated['total_power_w'],
            'day_kwh' => $validated['day_kwh'],
            'night_kwh' => $validated['night_kwh'],
            'total_kwh' => $validated['total_kwh'],
            'panel_count' => $validated['panel_count'],
            'battery_count' => $validated['battery_count'],
            'inverter_count' => $validated['inverter_count'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        foreach ($validated['devices'] as $deviceData) {
            QuoteDevice::create([
                'quote_calculation_id' => $calculation->id,
                'name' => $deviceData['name'],
                'watt' => $deviceData['watt'],
                'qty' => $deviceData['qty'],
                'power_w' => $deviceData['power_w'],
                'day_hours' => $deviceData['day_hours'],
                'night_hours' => $deviceData['night_hours'],
                'day_wh' => $deviceData['day_wh'],
                'night_wh' => $deviceData['night_wh'],
                'total_wh' => $deviceData['total_wh'],
                'day_kwh' => $deviceData['day_kwh'],
                'night_kwh' => $deviceData['night_kwh'],
                'total_kwh' => $deviceData['total_kwh'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Hesaplama başarıyla kaydedildi.',
            'calculation_id' => $calculation->id,
        ]);
    }
}
