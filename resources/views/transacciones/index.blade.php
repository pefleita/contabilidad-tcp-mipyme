@extends('layouts.app')

@section('title', 'Transacciones')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-2xl font-bold text-slate-800">Transacciones</h3>
        <a href="{{ route('transacciones.create') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nueva
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <p class="text-sm text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    @if(isset($sinEmpresa) && $sinEmpresa)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center">
        <p class="text-amber-800 mb-4">Primero debes registrar los datos de tu empresa.</p>
        <a href="{{ route('empresa.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
            Configurar Empresa
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Total Ingresos</p>
            <p class="text-2xl font-bold text-emerald-600">${{ number_format($totalIngresos, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Total Gastos</p>
            <p class="text-2xl font-bold text-rose-600">${{ number_format($totalGastos, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Balance</p>
            <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-slate-800' : 'text-rose-600' }}">
                ${{ number_format($balance, 2) }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form method="GET" action="{{ route('transacciones.index') }}" class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div>
                    <select name="tipo" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        <option value="">Todos los tipos</option>
                        <option value="ingreso" {{ request('tipo') == 'ingreso' ? 'selected' : '' }}>Ingresos</option>
                        <option value="gasto" {{ request('tipo') == 'gasto' ? 'selected' : '' }}>Gastos</option>
                    </select>
                </div>
                <div>
                    <select name="categoria_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="metodo_pago" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        <option value="">Todos los métodos</option>
                        <option value="efectivo" {{ request('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        <option value="electronico" {{ request('metodo_pago') == 'electronico' ? 'selected' : '' }}>Electrónico</option>
                    </select>
                </div>
                <div>
                    <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" placeholder="Desde" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                </div>
                <div>
                    <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" placeholder="Hasta" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                </div>
            </div>
            <div class="flex gap-2">
                <input type="text" name="busqueda" value="{{ request('busqueda') }}" placeholder="Buscar..." class="flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm">
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm hover:bg-slate-800 transition-colors">Filtrar</button>
                <a href="{{ route('transacciones.index') }}" class="px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50 transition-colors">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        @if($transacciones->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-4 py-3 text-sm font-semibold text-slate-600">Fecha</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-slate-600">Tipo</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-slate-600">Categoría</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-slate-600">Descripción</th>
                        <th class="text-left px-4 py-3 text-sm font-semibold text-slate-600">Método</th>
                        <th class="text-right px-4 py-3 text-sm font-semibold text-slate-600">Monto</th>
                        <th class="text-center px-4 py-3 text-sm font-semibold text-slate-600">Estado</th>
                        <th class="text-right px-4 py-3 text-sm font-semibold text-slate-600">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transacciones as $transaccion)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $transaccion->fecha->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @if($transaccion->esIngreso())
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-medium">Ingreso</span>
                            @else
                            <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded text-xs font-medium">Gasto</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $transaccion->categoria->nombre }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500 max-w-[200px] truncate">{{ $transaccion->descripcion ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm capitalize text-slate-600">{{ $transaccion->metodo_pago }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-right {{ $transaccion->esIngreso() ? 'text-emerald-600' : 'text-rose-600' }}">
                            ${{ number_format($transaccion->monto, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($transaccion->estaConfirmado())
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs">Confirmado</span>
                            @elseif($transaccion->estaAnulado())
                            <span class="px-2 py-1 bg-slate-100 text-slate-500 rounded text-xs">Anulado</span>
                            @else
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs">Pendiente</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('transacciones.show', $transaccion) }}" class="p-1 text-slate-400 hover:text-slate-600" title="Ver detalle">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('transacciones.edit', $transaccion) }}" class="p-1 text-slate-400 hover:text-slate-600" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirmar-eliminar-transaccion-{{ $transaccion->id }}')" class="p-1 text-slate-400 hover:text-red-600" title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>

                                <x-modal name="confirmar-eliminar-transaccion-{{ $transaccion->id }}" maxWidth="md" focusable>
                                    <form method="POST" action="{{ route('transacciones.destroy', $transaccion) }}" class="p-6">
                                        @csrf
                                        @method('DELETE')
                                        <div class="flex items-center gap-3 mb-4">
                                            <span class="p-2 bg-red-100 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                </svg>
                                            </span>
                                            <h2 class="text-lg font-semibold text-slate-800">Eliminar transacción</h2>
                                        </div>
                                        <p class="text-sm text-slate-600 mb-4">
                                            ¿Estás seguro de eliminar esta transacción?
                                        </p>
                                        <div class="bg-slate-50 rounded-lg p-3 mb-6 text-sm space-y-1">
                                            <div class="flex justify-between">
                                                <span class="text-slate-500">Fecha:</span>
                                                <span class="text-slate-800 font-medium">{{ $transaccion->fecha->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-500">Tipo:</span>
                                                <span class="font-medium {{ $transaccion->esIngreso() ? 'text-emerald-600' : 'text-rose-600' }}">{{ $transaccion->esIngreso() ? 'Ingreso' : 'Gasto' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-500">Monto:</span>
                                                <span class="text-slate-800 font-medium">${{ number_format($transaccion->monto, 2) }}</span>
                                            </div>
                                            @if($transaccion->descripcion)
                                            <div class="flex justify-between">
                                                <span class="text-slate-500">Descripción:</span>
                                                <span class="text-slate-800 text-right max-w-[200px] truncate">{{ $transaccion->descripcion }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex justify-end gap-3">
                                            <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                                Eliminar
                                            </button>
                                        </div>
                                    </form>
                                </x-modal>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-100">
            {{ $transacciones->links() }}
        </div>
        @else
        <div class="text-center py-12 text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-lg font-medium mb-1">No hay transacciones</p>
            <p class="text-sm">Registra tu primera transacción para comenzar.</p>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
