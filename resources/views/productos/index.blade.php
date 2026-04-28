@extends('layouts.app')

@section('title', 'Inventario - Productos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Gestión de Inventario</h3>
            <p class="text-slate-500">Administra tus productos y servicios</p>
        </div>
        <div class="flex items-center gap-3">
            @if(!isset($sinEmpresa) || !$sinEmpresa)
            <a href="{{ route('productos.reporte') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Reporte
            </a>
            <a href="{{ route('productos.movimiento') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                Movimiento
            </a>
            <a href="{{ route('productos.create') }}" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nuevo Producto
            </a>
            @endif
        </div>
    </div>

    @if(isset($sinEmpresa) && $sinEmpresa)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-8 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-amber-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <h4 class="text-lg font-semibold text-slate-800 mb-2">Necesitas registrar tu empresa</h4>
        <p class="text-slate-600 mb-4">Para gestionar productos y servicios, primero debes registrar los datos de tu empresa.</p>
        <a href="{{ route('empresa.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Registrar Empresa
        </a>
    </div>
    @else

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Productos</p>
                    <p class="text-xl font-bold text-slate-800">{{ $productos->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Valor Inventario</p>
                    <p class="text-xl font-bold text-slate-800">$0.00</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Bajo Stock</p>
                    <p class="text-xl font-bold text-red-600">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-1">Buscar</label>
                <input type="text" name="busqueda" value="{{ request('busqueda') }}" placeholder="Código o nombre..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div class="w-48">
                <label class="block text-sm font-medium text-slate-700 mb-1">Categoría</label>
                <select name="categoria" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todas</option>
                    <option value="electronica" {{ request('categoria') == 'electronica' ? 'selected' : '' }}>Electrónica</option>
                    <option value="oficina" {{ request('categoria') == 'oficina' ? 'selected' : '' }}>Oficina</option>
                    <option value="insumos" {{ request('categoria') == 'insumos' ? 'selected' : '' }}>Insumos</option>
                </select>
            </div>
            <div class="w-40">
                <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                <select name="estado" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todos</option>
                    <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">
                Filtrar
            </button>
            <a href="{{ route('productos.index') }}" class="px-4 py-2 text-slate-600 hover:text-slate-800 inline-flex items-center">
                Limpiar
            </a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Categoría</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Existencias</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Costo</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Precio Venta</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Margen</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($productos as $producto)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm text-slate-800 font-mono">{{ $producto->codigo }}</td>
                    <td class="px-6 py-4 text-sm text-slate-800">
                        <div class="flex items-center gap-2">
                            <span>{{ $producto->nombre }}</span>
                            @if($producto->es_servicio)
                            <span class="px-2 py-0.5 text-xs bg-purple-100 text-purple-700 rounded">Servicio</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $producto->categoria_producto ?? '-' }}</td>
                    <td class="px-6 py-4 text-right">
                        <span class="{{ $producto->estaBajoStock() ? 'text-red-600 font-medium' : 'text-slate-800' }}">
                            {{ number_format($producto->existencias, 2) }} {{ $producto->unidad_medida }}
                        </span>
                        @if($producto->estaBajoStock())
                        <span class="ml-1 text-xs text-red-500">⚠️</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right text-sm text-slate-600">${{ number_format($producto->precio_costo, 2) }}</td>
                    <td class="px-6 py-4 text-right text-sm text-slate-800 font-medium">${{ number_format($producto->precio_venta, 2) }}</td>
                    <td class="px-6 py-4 text-right text-sm">
                        <span class="{{ $producto->margen() > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ number_format($producto->margen(), 1) }}%
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('productos.show', $producto) }}" class="p-1 text-slate-400 hover:text-slate-600" title="Ver">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('productos.edit', $producto) }}" class="p-1 text-slate-400 hover:text-slate-600" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="text-lg font-medium">No hay productos registrados</p>
                            <p class="text-sm">Comienza agregando tu primer producto</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($productos->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $productos->links() }}
        </div>
        @endif
    </div>
    @endif
</div>
@endsection