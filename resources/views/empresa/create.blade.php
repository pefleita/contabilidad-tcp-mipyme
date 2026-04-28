@extends('layouts.app')

@section('title', 'Registrar Empresa')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-800">Registra tu Empresa</h3>
        <p class="text-slate-500 mt-2">Completa los datos de tu negocio para comenzar</p>
    </div>

    @if(session('warning'))
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-center gap-3 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="text-sm text-amber-800">{{ session('warning') }}</p>
    </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('empresa.store') }}" class="space-y-6">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">Información de la Empresa</h4>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre de la Empresa *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Mi Negocio S.A." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">NIT *</label>
                    <input type="text" name="nit" value="{{ old('nit') }}" required placeholder="Ej: 12345678901" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('nit') border-red-500 @enderror">
                    @error('nit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Actividad Económica *</label>
                    <input type="text" name="actividad_economica" value="{{ old('actividad_economica') }}" required placeholder="Ej: Comercio al por menor" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('actividad_economica') border-red-500 @enderror">
                    @error('actividad_economica')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Contabilidad *</label>
                    <select name="tipo_contabilidad" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('tipo_contabilidad') border-red-500 @enderror">
                        <option value="">Seleccionar...</option>
                        <option value="simplificada" {{ old('tipo_contabilidad') === 'simplificada' ? 'selected' : '' }}>
                            Simplificada (ingresos < 500,000 CUP/año)
                        </option>
                        <option value="formal" {{ old('tipo_contabilidad') === 'formal' ? 'selected' : '' }}>
                            Formal (ingresos >= 500,000 CUP/año)
                        </option>
                    </select>
                    @error('tipo_contabilidad')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-500 bg-slate-50 p-2 rounded">
                        Según la Resolución 272/2024 del Ministerio de Finanzas y Precios, el tipo de contabilidad se determina por tus ingresos anuales.
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-center">
            <button type="submit" class="px-8 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">
                Registrar Empresa
            </button>
        </div>
    </form>
</div>
@endsection