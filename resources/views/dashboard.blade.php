@php
    $empresa = Auth::user()->empresa;
    $totalIngresos = 0;
    $totalGastos = 0;
    $balance = 0;
    $transaccionesMes = 0;
    $ultimasTransacciones = collect();
    $alertaUmbral = false;
    $ingresosAnuales = 0;

    if ($empresa) {
        $totalIngresos = \App\Models\Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')->where('estado', '!=', 'anulado')->sum('monto');
        $totalGastos = \App\Models\Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')->where('estado', '!=', 'anulado')->sum('monto');
        $balance = $totalIngresos - $totalGastos;

        $transaccionesMes = \App\Models\Transaccion::where('empresa_id', $empresa->id)
            ->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->count();

        $ultimasTransacciones = \App\Models\Transaccion::where('empresa_id', $empresa->id)
            ->where('estado', '!=', 'anulado')
            ->orderBy('fecha', 'desc')->limit(5)->get();

        $ingresosAnuales = \App\Models\Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', now()->year)->sum('monto');
        $alertaUmbral = $ingresosAnuales > 450000;
    }
@endphp

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-2">Bienvenido, {{ Auth::user()->name }}</h3>
        <p class="text-sm text-slate-600">Gestiona tu contabilidad de manera eficiente y cumpliendo con la Resolución 272/2024.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Ingresos</p>
                    <p class="text-2xl font-bold text-emerald-600">${{ number_format($totalIngresos, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Gastos</p>
                    <p class="text-2xl font-bold text-rose-600">${{ number_format($totalGastos, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Balance</p>
                    <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-slate-800' : 'text-rose-600' }}">${{ number_format($balance, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Transacciones del Mes</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $transaccionesMes }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if($alertaUmbral)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <p class="text-sm text-amber-800">
                ⚠️ Has alcanzado el <strong>{{ number_format(($ingresosAnuales / 500000) * 100, 1) }}%</strong> del umbral de ingresos anuales (CUP 500,000). Considera migrar a contabilidad formal.
            </p>
        </div>
    </div>
    @endif

    @if($empresa && $ultimasTransacciones->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-semibold text-slate-800">Últimas Transacciones</h4>
            <a href="{{ route('transacciones.index') }}" class="text-sm text-slate-600 hover:text-slate-800">Ver todas</a>
        </div>
        <div class="space-y-2">
            @foreach($ultimasTransacciones as $t)
            <a href="{{ route('transacciones.show', $t) }}" class="flex items-center justify-between p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <div class="flex items-center gap-3">
                    @if($t->esIngreso())
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    @else
                    <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-slate-800">{{ $t->categoria->nombre }}</p>
                        <p class="text-xs text-slate-500">{{ $t->fecha->format('d/m/Y') }} · {{ Str::limit($t->descripcion, 30) }}</p>
                    </div>
                </div>
                <p class="text-sm font-medium {{ $t->esIngreso() ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $t->esIngreso() ? '+' : '-' }}${{ number_format($t->monto, 2) }}
                </p>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-semibold text-slate-800 mb-4">Acciones Rápidas</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('transacciones.create') }}" class="flex items-center gap-3 p-4 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="text-sm font-medium text-emerald-800">Nueva Transacción</span>
            </a>
            <a href="{{ route('productos.reporte') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium text-slate-800">Ver Reportes</span>
            </a>
            <a href="{{ route('empresa.index') }}" class="flex items-center gap-3 p-4 bg-slate-50 hover:bg-slate-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-sm font-medium text-slate-800">Configuración</span>
            </a>
        </div>
    </div>
</div>
@endsection