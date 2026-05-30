@extends('layouts.app')

@section('title', 'Libro Mayor')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Libro Mayor</h3>
            <p class="text-slate-500">Movimiento detallado por cuenta contable</p>
        </div>
    </div>

    <form method="GET" class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="grid grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Cuenta Contable</label>
                <select name="cuenta_id" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">Seleccionar cuenta</option>
                    @foreach($cuentas as $c)
                    <option value="{{ $c->id }}" {{ request('cuenta_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->codigo }} - {{ $c->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
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
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-slate-900 rounded-lg hover:bg-slate-800 transition-colors">Consultar</button>
                <a href="{{ route('contabilidad.libro-mayor') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">Limpiar</a>
            </div>
        </div>
    </form>

    @if($cuenta)
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="grid grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-slate-500">Cuenta:</span>
                <span class="font-medium text-slate-800 ml-1">{{ $cuenta->codigo }} - {{ $cuenta->nombre }}</span>
            </div>
            <div>
                <span class="text-slate-500">Saldo Anterior:</span>
                <span class="font-medium text-slate-800 ml-1 font-mono">${{ number_format($saldoAnterior, 2) }}</span>
            </div>
            <div>
                <span class="text-slate-500">Total Debe:</span>
                <span class="font-medium text-slate-800 ml-1 font-mono">${{ number_format($totalDebe, 2) }}</span>
            </div>
            <div>
                <span class="text-slate-500">Total Haber:</span>
                <span class="font-medium text-slate-800 ml-1 font-mono">${{ number_format($totalHaber, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-4 py-3 text-xs font-medium text-slate-500 uppercase">Fecha</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-slate-500 uppercase">N° Asiento</th>
                        <th class="text-left px-4 py-3 text-xs font-medium text-slate-500 uppercase">Descripción</th>
                        <th class="text-right px-4 py-3 text-xs font-medium text-slate-500 uppercase">Debe</th>
                        <th class="text-right px-4 py-3 text-xs font-medium text-slate-500 uppercase">Haber</th>
                        <th class="text-right px-4 py-3 text-xs font-medium text-slate-500 uppercase">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $saldo = $saldoAnterior; @endphp
                    @forelse($partidas as $partida)
                    @php
                        if (in_array($cuenta->tipo, ['activo', 'gasto'])) {
                            $saldo += (float) $partida->debe - (float) $partida->haber;
                        } else {
                            $saldo += (float) $partida->haber - (float) $partida->debe;
                        }
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-2.5 text-sm text-slate-600">{{ $partida->asiento->fecha->format('d/m/Y') }}</td>
                        <td class="px-4 py-2.5 text-sm font-medium text-slate-800">{{ $partida->asiento->numero_asiento }}</td>
                        <td class="px-4 py-2.5 text-sm text-slate-600">{{ $partida->asiento->descripcion }}</td>
                        <td class="px-4 py-2.5 text-sm text-right font-mono text-slate-800">{{ $partida->debe > 0 ? number_format($partida->debe, 2) : '-' }}</td>
                        <td class="px-4 py-2.5 text-sm text-right font-mono text-slate-800">{{ $partida->haber > 0 ? number_format($partida->haber, 2) : '-' }}</td>
                        <td class="px-4 py-2.5 text-sm text-right font-mono font-semibold text-slate-800">{{ number_format($saldo, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-slate-500">
                            No hay movimientos para esta cuenta en el período seleccionado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="border-t-2 border-slate-200 bg-slate-50">
                    <tr class="font-semibold">
                        <td colspan="3" class="px-4 py-3 text-sm text-slate-700">Totales / Saldo Final</td>
                        <td class="px-4 py-3 text-sm text-right font-mono text-slate-800">{{ number_format($totalDebe, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-right font-mono text-slate-800">{{ number_format($totalHaber, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-right font-mono text-slate-800">{{ number_format($saldo, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
        <p class="text-slate-500">Seleccione una cuenta contable para ver su libro mayor.</p>
    </div>
    @endif
</div>
@endsection
