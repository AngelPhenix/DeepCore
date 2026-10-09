<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->default('Ma Colonie');
            $table->string('type')->default('rocheuse'); // rocheuse, volcanique, cryogenique, etc.
            
            // Ressources avec précision décimale
            $table->decimal('iron', 15, 2)->default(500.00);
            $table->decimal('energy', 10, 2)->default(100.00);
            
            // Profondeur actuelle de la foreuse (en mètres ou kilomètres)
            $table->decimal('depth', 10, 2)->default(0.00);
            
            // Crucial pour le calcul du temps asynchrone
            $table->timestamp('last_updated_at')->useCurrent();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planets');
    }
};
