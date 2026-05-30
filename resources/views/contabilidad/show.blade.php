@extends('layouts.app')

@section('title', 'Asiento #' . $asiento->numero_asiento)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('contabilidad.index') }}" class="text-slate-500 hover:text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l7-7m-7 7l7 7" />
            </svg>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Asiento #{{ $asiento->numero_asiento }}</h3>
            <p class="text-slate-500">{{ $asiento->descripcion }}</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex items-center gap-3 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="grid grid-cols-3 gap-6 mb-6 pb-6 border-b border-slate-200">
            <div>
                <p class="text-xs text-slate-500 uppercase">Número</p>
                <p class="text-sm font-medium text-slate-800">{{ $asiento->numero_asiento }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase">Fecha</p>
                <p class="text-sm font-medium text-slate-800">{{ $asiento->fecha->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase">Estado</p>
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                    @if($asiento->estado === 'confirmado') bg-emerald-100 text-emerald-700
                    @elseif($asiento->estado === 'borrador') bg-amber-100 text-amber-700
                    @else bg-red-100 text-red-700 @endif">
                    {{ ucfirst($asiento->estado) }}
                </span>
            </div>
        </div>

        <h4 class="text-sm font-medium text-slate-700 mb-3">Partidas</h4>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-4 py-2 text-xs font-medium text-slate-500 uppercase">Código</th>
                        <th class="text-left px-4 py-2 text-xs font-medium text-slate-500 uppercase">Cuenta</th>
                        <th class="text-right px-4 py-2 text-xs font-medium text-slate-500 uppercase">Debe</th>
                        <th class="text-right px-4 py-2 text-xs font-medium text-slate-500 uppercase">Haber</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($asiento->partidas as $partida)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-mono text-slate-600">{{ $partida->cuenta->codigo }}</td>
                        <td class="px-4 py-3 text-sm text-slate-800">{{ $partida->cuenta->nombre }}</td>
                        <td class="px-4 py-3 text-sm text-right font-mono text-slate-800">
                            {{ $partida->debe > 0 ? number_format($partida->debe, 2) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right font-mono text-slate-800">
                            {{ $partida->haber > 0 ? number_format($partida->haber, 2) : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-slate-200">
                    <tr class="font-semibold">
                        <td colspan="2" class="px-4 py-3 text-sm text-slate-700">Totales</td>
                        <td class="px-4 py-3 text-sm text-right font-mono text-slate-800">{{ number_format($asiento->totalDebe(), 2) }}</td>
                        <td class="px-4 py-3 text-sm text-right font-mono text-slate-800">{{ number_format($asiento->totalHaber(), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="flex items-center justify-between mt-6">
        <a href="{{ route('contabilidad.index') }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 transition-colors">← Volver</a>
        <div class="flex items-center gap-3">
            @if($asiento->estado === 'borrador')
            <a href="{{ route('contabilidad.edit', $asiento) }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">Editar</a>
            @endif
        </div>
    </div>
</div>
@endsection
