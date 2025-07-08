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
         Schema::create('reservations', function (Blueprint $table) {
        $table->id(); // ID [cite: 88]
        $table->string('guest_name'); // Nome do hóspede 
        $table->date('check_in_date'); // Data de entrada 
        $table->date('check_out_date'); // Data de saída 
        $table->integer('guest_count'); // Quantidade de hóspedes 
        $table->enum('status', ['pendente', 'confirmada', 'cancelada'])->default('pendente'); // Status, com 'pendente' como padrão 
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
