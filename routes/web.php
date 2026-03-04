<?php

use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — VIP2CARS
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('vehiculos.index'));

Route::resource('vehiculos', VehiculoController::class);
