<?php

use App\Http\Controllers\Controller;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Retorna todos os quartos com seus respectivos históricos de preços.
     */
    public function index()
    {
        // Usa 'with('prices')' para carregar os preços junto com os quartos
        $rooms = Room::with('prices')->get();
        return response()->json($rooms);
    }
}