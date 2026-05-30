@extends('layouts.app')

@section('title', 'Nuevo Activo Fijo')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('activos.index') }}" class="text-slate-500 hover:text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l7-7m-7 7l7 7" />
            </svg>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Nuevo Activo Fijo</h3>
            <p class="text-slate-500">Registrar un activo fijo</p>
        </div>
    </div>

    <form method="POST" action="{{ route('activos.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Código</label>
                    <input type="text" name="codigo" value="{{ old('codigo') }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('codigo') border-red-300 @enderror">
                    @error('codigo')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                    <select name="tipo" class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('tipo') border-red-300 @enderror">
                        <option value="">Seleccionar...</option>
                        <option value="mueble" {{ old('tipo') === 'mueble' ? 'selected' : '' }}>Mueble</option>
                        <option value="inmueble" {{ old('tipo') === 'inmueble' ? 'selected' : '' }}>Inmueble</option>
                        <option value="vehiculo" {{ old('tipo') === 'vehiculo' ? 'selected' : '' }}>Vehículo</option>
                        <option value="equipo" {{ old('tipo') === 'equipo' ? 'selected' : '' }}>Equipo</option>
                        <option value="otro" {{ old('tipo') === 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('tipo')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                    class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('nombre') border-red-300 @enderror">
                @error('nombre')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="2"
                    class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('descripcion') border-red-300 @enderror">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h4 class="text-lg font-semibold text-slate-800">Valores Financieros</h4>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Costo Original (CUP)</label>
                    <input type="number" step="0.01" min="0" name="costo_original" value="{{ old('costo_original') }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm text-right font-mono focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('costo_original') border-red-300 @enderror">
                    @error('costo_original')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Valor Residual</label>
                    <input type="number" step="0.01" min="0" name="valor_residual" value="{{ old('valor_residual', 0) }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm text-right font-mono focus:ring-2 focus:ring-slate-900 focus:border-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Vida Útil (años)</label>
                    <input type="number" min="1" name="vida_util_anos" value="{{ old('vida_util_anos') }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm text-right focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('vida_util_anos') border-red-300 @enderror">
                    @error('vida_util_anos')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h4 class="text-lg font-semibold text-slate-800">Fechas</h4>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Adquisición</label>
                    <input type="date" name="fecha_adquisicion" value="{{ old('fecha_adquisicion', date('Y-m-d')) }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('fecha_adquisicion') border-red-300 @enderror">
                    @error('fecha_adquisicion')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Inicio de Depreciación</label>
                    <input type="date" name="fecha_inicio_depreciacion" value="{{ old('fecha_inicio_depreciacion', date('Y-m-d')) }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('fecha_inicio_depreciacion') border-red-300 @enderror">
                    @error('fecha_inicio_depreciacion')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <label class="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
            <textarea name="observaciones" rows="2"
                class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900">{{ old('observaciones') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('activos.index') }}" class="px-6 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">Cancelar</a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-slate-900 rounded-lg hover:bg-slate-800 transition-colors">Guardar Activo</button>
        </div>
    </form>
</div>
@endsection
