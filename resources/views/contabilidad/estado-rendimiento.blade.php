@extends('layouts.app')

@section('title', 'Estado de Rendimiento')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Estado de Rendimiento</h3>
            <p class="text-slate-500">Estado de Resultados al {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-emerald-700 mb-4">INGRESOS</h4>
            <div class="space-y-3">
                @forelse($cuentasIngreso as $cuenta)
                @php
                $saldo = 0;
                if ($cuenta->es_movimiento) {
                    $saldo = \App\Models\PartidaAsiento::whereHas('asiento', fn($q) => $q->where('empresa_id', $cuenta->empresa_id)->where('estado', 'confirmado'))->where('cuenta_id', $cuenta->id)->sum('haber') - \App\Models\PartidaAsiento::whereHas('asiento', fn($q) => $q->where('empresa_id', $cuenta->empresa_id)->where('estado', 'confirmado'))->where('cuenta_id', $cuenta->id)->sum('debe');
                }
                @endphp
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">{{ $cuenta->codigo }} - {{ $cuenta->nombre }}</span>
                    <span class="font-mono font-medium text-slate-800">${{ number_format($saldo, 2) }}</span>
                </div>
                @empty
                <p class="text-sm text-slate-500">No hay cuentas de ingreso registradas.</p>
                @endforelse
            </div>
            <div class="border-t-2 border-emerald-200 mt-4 pt-4 flex justify-between font-bold text-lg">
                <span class="text-emerald-700">Total Ingresos</span>
                <span class="font-mono text-emerald-700">${{ number_format($totalIngresos, 2) }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-red-700 mb-4">GASTOS</h4>
            <div class="space-y-3">
                @forelse($cuentasGasto as $cuenta)
                @php
                $saldo = 0;
                if ($cuenta->es_movimiento) {
                    $saldo = \App\Models\PartidaAsiento::whereHas('asiento', fn($q) => $q->where('empresa_id', $cuenta->empresa_id)->where('estado', 'confirmado'))->where('cuenta_id', $cuenta->id)->sum('debe') - \App\Models\PartidaAsiento::whereHas('asiento', fn($q) => $q->where('empresa_id', $cuenta->empresa_id)->where('estado', 'confirmado'))->where('cuenta_id', $cuenta->id)->sum('haber');
                }
                @endphp
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">{{ $cuenta->codigo }} - {{ $cuenta->nombre }}</span>
                    <span class="font-mono font-medium text-slate-800">${{ number_format($saldo, 2) }}</span>
                </div>
                @empty
                <p class="text-sm text-slate-500">No hay cuentas de gasto registradas.</p>
                @endforelse
            </div>
            <div class="border-t-2 border-red-200 mt-4 pt-4 flex justify-between font-bold text-lg">
                <span class="text-red-700">Total Gastos</span>
                <span class="font-mono text-red-700">${{ number_format($totalGastos, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border-2 p-6 text-center
        @if($resultado >= 0) border-emerald-200 @else border-red-200 @endif">
        <p class="text-sm text-slate-500 uppercase font-medium">Resultado del Período</p>
        <p class="text-3xl font-bold mt-2 font-mono {{ $resultado >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
            @if($resultado >= 0) + @endif ${{ number_format($resultado, 2) }}
        </p>
        <p class="text-sm mt-1 {{ $resultado >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
            {{ $resultado >= 0 ? 'Ganancia' : 'Pérdida' }}
        </p>
    </div>
</div>
@endsection
