@extends('layouts.app')

@section('title', 'Resumen Anual')

@section('content')
@if(empty($data))
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-center">
    <p class="text-slate-500">Debe configurar los datos de su empresa antes de generar reportes.</p>
    <a href="{{ route('empresa.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700">Configurar Empresa</a>
</div>
@else
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Resumen Anual de Ingresos y Gastos</h3>
                <p class="text-sm text-slate-500">Desglose mensual por categoría y tendencias del año fiscal</p>
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
                    <a href="{{ route('reportes.resumen-anual.pdf', ['anio' => $data['anio']]) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-600 text-white text-sm font-medium rounded-lg hover:bg-rose-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        PDF
                    </a>
                    <a href="{{ route('reportes.resumen-anual.excel', ['anio' => $data['anio']]) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Total Ingresos</p>
            <p class="text-2xl font-bold text-emerald-600">${{ number_format($data['totalIngresos'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Total Gastos</p>
            <p class="text-2xl font-bold text-rose-600">${{ number_format($data['totalGastos'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Balance Anual</p>
            <p class="text-2xl font-bold {{ $data['balanceAnual'] >= 0 ? 'text-slate-800' : 'text-rose-600' }}">${{ number_format($data['balanceAnual'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-sm text-slate-500">Margen de Ganancia</p>
            <p class="text-2xl font-bold {{ $data['totalIngresos'] > 0 && $data['balanceAnual'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $data['totalIngresos'] > 0 ? number_format(($data['balanceAnual'] / $data['totalIngresos']) * 100, 1) : 0 }}%
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-semibold text-slate-800 mb-4">Tendencia Mensual</h4>
        <div class="relative" style="height: 300px;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">Ingresos por Categoría</h4>
            <div class="space-y-3">
                @forelse($data['ingresosPorCategoria'] as $item)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-700">{{ $item->categoria->nombre ?? 'Sin categoría' }}</span>
                    <div class="flex items-center gap-3">
                        <div class="w-32 bg-slate-100 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $data['totalIngresos'] > 0 ? ($item->total / $data['totalIngresos']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-slate-800 w-24 text-right">${{ number_format($item->total, 2) }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-500">No hay ingresos registrados en este año.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">Gastos por Categoría</h4>
            <div class="space-y-3">
                @forelse($data['gastosPorCategoria'] as $item)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-700">{{ $item->categoria->nombre ?? 'Sin categoría' }}</span>
                    <div class="flex items-center gap-3">
                        <div class="w-32 bg-slate-100 rounded-full h-2">
                            <div class="bg-rose-500 h-2 rounded-full" style="width: {{ $data['totalGastos'] > 0 ? ($item->total / $data['totalGastos']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-slate-800 w-24 text-right">${{ number_format($item->total, 2) }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-500">No hay gastos registrados en este año.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h4 class="text-lg font-semibold text-slate-800 mb-4">Desglose Mensual</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 font-medium text-slate-600">Mes</th>
                        <th class="text-right py-3 px-4 font-medium text-slate-600">Ingresos</th>
                        <th class="text-right py-3 px-4 font-medium text-slate-600">Gastos</th>
                        <th class="text-right py-3 px-4 font-medium text-slate-600">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['meses'] as $mes)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-800">{{ \Carbon\Carbon::create()->month($mes['mes'])->format('F') }}</td>
                        <td class="py-3 px-4 text-right text-emerald-600">${{ number_format($mes['ingresos'], 2) }}</td>
                        <td class="py-3 px-4 text-right text-rose-600">${{ number_format($mes['gastos'], 2) }}</td>
                        <td class="py-3 px-4 text-right {{ $mes['balance'] >= 0 ? 'text-slate-800' : 'text-rose-600' }}">${{ number_format($mes['balance'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 font-semibold">
                        <td class="py-3 px-4 text-slate-800">TOTAL</td>
                        <td class="py-3 px-4 text-right text-emerald-600">${{ number_format($data['totalIngresos'], 2) }}</td>
                        <td class="py-3 px-4 text-right text-rose-600">${{ number_format($data['totalGastos'], 2) }}</td>
                        <td class="py-3 px-4 text-right {{ $data['balanceAnual'] >= 0 ? 'text-slate-800' : 'text-rose-600' }}">${{ number_format($data['balanceAnual'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if(!empty($data))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_map(fn($m) => \Carbon\Carbon::create()->month($m['mes'])->format('M'), $data['meses'])) !!},
            datasets: [{
                label: 'Ingresos',
                data: {!! json_encode(array_map(fn($m) => $m['ingresos'], $data['meses'])) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Gastos',
                data: {!! json_encode(array_map(fn($m) => $m['gastos'], $data['meses'])) !!},
                borderColor: '#f43f5e',
                backgroundColor: 'rgba(244, 63, 94, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return '$' + value.toLocaleString(); }
                    }
                }
            }
        }
    });
});
</script>
@endif
@endpush