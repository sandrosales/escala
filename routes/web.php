<?php

use App\Http\Controllers\PlantaoController;
use Illuminate\Support\Facades\Route;


Route::get('plantoes/mes', [PlantaoController::class, 'listByMonth'])->name('plantoes.mes');
Route::get('plantoes/create', [PlantaoController::class, 'create'])->name('plantoes.create');
Route::post('plantoes/store', [PlantaoController::class, 'store'])->name('plantoes.store');
// Route::get('plantoes/mes', 'PlantaoController@listByMonth')->name('plantoes.mes');
// Route::controller(PlantaoController::class)

Route::get('portal/admin/dashboard', function () {
    return view('portal.app');
});

Route::get('/', function () {
    return view('welcome');
});
