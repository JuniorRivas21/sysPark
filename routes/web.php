<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GraficosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::view('tipos','tipos');
Route::view('usuarios','usuarios');
Route::view('incidencias','incidencias');

// Rutas para el controlador de registros
Route::view('entradas', 'entradas');
Route::view('salidas', 'salidas');

// Rutas para el controlador de gráficos
Route::view('graficos', 'graficos');
Route::get('/obtener-datos', [GraficosController::class, 'obtenerDatos']);


