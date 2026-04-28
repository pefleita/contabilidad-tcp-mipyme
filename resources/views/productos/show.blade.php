@extends('layouts.app')

@section('title', $producto->nombre)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('productos.index') }}" class="p-2 text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $producto->nombre }}</h3>
                <p class="text-slate-500">Código: {{ $producto->codigo }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('productos.edit', $producto) }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Existencias</p>
            <p class="text-2xl font-bold {{ $producto->estaBajoStock() ? 'text-red-600' : 'text-slate-800' }}">
                {{ number_format($producto->existencias, 2) }}
                <span class="text-sm font-normal text-slate-500">{{ $producto->unidad_medida }}</span>
            </p>
            @if($producto->estaBajoStock())
            <p class="text-xs text-red-500 mt-1">⚠️ Bajo stock mínimo ({{ $producto->stock_minimo }})</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Precio Costo</p>
            <p class="text-2xl font-bold text-slate-800">${{ number_format($producto->precio_costo, 2) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Precio Venta</p>
            <p class="text-2xl font-bold text-slate-800">${{ number_format($producto->precio_venta, 2) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Margen</p>
            <p class="text-2xl font-bold {{ $producto->margen() > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                {{ number_format($producto->margen(), 1) }}%
            </p>
        </div>
    </div>

    <!-- Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">Información</h4>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">Categoría</dt>
                    <dd class="text-sm text-slate-800">{{ $producto->categoria_producto ?? 'Sin categoría' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">Unidad de Medida</dt>
                    <dd class="text-sm text-slate-800">{{ $producto->unidad_medida }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">Tipo</dt>
                    <dd class="text-sm">
                        @if($producto->es_servicio)
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs">Servicio</span>
                        @else
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">Producto</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">Estado</dt>
                    <dd class="text-sm">
                        @if($producto->esta_activo)
                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs">Activo</span>
                        @else
                        <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs">Inactivo</span>
                        @endif
                    </dd>
                </div>
                @if($producto->descripcion)
                <div class="pt-3 border-t border-slate-100">
                    <dt class="text-sm text-slate-500 mb-1">Descripción</dt>
                    <dd class="text-sm text-slate-700">{{ $producto->descripcion }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-slate-800">Historial Kardex</h4>
                <span class="text-sm text-slate-500">{{ $kardex->count() }} registros</span>
            </div>
            
            @if($kardex->count() > 0)
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($kardex as $movimiento)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        @if($movimiento->esEntrada())
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        @elseif($movimiento->esSalida())
                        <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
                        @else
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-slate-800 capitalize">{{ $movimiento->tipo_movimiento }}</p>
                            <p class="text-xs text-slate-500">{{ $movimiento->fecha->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium {{ $movimiento->esEntrada() ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $movimiento->esEntrada() ? '+' : '-' }}{{ number_format($movimiento->cantidad, 2) }}
                        </p>
                        <p class="text-xs text-slate-500">${{ number_format($movimiento->precio_unitario, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-sm">No hay movimientos registrados</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection