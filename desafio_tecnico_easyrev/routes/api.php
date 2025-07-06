<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\PriceController;
use App\Http\Controllers\Api\PredictionController;

// Rota para a lógica de previsão de preço.
Route::post('/predict', [PredictionController::class, 'predict']);

// Rota para buscar todos os quartos e seus históricos de preços.
Route::get('/rooms', [RoomController::class, 'index']);

// Rota para adicionar um novo preço a um quarto específico.
Route::post('/rooms/{roomId}/price', [PriceController::class, 'store']);