<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quote_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_calculation_id')->constrained('quote_calculations')->onDelete('cascade');
            $table->string('name')->comment('Cihaz adı');
            $table->decimal('watt', 8, 2)->comment('Birim güç (W)');
            $table->integer('qty')->default(1)->comment('Adet');
            $table->decimal('power_w', 10, 2)->comment('Toplam güç (W)');
            $table->decimal('day_hours', 5, 2)->default(0)->comment('Gündüz çalışma saati');
            $table->decimal('night_hours', 5, 2)->default(0)->comment('Gece çalışma saati');
            $table->decimal('day_wh', 10, 2)->default(0)->comment('Gündüz enerji (Wh)');
            $table->decimal('night_wh', 10, 2)->default(0)->comment('Gece enerji (Wh)');
            $table->decimal('total_wh', 10, 2)->default(0)->comment('Toplam enerji (Wh)');
            $table->decimal('day_kwh', 10, 2)->default(0)->comment('Gündüz enerji (kWh)');
            $table->decimal('night_kwh', 10, 2)->default(0)->comment('Gece enerji (kWh)');
            $table->decimal('total_kwh', 10, 2)->default(0)->comment('Toplam enerji (kWh)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_devices');
    }
};
