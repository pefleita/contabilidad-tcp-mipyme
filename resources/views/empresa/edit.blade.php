@extends('layouts.app')

@section('title', 'Editar Empresa')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('empresa.index') }}" class="p-2 text-slate-400 hover:text-slate-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Editar Empresa</h3>
            <p class="text-slate-500">Actualiza los datos de tu negocio</p>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('empresa.update') }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">Información de la Empresa</h4>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre de la Empresa *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $empresa->nombre) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">NIT *</label>
                    <input type="text" name="nit" value="{{ old('nit', $empresa->nit) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('nit') border-red-500 @enderror">
                    @error('nit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Actividad Económica *</label>
                    <input type="text" name="actividad_economica" value="{{ old('actividad_economica', $empresa->actividad_economica) }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('actividad_economica') border-red-500 @enderror">
                    @error('actividad_economica')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Contabilidad *</label>
                    <select name="tipo_contabilidad" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('tipo_contabilidad') border-red-500 @enderror">
                        <option value="simplificada" {{ $empresa->tipo_contabilidad === 'simplificada' ? 'selected' : '' }}>
                            Simplificada (ingresos < 500,000 CUP/año)
                        </option>
                        <option value="formal" {{ $empresa->tipo_contabilidad === 'formal' ? 'selected' : '' }}>
                            Formal (ingresos >= 500,000 CUP/año)
                        </option>
                    </select>
                    @error('tipo_contabilidad')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-500">Según Resolución 272/2024 del Ministerio de Finanzas y Precios</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('empresa.index') }}" class="px-6 py-2 text-slate-600 hover:text-slate-800">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection