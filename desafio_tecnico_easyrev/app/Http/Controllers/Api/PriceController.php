<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Price;
use Illuminate\Support\Facades\Validator;

class PriceController extends Controller
{
    /**
     * Armazena um novo preço para um quarto específico.
     */
    public function store(Request $request, $roomId)
    {
        // 1. Valida se o quarto existe
        $room = Room::find($roomId);
        if (!$room) {
            return response()->json(['message' => 'Quarto não encontrado.'], 404);
        }

        // 2. Valida os dados de entrada
        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric|min:0',
            'effective_date' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 3. Cria e salva o novo preço
        $price = new Price([
            'room_id' => $roomId,
            'price' => $request->input('price'),
            'effective_date' => $request->input('effective_date'),
        ]);

        $price->save();

        return response()->json($price, 201); // 201 Created
    }
}