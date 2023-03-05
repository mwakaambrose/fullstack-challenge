<?php

use App\Models\Weather;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weather', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'user_id')
                ->constrained();
            $table->enum(column: 'type', allowed: Weather::TYPES);
            $table->json(column: 'weather');
            $table->json(column: 'main');
            $table->json(column: 'wind');
            $table->json(column: 'rain');
            $table->json(column: 'clouds');
            $table->text(column: 'datetime_txt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather');
    }
};
