<?php

    use App\Http\Controllers\Api\DeliveryFeeController;
    use Illuminate\Support\Facades\Route;

    Route::post('/calculate-delivery-fee', [DeliveryFeeController::class, 'calculate']);
