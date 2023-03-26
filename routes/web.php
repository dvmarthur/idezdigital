<?php

use App\Http\Controllers\MunicipiosController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//rota para municipios 
Route::get('/municipios/{estado}', [MunicipiosController::class, 'index'])->middleware('throttle:30,1');

//rota para pesquisar municipios
Route::get('/municipios/{estado}/{municipio}', [MunicipiosController::class, 'show'])->middleware('throttle:30,1');

