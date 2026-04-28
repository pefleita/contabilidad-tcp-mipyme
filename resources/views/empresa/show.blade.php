@extends('layouts.app')

@section('title', 'Mi Empresa')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Datos de la Empresa</h3>
            <p class="text-slate-500">Información registrada</p>
        </div>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('empresa.edit') }}" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Editar
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="text-sm text-amber-800">{{ session('warning') }}</p>
    </div>
    @endif

    <!-- Company Info -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-slate-800">{{ $empresa->nombre }}</h4>
                    <p class="text-sm text-slate-500">NIT: {{ $empresa->nit }}</p>
                </div>
            </div>
            <span class="px-3 py-1 text-sm font-medium rounded-full {{ $empresa->tipo_contabilidad === 'formal' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                {{ $empresa->tipo_contabilidad === 'formal' ? 'Contabilidad Formal' : 'Contabilidad Simplificada' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-500 mb-1">Actividad Económica</label>
                <p class="text-slate-800">{{ $empresa->actividad_economica }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-500 mb-1">Tipo de Contabilidad</label>
                <p class="text-slate-800">
                    @if($empresa->tipo_contabilidad === 'formal')
                    Contabilidad Formal (>= 500,000 CUP/año)
                    @else
                    Contabilidad Simplificada (< 500,000 CUP/año)
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-blue-800">
                Según la Resolución 272/2024, si tus ingresos anuales superan los 500,000 CUP debes llevar contabilidad formal.
            </p>
        </div>
    </div>
</div>
@endsection