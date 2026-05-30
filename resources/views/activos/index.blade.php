@extends('layouts.app')

@section('title', 'Activos Fijos')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Activos Fijos</h3>
            <p class="text-slate-500">Gestión de activos y depreciación</p>
        </div>
        @if(!isset($sinEmpresa) || !$sinEmpresa)
        <div class="flex items-center gap-3">
            <a href="{{ route('activos.libro') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Libro
            </a>
            <a href="{{ route('activos.depreciacion') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                Depreciación
            </a>
            <a href="{{ route('activos.create') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nuevo Activo
            </a>
        </div>
        @endif
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    @if(isset($sinEmpresa) && $sinEmpresa)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
        <p class="text-amber-800 font-medium">No hay datos de empresa</p>
        <p class="text-amber-600 text-sm mt-1">Registre los datos de su empresa para comenzar.</p>
        <a href="{{ route('empresa.index') }}" class="inline-flex items-center mt-3 px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors">Configurar Empresa</a>
    </div>
    @else
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase">Código</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase">Nombre</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase">Tipo</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Costo</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Dep. Acumulada</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Valor Neto</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-slate-500 uppercase">Estado</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-slate-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($activos as $activo)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-mono text-slate-600">{{ $activo->codigo }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('activos.show', $activo) }}" class="text-sm font-medium text-slate-800 hover:text-slate-600">{{ $activo->nombre }}</a>
                        </td>
                        <td class="px-6 py-4 text-sm capitalize text-slate-600">{{ $activo->tipo }}</td>
                        <td class="px-6 py-4 text-sm text-right font-mono text-slate-800">${{ number_format($activo->costo_original, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right font-mono text-slate-600">${{ number_format($activo->calcularDepreciacionAcumulada(), 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right font-mono font-medium text-slate-800">${{ number_format($activo->valorNeto(), 2) }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $activo->esta_activo ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $activo->esta_activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('activos.edit', $activo) }}" class="text-slate-400 hover:text-slate-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('activos.destroy', $activo) }}" onsubmit="return confirm('¿Eliminar este activo?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                            No hay activos fijos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($activos->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $activos->links() }}
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
