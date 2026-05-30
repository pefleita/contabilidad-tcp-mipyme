@extends('layouts.app')

@section('title', 'Contabilidad - Asientos')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Asientos Contables</h3>
            <p class="text-slate-500">Registro de partida doble</p>
        </div>
        @if(isset($esFormal) && $esFormal)
        <a href="{{ route('contabilidad.create') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            + Nuevo Asiento
        </a>
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

    @if(session('info'))
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm text-blue-800">{{ session('info') }}</p>
    </div>
    @endif

    @if(!isset($esFormal) || !$esFormal)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
        <p class="text-amber-800 font-medium">Contabilidad formal no habilitada</p>
        <p class="text-amber-600 text-sm mt-1">Configure su empresa con tipo de contabilidad "formal" para acceder a esta sección.</p>
    </div>
    @else
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase">N° Asiento</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase">Fecha</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase">Descripción</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Debe</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Haber</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-slate-500 uppercase">Estado</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-slate-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($asientos as $asiento)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $asiento->numero_asiento }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $asiento->fecha->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate">{{ $asiento->descripcion }}</td>
                        <td class="px-6 py-4 text-sm text-right text-slate-800 font-mono">{{ number_format($asiento->totalDebe(), 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right text-slate-800 font-mono">{{ number_format($asiento->totalHaber(), 2) }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                @if($asiento->estado === 'confirmado') bg-emerald-100 text-emerald-700
                                @elseif($asiento->estado === 'borrador') bg-amber-100 text-amber-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($asiento->estado) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('contabilidad.show', $asiento) }}" class="text-slate-500 hover:text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            No hay asientos contables registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($asientos->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $asientos->links() }}
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
