@extends('layouts.app')

@section('title', 'Estado de Situación')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Estado de Situación</h3>
            <p class="text-slate-500">Balance General al {{ now()->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">ACTIVO</h4>
            <div class="space-y-2">
                @foreach($cuentas->where('tipo', 'activo') as $cuenta)
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">{{ $cuenta->codigo }} - {{ $cuenta->nombre }}</span>
                    <span class="font-mono font-medium text-slate-800">${{ number_format($cuenta->es_movimiento ? \App\Models\PartidaAsiento::whereHas('asiento', fn($q) => $q->where('empresa_id', $cuenta->empresa_id)->where('estado', 'confirmado'))->where('cuenta_id', $cuenta->id)->sum('debe') - \App\Models\PartidaAsiento::whereHas('asiento', fn($q) => $q->where('empresa_id', $cuenta->empresa_id)->where('estado', 'confirmado'))->where('cuenta_id', $cuenta->id)->sum('haber') : 0, 2) }}</span>
                </div>
                @endforeach
            </div>
            <div class="border-t border-slate-200 mt-4 pt-4 flex justify-between font-semibold">
                <span class="text-slate-800">Total Activo</span>
                <span class="font-mono text-emerald-700">${{ number_format($totalActivo, 2) }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">PASIVO</h4>
            <div class="space-y-2">
                @foreach($cuentas->where('tipo', 'pasivo') as $cuenta)
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">{{ $cuenta->codigo }} - {{ $cuenta->nombre }}</span>
                    <span class="font-mono font-medium text-slate-800">${{ number_format($cuenta->es_movimiento ? \App\Models\PartidaAsiento::whereHas('asiento', fn($q) => $q->where('empresa_id', $cuenta->empresa_id)->where('estado', 'confirmado'))->where('cuenta_id', $cuenta->id)->sum('haber') - \App\Models\PartidaAsiento::whereHas('asiento', fn($q) => $q->where('empresa_id', $cuenta->empresa_id)->where('estado', 'confirmado'))->where('cuenta_id', $cuenta->id)->sum('debe') : 0, 2) }}</span>
                </div>
                @endforeach
            </div>
            <div class="border-t border-slate-200 mt-4 pt-4 flex justify-between font-semibold">
                <span class="text-slate-800">Total Pasivo</span>
                <span class="font-mono text-blue-700">${{ number_format($totalPasivo, 2) }}</span>
            </div>

            <h4 class="text-lg font-semibold text-slate-800 mt-8 mb-4">PATRIMONIO</h4>
            <div class="space-y-2">
                @foreach($cuentas->where('tipo', 'patrimonio') as $cuenta)
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">{{ $cuenta->codigo }} - {{ $cuenta->nombre }}</span>
                    <span class="font-mono font-medium text-slate-800">${{ number_format($cuenta->es_movimiento ? \App\Models\PartidaAsiento::whereHas('asiento', fn($q) => $q->where('empresa_id', $cuenta->empresa_id)->where('estado', 'confirmado'))->where('cuenta_id', $cuenta->id)->sum('haber') - \App\Models\PartidaAsiento::whereHas('asiento', fn($q) => $q->where('empresa_id', $cuenta->empresa_id)->where('estado', 'confirmado'))->where('cuenta_id', $cuenta->id)->sum('debe') : 0, 2) }}</span>
                </div>
                @endforeach
            </div>
            <div class="border-t border-slate-200 mt-4 pt-4 flex justify-between font-semibold">
                <span class="text-slate-800">Total Patrimonio</span>
                <span class="font-mono text-purple-700">${{ number_format($totalPatrimonio, 2) }}</span>
            </div>

            <div class="border-t-2 border-slate-800 mt-6 pt-4 flex justify-between font-bold text-lg">
                <span class="text-slate-800">Total Pasivo + Patrimonio</span>
                <span class="font-mono text-slate-800">${{ number_format($totalPasivo + $totalPatrimonio, 2) }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 rounded-full {{ $cuadra ? 'bg-emerald-100' : 'bg-red-100' }} flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 {{ $cuadra ? 'text-emerald-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if($cuadra)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    @endif
                </svg>
            </div>
            <h5 class="text-lg font-semibold {{ $cuadra ? 'text-emerald-700' : 'text-red-700' }}">
                @if($cuadra) Balance General CUADRA @else Balance General NO CUADRA @endif
            </h5>
            <p class="text-sm text-slate-500 mt-1">
                @if($cuadra)
                Activo = Pasivo + Patrimonio
                @else
                Diferencia: ${{ number_format(abs($totalActivo - ($totalPasivo + $totalPatrimonio)), 2) }}
                @endif
            </p>
        </div>
    </div>
</div>
@endsection
