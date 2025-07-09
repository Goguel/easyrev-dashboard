<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
    /**
     * Retorna todas as reservas para a data atual.
     */
    public function today(Request $request)
    {
        // Pega a data do parâmetro 'date' da URL.
        $date = $request->query('date', Carbon::today()->toDateString());

        // Busca as reservas onde a data de check-in é igual à data fornecida.
        $reservations = Reservation::whereDate('check_in_date', $date)->get();
        
        return response()->json($reservations);
    }

    /**
     * Cria uma nova reserva.
     */
    public function store(Request $request)
    {
        // Validação básica dos campos, conforme os diferenciais do PDF
        $validator = Validator::make($request->all(), [
            'guest_name' => 'required|string|max:255',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after_or_equal:check_in_date',
            'guest_count' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Cria a reserva com os dados validados
        $reservation = Reservation::create($request->all());

        return response()->json($reservation, 201); // 201: Created
    }

    /**
     * Atualiza o status de uma reserva existente.
     */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        // Validação para garantir que o status é um dos valores permitidos
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pendente,confirmada,cancelada',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Atualiza o status e salva no banco
        $reservation->status = $request->input('status');
        $reservation->save();

        return response()->json($reservation);
    }
}