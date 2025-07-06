<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; 
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Desativa a verificação de chaves estrangeiras
        Schema::disableForeignKeyConstraints();

        // 2. Limpa a tabela
        Room::truncate();

        // 3. Reativa a verificação de chaves estrangeiras
        Schema::enableForeignKeyConstraints();

        // 4. Agora, cria os dados
        Room::create(['name' => 'Quarto Standard', 'type' => 'Standard']);
        Room::create(['name' => 'Quarto Deluxe', 'type' => 'Deluxe']);
        Room::create(['name' => 'Suíte Executiva', 'type' => 'Suite']);
    }
}