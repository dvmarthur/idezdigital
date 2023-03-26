<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//rota para municipios 
Route::get('/municipios/{estado}', [MunicipiosController::class, 'index'])->middleware('throttle:30,1');

//rota para pesquisar municipios
Route::get('/municipios/{estado}/{municipio}', [MunicipiosController::class, 'show'])->middleware('throttle:30,1');
