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
        Schema::create('quote_calculations', function (Blueprint $table) {
            $table->id();
            $table->decimal('gt', 10, 2)->default(0)->comment('Gündüz toplam enerji (Wh)');
            $table->decimal('gc', 10, 2)->default(0)->comment('Gece toplam enerji (Wh)');
            $table->decimal('total_power_w', 10, 2)->default(0)->comment('Toplam anlık güç (W)');
            $table->decimal('day_kwh', 10, 2)->default(0)->comment('Gündüz toplam (kWh)');
            $table->decimal('night_kwh', 10, 2)->default(0)->comment('Gece toplam (kWh)');
            $table->decimal('total_kwh', 10, 2)->default(0)->comment('Günlük toplam (kWh)');
            $table->integer('panel_count')->default(0)->comment('Gerekli panel sayısı');
            $table->integer('battery_count')->default(0)->comment('Gerekli akü sayısı');
            $table->integer('inverter_count')->default(0)->comment('Gerekli inverter sayısı');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_calculations');
    }
};
