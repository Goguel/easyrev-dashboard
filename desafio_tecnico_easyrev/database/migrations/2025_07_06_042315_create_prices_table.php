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
    Schema::create('prices', function (Blueprint $table) {
        $table->id();
        // Esta linha cria a chave estrangeira que conecta um preço a um quarto
        $table->foreignId('room_id')->constrained()->onDelete('cascade');
        $table->decimal('price', 8, 2);
        $table->date('effective_date');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
