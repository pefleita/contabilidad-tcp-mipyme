@extends('layouts.app')

@section('title', 'Depreciación de Activos')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Depreciación de Activos</h3>
            <p class="text-slate-500">Cálculo de depreciación por método de línea recta</p>
        </div>
        <a href="{{ route('activos.index') }}" class="text-sm text-slate-600 hover:text-slate-800">← Volver</a>
    </div>

    @if(isset($sinEmpresa) && $sinEmpresa)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
        <p class="text-amber-800 font-medium">Registre los datos de su empresa primero.</p>
    </div>
    @else
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase">Activo</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Costo Original</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Valor Residual</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Base Depreciable</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-slate-500 uppercase">Vida Útil</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Dep. Anual</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Dep. Mensual</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Dep. Acumulada</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Valor Neto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $totalCosto = 0;
                        $totalDepreciacion = 0;
                        $totalValorNeto = 0;
                    @endphp
                    @forelse($activos as $activo)
                    @php
                        $totalCosto += $activo->costo_original;
                        $depAcum = $activo->calcularDepreciacionAcumulada();
                        $totalDepreciacion += $depAcum;
                        $totalValorNeto += $activo->valorNeto();
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-800">{{ $activo->nombre }}</p>
                            <p class="text-xs text-slate-500">{{ $activo->codigo }} · {{ ucfirst($activo->tipo) }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-right font-mono text-slate-800">${{ number_format($activo->costo_original, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right font-mono text-slate-500">${{ number_format($activo->valor_residual, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right font-mono text-slate-800">${{ number_format($activo->costo_original - $activo->valor_residual, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-center text-slate-600">{{ $activo->vida_util_anos }} años</td>
                        <td class="px-6 py-4 text-sm text-right font-mono text-slate-800">${{ number_format($activo->depreciacionAnual(), 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right font-mono text-slate-600">${{ number_format($activo->depreciacionMensual(), 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right font-mono text-amber-600">${{ number_format($depAcum, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right font-mono font-semibold text-emerald-600">${{ number_format($activo->valorNeto(), 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-slate-500">
                            No hay activos activos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="border-t-2 border-slate-200 bg-slate-50">
                    <tr class="font-semibold">
                        <td class="px-6 py-4 text-sm text-slate-700">Totales</td>
                        <td class="px-6 py-4 text-sm text-right font-mono text-slate-800">${{ number_format($totalCosto, 2) }}</td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4 text-sm text-right font-mono text-slate-800">${{ number_format($totalDepreciacion, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-right font-mono text-slate-800">${{ number_format($totalValorNeto, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
