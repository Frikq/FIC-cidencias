<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BDController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Rutas de recursos para archivos CSS y JavaScript
//Route::any('/mi-ruta', 'BDController@iniciar');

Route::post('/', [BDController::class, 'iniciar'])->name('iniciar');
Route::post('login', [BDController::class, 'iniciar'])->name('iniciar');
Route::post('registro', [BDController::class, 'registrar'])->name('registrar');
Route::post('cerrar', [BDController::class, 'cerrarSesion'])->name('cerrar');
Route::post('reporte', function(){return view('reporte');})->name('reportes');
Route::post('reportar', [BDController::class, 'reportar'])->name('reportar');
Route::post('seleccionar', [BDController::class, 'seleccionar'])->name('seleccionar');
Route::post('eliminar', [BDController::class, 'eliminar'])->name('eliminar');
//Route::post('roles', [BDController::class, 'roles'])->name('roles');
Route::post('roles', [BDController::class, 'buscar'])->name('buscar');
Route::post('asignar', [BDController::class, 'asignar'])->name('asignar');


//Route::view('menu', 'menu')->middleware('auth')->name('menu');
Route::view('menu', 'menu')->name('menu');
Route::view('login', 'index')->name('login');
Route::view('registro', 'registro')->name('registro');
Route::view('cerrar', 'index')->name('cerrar');
Route::view('reporte', 'reporte')->name('reporte');
Route::view('seleccionar', 'reporte')->name('seleccionar');
Route::view('roles', 'roles')->name('roles');
Route::view('asignar', 'roles')->name('asignar');
//Route::view('reportar', 'menu')->name('reportar');

Route::get('/', [BDController::class, 'comprobarInicio']);
//Route::get('menu', [BDController::class, 'Reportes'])->name('menu');
Route::get('login', [BDController::class, 'comprobarInicio'])->name('login');
Route::get('register', function(){return view('registro');})->name('register');
Route::get('/cerrar', function(){return view('index');})->name('cerrar');
Route::get('/roles', [BDController::class, 'comprobarAdmin'])->name('roles');
Route::get('volver', function(){return view('menu');})->name('volver');
Route::get('reporte', [BDController::class, 'reporte'])->name('reporte');
Route::get('menu', [BDController::class, 'reportes'])->name('menu');
Route::get('seleccionar', [BDController::class, 'seleccionar'])->name('seleccionar');
Route::get('reporte/{incidencia}', [BDController::class, 'seleccionar']);



Route::post('iniciar-sesion', [AuthController::class, 'login'])->name('iniciar-sesion');

Route::post('cerrar-sesion', [AuthController::class, 'logout'])->name('cerrar-sesion');
