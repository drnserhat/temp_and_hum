<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/measurements', [ApiController::class, 'storeMeasurement']);
Route::get('/measurements/latest', [ApiController::class, 'getLatestMeasurement']); 