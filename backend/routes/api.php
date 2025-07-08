<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReservationController; 

// Retorna todas as reservas para a data atual. 
Route::get('/reservations/today', [ReservationController::class, 'today']);

// Cria uma nova reserva. 
Route::post('/reservations', [ReservationController::class, 'store']);

// Atualiza status da reserva.
Route::patch('/reservations/{reservation}', [ReservationController::class, 'updateStatus']);