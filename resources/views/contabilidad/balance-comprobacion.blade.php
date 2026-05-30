@extends('layouts.app')

@section('title', 'Balance de Comprobación')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Balance de Comprobación</h3>
            <p class="text-slate-500">Verificación de débitos y créditos</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-3 h-3 rounded-full {{ $cuadra ? 'bg-emerald-500' : 'bg-red-500' }}"></div>
                <span class="text-sm font-medium {{ $cuadra ? 'text-emerald-700' : 'text-red-700' }}">
                    @if($cuadra)
                    El balance CUADRA — Total Débitos = Total Créditos
                    @else
                    El balance NO CUADRA — Los totales no coinciden
                    @endif
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 p-6">
            <div class="bg-emerald-50 rounded-lg p-4 text-center">
                <p class="text-xs text-emerald-600 uppercase font-medium">Total Débitos</p>
                <p class="text-2xl font-bold text-emerald-800 font-mono mt-1">${{ number_format($totalDebe, 2) }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-xs text-blue-600 uppercase font-medium">Total Créditos</p>
                <p class="text-2xl font-bold text-blue-800 font-mono mt-1">${{ number_format($totalHaber, 2) }}</p>
            </div>
        </div>

        @if($cuadra)
        <div class="px-6 pb-6">
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 text-center">
                <p class="text-emerald-800 font-medium">✓ El balance está correcto</p>
                <p class="text-emerald-600 text-sm mt-1">Los movimientos contables cumplen con el principio de partida doble.</p>
            </div>
        </div>
        @else
        <div class="px-6 pb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                <p class="text-red-800 font-medium">✗ El balance no cuadra</p>
                <p class="text-red-600 text-sm mt-1">Diferencia: ${{ number_format(abs($totalDebe - $totalHaber), 2) }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
