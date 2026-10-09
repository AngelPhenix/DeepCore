<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ex: "Mine de Fer"
            $table->string('slug')->unique(); // ex: "iron_mine"
            $table->text('description')->nullable();
            
            // Coût de base et facteur d'augmentation par niveau
            $table->decimal('base_cost_iron', 12, 2)->default(50.00);
            $table->decimal('cost_multiplier', 5, 2)->default(1.50);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};