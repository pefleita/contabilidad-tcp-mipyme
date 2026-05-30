@extends('layouts.app')

@section('title', 'Editar Activo Fijo')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('activos.index') }}" class="text-slate-500 hover:text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l7-7m-7 7l7 7" />
            </svg>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Editar Activo Fijo</h3>
            <p class="text-slate-500">{{ $activo->nombre }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('activos.update', $activo) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Código</label>
                    <input type="text" name="codigo" value="{{ old('codigo', $activo->codigo) }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('codigo') border-red-300 @enderror">
                    @error('codigo')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                    <select name="tipo" class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('tipo') border-red-300 @enderror">
                        <option value="mueble" {{ old('tipo', $activo->tipo) === 'mueble' ? 'selected' : '' }}>Mueble</option>
                        <option value="inmueble" {{ old('tipo', $activo->tipo) === 'inmueble' ? 'selected' : '' }}>Inmueble</option>
                        <option value="vehiculo" {{ old('tipo', $activo->tipo) === 'vehiculo' ? 'selected' : '' }}>Vehículo</option>
                        <option value="equipo" {{ old('tipo', $activo->tipo) === 'equipo' ? 'selected' : '' }}>Equipo</option>
                        <option value="otro" {{ old('tipo', $activo->tipo) === 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', $activo->nombre) }}"
                    class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('nombre') border-red-300 @enderror">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="2"
                    class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900">{{ old('descripcion', $activo->descripcion) }}</textarea>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h4 class="text-lg font-semibold text-slate-800">Valores Financieros</h4>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Costo Original (CUP)</label>
                    <input type="number" step="0.01" min="0" name="costo_original" value="{{ old('costo_original', $activo->costo_original) }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm text-right font-mono focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('costo_original') border-red-300 @enderror">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Valor Residual</label>
                    <input type="number" step="0.01" min="0" name="valor_residual" value="{{ old('valor_residual', $activo->valor_residual) }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm text-right font-mono focus:ring-2 focus:ring-slate-900 focus:border-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Vida Útil (años)</label>
                    <input type="number" min="1" name="vida_util_anos" value="{{ old('vida_util_anos', $activo->vida_util_anos) }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm text-right focus:ring-2 focus:ring-slate-900 focus:border-slate-900 @error('vida_util_anos') border-red-300 @enderror">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h4 class="text-lg font-semibold text-slate-800">Fechas y Estado</h4>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Adquisición</label>
                    <input type="date" name="fecha_adquisicion" value="{{ old('fecha_adquisicion', $activo->fecha_adquisicion->format('Y-m-d')) }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Inicio de Depreciación</label>
                    <input type="date" name="fecha_inicio_depreciacion" value="{{ old('fecha_inicio_depreciacion', $activo->fecha_inicio_depreciacion->format('Y-m-d')) }}"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="esta_activo" id="esta_activo" value="1" {{ old('esta_activo', $activo->esta_activo) ? 'checked' : '' }}
                    class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                <label for="esta_activo" class="text-sm text-slate-700">Activo</label>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <label class="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
            <textarea name="observaciones" rows="2"
                class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-slate-900 focus:border-slate-900">{{ old('observaciones', $activo->observaciones) }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('activos.index') }}" class="px-6 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">Cancelar</a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-slate-900 rounded-lg hover:bg-slate-800 transition-colors">Actualizar Activo</button>
        </div>
    </form>
</div>
@endsection
