@extends('layouts.app')

@section('title', $activo->nombre)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('activos.index') }}" class="text-slate-500 hover:text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l7-7m-7 7l7 7" />
            </svg>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-slate-800">{{ $activo->nombre }}</h3>
            <p class="text-slate-500 capitalize">{{ $activo->tipo }} · {{ $activo->codigo }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6 text-center">
            <p class="text-xs text-slate-500 uppercase">Costo Original</p>
            <p class="text-2xl font-bold text-slate-800 font-mono mt-1">${{ number_format($activo->costo_original, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-6 text-center">
            <p class="text-xs text-slate-500 uppercase">Dep. Acumulada</p>
            <p class="text-2xl font-bold text-amber-600 font-mono mt-1">${{ number_format($activo->calcularDepreciacionAcumulada(), 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $activo->vida_util_anos }} años de vida útil</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-6 text-center">
            <p class="text-xs text-slate-500 uppercase">Valor Neto</p>
            <p class="text-2xl font-bold text-emerald-600 font-mono mt-1">${{ number_format($activo->valorNeto(), 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 mt-6">
        <h4 class="text-lg font-semibold text-slate-800 mb-4">Detalles del Activo</h4>
        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-xs text-slate-500 uppercase">Código</dt>
                <dd class="text-sm font-mono text-slate-800 mt-1">{{ $activo->codigo }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500 uppercase">Tipo</dt>
                <dd class="text-sm capitalize text-slate-800 mt-1">{{ $activo->tipo }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500 uppercase">Estado</dt>
                <dd class="mt-1">
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $activo->esta_activo ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $activo->esta_activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500 uppercase">Vida Útil</dt>
                <dd class="text-sm text-slate-800 mt-1">{{ $activo->vida_util_anos }} años</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500 uppercase">Fecha de Adquisición</dt>
                <dd class="text-sm text-slate-800 mt-1">{{ $activo->fecha_adquisicion->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500 uppercase">Inicio Depreciación</dt>
                <dd class="text-sm text-slate-800 mt-1">{{ $activo->fecha_inicio_depreciacion->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500 uppercase">Valor Residual</dt>
                <dd class="text-sm font-mono text-slate-800 mt-1">${{ number_format($activo->valor_residual, 2) }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-500 uppercase">Depreciación Anual</dt>
                <dd class="text-sm font-mono text-slate-800 mt-1">${{ number_format($activo->depreciacionAnual(), 2) }}</dd>
            </div>
            @if($activo->descripcion)
            <div class="col-span-2">
                <dt class="text-xs text-slate-500 uppercase">Descripción</dt>
                <dd class="text-sm text-slate-800 mt-1">{{ $activo->descripcion }}</dd>
            </div>
            @endif
            @if($activo->observaciones)
            <div class="col-span-2">
                <dt class="text-xs text-slate-500 uppercase">Observaciones</dt>
                <dd class="text-sm text-slate-800 mt-1">{{ $activo->observaciones }}</dd>
            </div>
            @endif
        </dl>
    </div>

    <div class="flex items-center justify-between mt-6">
        <a href="{{ route('activos.index') }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 transition-colors">← Volver</a>
        <a href="{{ route('activos.edit', $activo) }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">Editar</a>
    </div>
</div>
@endsection
