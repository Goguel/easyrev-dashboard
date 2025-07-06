<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PredictionController extends Controller
{
    /**
     * Prevê o preço da diária para o próximo dia com base nos dados fornecidos. 
     */
    public function predict(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'base_price' => 'required|numeric|min:0',
            'occupancy' => 'required|numeric|min:0|max:100',
            'room_type' => 'required|string|in:Standard,Deluxe,Suite',
            'is_holiday_or_weekend' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $predictedPrice = $request->input('base_price');

       
        if ($request->input('occupancy') > 80) {
            $predictedPrice *= 1.10; 
        } else {
            $predictedPrice *= 0.95; 
        }

        
        switch ($request->input('room_type')) {
            case 'Suite':
                $predictedPrice *= 1.15; 
                break;
            case 'Deluxe':
                $predictedPrice *= 1.10; 
                break;
           
        }

        if ($request->input('is_holiday_or_weekend')) {
            $predictedPrice *= 1.20;
        }

        
        return response()->json([
            'predicted_price' => round($predictedPrice, 2)
        ]);
    }
}