@extends('layouts.app')

@section('title', 'Reporte de Inventario')

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
                <h3 class="text-2xl font-bold text-slate-800">Reporte de Inventario</h3>
                <p class="text-slate-500">Valoración de inventario a la fecha</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Imprimir
            </button>
        </div>
    </div>

    <!-- Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm text-blue-800">Método de valoración: Promedio Ponderado. Fecha de corte: {{ now()->format('d/m/Y') }}</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Total de Productos</p>
            <p class="text-3xl font-bold text-slate-800">{{ $productos->count() }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Total Unidades</p>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($totalCantidad, 2) }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Valor Total Inventario</p>
            <p class="text-3xl font-bold text-emerald-600">${{ number_format($totalValor, 2) }}</p>
        </div>
    </div>

    <!-- Bajo Stock Alert -->
    @if($productosBajoStock->count() > 0)
    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <div class="flex items-center gap-3 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h4 class="text-lg font-semibold text-red-800">Productos Bajo Stock Mínimo</h4>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($productosBajoStock as $producto)
            <div class="bg-white rounded-lg p-3 border border-red-100">
                <p class="font-medium text-slate-800">{{ $producto->codigo }}</p>
                <p class="text-sm text-slate-600">{{ $producto->nombre }}</p>
                <div class="flex justify-between mt-2 text-sm">
                    <span class="text-red-600">Actual: {{ number_format($producto->existencias, 2) }}</span>
                    <span class="text-slate-500">Mín: {{ number_format($producto->stock_minimo, 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h4 class="text-lg font-semibold text-slate-800">Detalle de Inventario</h4>
        </div>
        
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Producto</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Existencias</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Costo Unit.</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Valor Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($productos as $producto)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm text-slate-800 font-mono">{{ $producto->codigo }}</td>
                    <td class="px-6 py-4 text-sm text-slate-800">
                        <div>{{ $producto->nombre }}</div>
                        <div class="text-xs text-slate-500">{{ $producto->categoria_producto ?? 'Sin categoría' }}</div>
                    </td>
                    <td class="px-6 py-4 text-right text-sm">
                        <span class="{{ $producto->estaBajoStock() ? 'text-red-600 font-medium' : 'text-slate-800' }}">
                            {{ number_format($producto->existencias, 2) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm text-slate-600">${{ number_format($producto->precio_costo, 2) }}</td>
                    <td class="px-6 py-4 text-right text-sm font-medium text-slate-800">
                        ${{ number_format($producto->existencias * $producto->precio_costo, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        No hay productos para mostrar
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($productos->count() > 0)
            <tfoot class="bg-slate-50 border-t border-slate-200">
                <tr>
                    <td colspan="2" class="px-6 py-4 text-sm font-semibold text-slate-800 text-right">TOTALES:</td>
                    <td class="px-6 py-4 text-right text-sm font-bold text-slate-800">{{ number_format($totalCantidad, 2) }}</td>
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4 text-right text-sm font-bold text-emerald-600">${{ number_format($totalValor, 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    <!-- Footer -->
    <div class="text-center text-sm text-slate-500">
        <p>Reporte generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</div>
@endsection