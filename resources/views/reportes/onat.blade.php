@extends('layouts.app')

@section('title', $data ? "Informe ONAT - {$data['anio']}" : 'Informe ONAT')

@section('content')
@if(!$data)
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-center">
    <p class="text-slate-500">Debe configurar los datos de su empresa antes de generar reportes.</p>
    <a href="{{ route('empresa.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700">Configurar Empresa</a>
</div>
@else
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Informe para Declaración Jurada ONAT</h3>
                <p class="text-sm text-slate-500">Resolución 272/2024 - Año Fiscal {{ $data['anio'] }}</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    <select name="anio" onchange="this.form.submit()" class="rounded-lg border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        @for ($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $data['anio'] == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>
                <div class="flex gap-2">
                    <a href="{{ route('reportes.informe-onat.pdf', ['anio' => $data['anio']]) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        PDF
                    </a>
                    <a href="{{ route('reportes.informe-onat.excel', ['anio' => $data['anio']]) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($data['empresa'])
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-semibold text-slate-800 mb-4">Datos de la Empresa</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wide">Nombre</p>
                <p class="text-sm font-medium text-slate-800">{{ $data['empresa']->nombre }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wide">NIT</p>
                <p class="text-sm font-medium text-slate-800">{{ $data['empresa']->nit ?? 'No configurado' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wide">Actividad Económica</p>
                <p class="text-sm font-medium text-slate-800">{{ $data['empresa']->actividad_economica ?? 'No especificada' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-semibold text-slate-800 mb-4">Estado de Cuenta</h4>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ $data['empresa']->esContabilidadFormal() ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium">
                Contabilidad {{ $data['empresa']->esContabilidadFormal() ? 'Formal' : 'Simplificada' }}
            </span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Total Ingresos</p>
            <p class="text-2xl font-bold text-emerald-600">${{ number_format($data['ingresos'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Total Gastos (Deducibles)</p>
            <p class="text-2xl font-bold text-rose-600">${{ number_format($data['gastos'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Base Imponible</p>
            <p class="text-2xl font-bold {{ $data['baseImponible'] >= 0 ? 'text-slate-800' : 'text-rose-600' }}">${{ number_format($data['baseImponible'], 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-semibold text-slate-800 mb-4">Resumen Mensual</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 font-medium text-slate-600">Mes</th>
                        <th class="text-right py-3 px-4 font-medium text-slate-600">Ingresos</th>
                        <th class="text-right py-3 px-4 font-medium text-slate-600">Gastos</th>
                        <th class="text-right py-3 px-4 font-medium text-slate-600">Base Imponible</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($m = 1; $m <= 12; $m++)
                    @php
                        $ing = (float) ($data['ingresosPorMes'][$m] ?? 0);
                        $gas = (float) ($data['gastosPorMes'][$m] ?? 0);
                    @endphp
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-800">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</td>
                        <td class="py-3 px-4 text-right text-emerald-600">${{ number_format($ing, 2) }}</td>
                        <td class="py-3 px-4 text-right text-rose-600">${{ number_format($gas, 2) }}</td>
                        <td class="py-3 px-4 text-right {{ ($ing - $gas) >= 0 ? 'text-slate-800' : 'text-rose-600' }}">${{ number_format($ing - $gas, 2) }}</td>
                    </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 font-semibold">
                        <td class="py-3 px-4 text-slate-800">TOTAL</td>
                        <td class="py-3 px-4 text-right text-emerald-600">${{ number_format($data['ingresos'], 2) }}</td>
                        <td class="py-3 px-4 text-right text-rose-600">${{ number_format($data['gastos'], 2) }}</td>
                        <td class="py-3 px-4 text-right {{ $data['baseImponible'] >= 0 ? 'text-slate-800' : 'text-rose-600' }}">${{ number_format($data['baseImponible'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-semibold text-slate-800 mb-4">Historial de Declaraciones</h4>
        @if(count($data['historial']) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 font-medium text-slate-600">Año Fiscal</th>
                        <th class="text-right py-3 px-4 font-medium text-slate-600">Ingresos</th>
                        <th class="text-right py-3 px-4 font-medium text-slate-600">Gastos</th>
                        <th class="text-right py-3 px-4 font-medium text-slate-600">Base Imponible</th>
                        <th class="text-center py-3 px-4 font-medium text-slate-600">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['historial'] as $h)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-800 font-medium">{{ $h['anio'] }}</td>
                        <td class="py-3 px-4 text-right text-emerald-600">${{ number_format($h['ingresos'], 2) }}</td>
                        <td class="py-3 px-4 text-right text-rose-600">${{ number_format($h['gastos'], 2) }}</td>
                        <td class="py-3 px-4 text-right {{ $h['baseImponible'] >= 0 ? 'text-slate-800' : 'text-rose-600' }}">${{ number_format($h['baseImponible'], 2) }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $h['tieneTransacciones'] ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $h['tieneTransacciones'] ? 'Declarado' : 'Sin datos' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-sm text-slate-500">No hay datos históricos disponibles.</p>
        @endif
    </div>
</div>
@endif
@endsection