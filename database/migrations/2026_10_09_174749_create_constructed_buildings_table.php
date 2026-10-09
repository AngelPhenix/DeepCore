<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('constructed_buildings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planet_id')->constrained()->onDelete('cascade');
            $table->string('building_slug'); // Référence au slug du bâtiment
            $table->unsignedInteger('level')->default(1);
            
            // Les stats uniques générées au craft (le Roll)
            $table->string('rarity')->default('common'); // common, rare, epic, legendary
            $table->decimal('efficiency_multiplier', 5, 2)->default(1.00); // ex: 1.15 (+15%)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('constructed_buildings');
    }
};