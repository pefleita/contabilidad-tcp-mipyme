<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ActivoFijoController;
use App\Http\Controllers\ContabilidadController;
use App\Http\Controllers\TransaccionController;
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
    
    Route::prefix('contabilidad')->name('contabilidad.')->group(function () {
        Route::get('/', [ContabilidadController::class, 'index'])->name('index');
        Route::get('/create', [ContabilidadController::class, 'create'])->name('create');
        Route::post('/', [ContabilidadController::class, 'store'])->name('store');
        Route::get('/{asiento}', [ContabilidadController::class, 'show'])->name('show');
        Route::get('/{asiento}/edit', [ContabilidadController::class, 'edit'])->name('edit');
        Route::put('/{asiento}', [ContabilidadController::class, 'update'])->name('update');
        Route::delete('/{asiento}', [ContabilidadController::class, 'destroy'])->name('destroy');
        Route::get('/reportes/libro-diario', [ContabilidadController::class, 'libroDiario'])->name('libro-diario');
        Route::get('/reportes/libro-mayor', [ContabilidadController::class, 'libroMayor'])->name('libro-mayor');
        Route::get('/reportes/balance-comprobacion', [ContabilidadController::class, 'verificarBalance'])->name('balance-comprobacion');
        Route::get('/reportes/estado-situacion', [ContabilidadController::class, 'estadoSituacion'])->name('estado-situacion');
        Route::get('/reportes/estado-rendimiento', [ContabilidadController::class, 'estadoRendimiento'])->name('estado-rendimiento');
        Route::post('/generar-desde-transaccion/{transaccion}', [ContabilidadController::class, 'generarDesdeTransaccion'])->name('generar-desde-transaccion');
    });
    
    Route::resource('productos', ProductoController::class);
    
    Route::get('productos/movimiento', [ProductoController::class, 'movimiento'])->name('productos.movimiento');
    Route::post('productos/movimiento', [ProductoController::class, 'guardarMovimiento'])->name('productos.guardarMovimiento');
    Route::get('productos/reporte', [ProductoController::class, 'reporteInventario'])->name('productos.reporte');
    
    Route::get('empresa', [EmpresaController::class, 'index'])->name('empresa.index');
    Route::get('empresa/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
    Route::put('empresa', [EmpresaController::class, 'update'])->name('empresa.update');
    Route::post('empresa', [EmpresaController::class, 'store'])->name('empresa.store');
    
    Route::resource('categorias', CategoriaController::class);
    
    Route::resource('transacciones', TransaccionController::class)->parameters(['transacciones' => 'transaccion']);
    Route::post('transacciones/{transaccion}/comprobantes', [TransaccionController::class, 'uploadComprobante'])->name('transacciones.upload-comprobante');
    Route::delete('transacciones/{transaccion}/comprobantes/{comprobante}', [TransaccionController::class, 'deleteComprobante'])->name('transacciones.delete-comprobante');

    Route::get('activos/depreciacion', [ActivoFijoController::class, 'depreciacion'])->name('activos.depreciacion');
    Route::get('activos/libro', [ActivoFijoController::class, 'libroActivos'])->name('activos.libro');
    Route::resource('activos', ActivoFijoController::class)->parameters(['activos' => 'activo']);
});

require __DIR__.'/auth.php';
