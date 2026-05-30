@extends('layouts.app')

@section('title', 'Libro de Activos Fijos')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Libro de Activos Fijos</h3>
            <p class="text-slate-500">Registro completo de activos fijos</p>
        </div>
        <a href="{{ route('activos.index') }}" class="text-sm text-slate-600 hover:text-slate-800">← Volver</a>
    </div>

    @if(isset($sinEmpresa) && $sinEmpresa)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
        <p class="text-amber-800 font-medium">Registre los datos de su empresa primero.</p>
    </div>
    @else
    @foreach($totalesPorTipo as $tipo => $totales)
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-3 border-b border-slate-200">
            <h4 class="text-sm font-semibold text-slate-700 uppercase">{{ $tipo }} ({{ $totales['cantidad'] }} activos)</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase">Código</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase">Nombre</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Costo Original</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Dep. Acumulada</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-slate-500 uppercase">Valor Neto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($activos->where('tipo', $tipo) as $activo)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-3 text-sm font-mono text-slate-600">{{ $activo->codigo }}</td>
                        <td class="px-6 py-3 text-sm text-slate-800">{{ $activo->nombre }}</td>
                        <td class="px-6 py-3 text-sm text-right font-mono text-slate-800">${{ number_format($activo->costo_original, 2) }}</td>
                        <td class="px-6 py-3 text-sm text-right font-mono text-slate-600">${{ number_format($activo->calcularDepreciacionAcumulada(), 2) }}</td>
                        <td class="px-6 py-3 text-sm text-right font-mono font-medium text-slate-800">${{ number_format($activo->valorNeto(), 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-slate-200 bg-slate-50">
                    <tr class="font-semibold">
                        <td colspan="2" class="px-6 py-3 text-sm text-slate-700">Total {{ ucfirst($tipo) }}</td>
                        <td class="px-6 py-3 text-sm text-right font-mono text-slate-800">${{ number_format($totales['costo'], 2) }}</td>
                        <td class="px-6 py-3 text-sm text-right font-mono text-slate-800">${{ number_format($totales['depreciacion'], 2) }}</td>
                        <td class="px-6 py-3 text-sm text-right font-mono text-slate-800">${{ number_format($totales['valor_neto'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endforeach

    <div class="bg-slate-900 rounded-xl p-6 text-white">
        <div class="grid grid-cols-3 gap-6 text-center">
            <div>
                <p class="text-xs text-slate-400 uppercase">Total Costo Original</p>
                <p class="text-2xl font-bold font-mono mt-1">${{ number_format($activos->sum('costo_original'), 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Total Dep. Acumulada</p>
                <p class="text-2xl font-bold font-mono mt-1 text-amber-400">${{ number_format($activos->sum(function($a) { return $a->calcularDepreciacionAcumulada(); }), 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Total Valor en Libros</p>
                <p class="text-2xl font-bold font-mono mt-1 text-emerald-400">${{ number_format($activos->sum(function($a) { return $a->valorNeto(); }), 2) }}</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
