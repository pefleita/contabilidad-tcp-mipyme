<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/admin', function () {
        return view('dashboard', ['seccion' => 'Admin']);
    })->middleware('role:admin')->name('admin');
    
    Route::get('/contabilidad', function () {
        return view('dashboard', ['seccion' => 'Contabilidad']);
    })->middleware('role:contador')->name('contabilidad');
    
    Route::resource('productos', ProductoController::class);
    
    Route::get('productos/movimiento', [ProductoController::class, 'movimiento'])->name('productos.movimiento');
    Route::post('productos/movimiento', [ProductoController::class, 'guardarMovimiento'])->name('productos.guardarMovimiento');
    Route::get('productos/reporte', [ProductoController::class, 'reporteInventario'])->name('productos.reporte');
});

require __DIR__.'/auth.php';
