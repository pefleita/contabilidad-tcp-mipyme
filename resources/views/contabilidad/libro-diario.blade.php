@extends('layouts.app')

@section('title', 'Libro Diario')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Libro Diario</h3>
            <p class="text-slate-500">Registro cronológico de asientos contables</p>
        </div>
    </div>

    <form method="GET" class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="grid grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Fecha Desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">N° Asiento</label>
                <input type="text" name="numero_asiento" value="{{ request('numero_asiento') }}"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" placeholder="Buscar...">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-slate-900 rounded-lg hover:bg-slate-800 transition-colors">Filtrar</button>
                <a href="{{ route('contabilidad.libro-diario') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">Limpiar</a>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-4 py-3 text-xs font-medium text-slate-500 uppercase">Fecha</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-slate-500 uppercase">N° Asiento</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-slate-500 uppercase">Descripción</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-slate-500 uppercase">Cuenta</th>
                        <th class="text-right px-4 py-3 text-xs font-medium text-slate-500 uppercase">Debe</th>
                        <th class="text-right px-4 py-3 text-xs font-medium text-slate-500 uppercase">Haber</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($asientos as $asiento)
                        @foreach($asiento->partidas as $partida)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2.5 text-sm text-slate-600">{{ $asiento->fecha->format('d/m/Y') }}</td>
                            <td class="px-4 py-2.5 text-sm font-medium text-slate-800">{{ $asiento->numero_asiento }}</td>
                            <td class="px-4 py-2.5 text-sm text-slate-600">{{ $loop->first ? $asiento->descripcion : '' }}</td>
                            <td class="px-4 py-2.5 text-sm text-slate-800">{{ $partida->cuenta->codigo }} - {{ $partida->cuenta->nombre }}</td>
                            <td class="px-4 py-2.5 text-sm text-right font-mono text-slate-800">{{ $partida->debe > 0 ? number_format($partida->debe, 2) : '-' }}</td>
                            <td class="px-4 py-2.5 text-sm text-right font-mono text-slate-800">{{ $partida->haber > 0 ? number_format($partida->haber, 2) : '-' }}</td>
                        </tr>
                        @endforeach
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                            No se encontraron asientos para los filtros seleccionados.
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
</div>
@endsection
