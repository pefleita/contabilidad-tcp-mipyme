@extends('layouts.app')

@section('title', 'Editar Transacción')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('transacciones.show', $transaccion) }}" class="p-2 text-slate-400 hover:text-slate-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <h3 class="text-2xl font-bold text-slate-800">Editar Transacción</h3>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <p class="text-sm text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form method="POST" action="{{ route('transacciones.update', $transaccion) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Tipo</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-300 has-[:checked]:ring-1 has-[:checked]:ring-emerald-300">
                        <input type="radio" name="tipo" value="ingreso" {{ old('tipo', $transaccion->tipo) == 'ingreso' ? 'checked' : '' }} class="text-emerald-600" required>
                        <span class="text-sm font-medium text-slate-700">Ingreso</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer has-[:checked]:bg-rose-50 has-[:checked]:border-rose-300 has-[:checked]:ring-1 has-[:checked]:ring-rose-300">
                        <input type="radio" name="tipo" value="gasto" {{ old('tipo', $transaccion->tipo) == 'gasto' ? 'checked' : '' }} class="text-rose-600" required>
                        <span class="text-sm font-medium text-slate-700">Gasto</span>
                    </label>
                </div>
                @error('tipo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Categoría</label>
                    <select name="categoria_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg" required>
                        <option value="">Seleccionar categoría</option>
                        @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" data-tipo="{{ $categoria->tipo }}" {{ old('categoria_id', $transaccion->categoria_id) == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }} ({{ $categoria->esIngreso() ? 'Ingreso' : 'Gasto' }})
                        </option>
                        @endforeach
                    </select>
                    @error('categoria_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Monto</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-slate-500">$</span>
                        <input type="number" name="monto" value="{{ old('monto', $transaccion->monto) }}" step="0.01" min="0.01" placeholder="0.00" class="w-full pl-8 pr-3 py-2 border border-slate-200 rounded-lg" required>
                    </div>
                    @error('monto') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Fecha</label>
                    <input type="date" name="fecha" value="{{ old('fecha', $transaccion->fecha->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg" required>
                    @error('fecha') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Método de Pago</label>
                    <select name="metodo_pago" class="w-full px-3 py-2 border border-slate-200 rounded-lg" required>
                        <option value="">Seleccionar método</option>
                        <option value="efectivo" {{ old('metodo_pago', $transaccion->metodo_pago) == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="transferencia" {{ old('metodo_pago', $transaccion->metodo_pago) == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        <option value="electronico" {{ old('metodo_pago', $transaccion->metodo_pago) == 'electronico' ? 'selected' : '' }}>Electrónico</option>
                    </select>
                    @error('metodo_pago') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Descripción</label>
                <textarea name="descripcion" rows="3" placeholder="Descripción opcional..." class="w-full px-3 py-2 border border-slate-200 rounded-lg">{{ old('descripcion', $transaccion->descripcion) }}</textarea>
                @error('descripcion') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Estado</label>
                <select name="estado" class="w-full px-3 py-2 border border-slate-200 rounded-lg">
                    <option value="confirmado" {{ old('estado', $transaccion->estado) == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                    <option value="pendiente" {{ old('estado', $transaccion->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="anulado" {{ old('estado', $transaccion->estado) == 'anulado' ? 'selected' : '' }}>Anulado</option>
                </select>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-colors">
                    Actualizar Transacción
                </button>
                <a href="{{ route('transacciones.show', $transaccion) }}" class="px-6 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoRadios = document.querySelectorAll('input[name="tipo"]');
    const categoriaSelect = document.querySelector('select[name="categoria_id"]');

    function filtrarCategorias() {
        const tipoSeleccionado = document.querySelector('input[name="tipo"]:checked')?.value;
        const opciones = categoriaSelect.querySelectorAll('option');

        opciones.forEach(opt => {
            if (opt.value === '') return;
            const tipoCategoria = opt.dataset.tipo;
            if (tipoSeleccionado && tipoCategoria !== tipoSeleccionado) {
                opt.style.display = 'none';
            } else {
                opt.style.display = '';
            }
        });

        if (categoriaSelect.value) {
            const selected = categoriaSelect.querySelector('option[value="' + categoriaSelect.value + '"]');
            if (selected && selected.style.display === 'none') {
                categoriaSelect.value = '';
            }
        }
    }

    tipoRadios.forEach(radio => {
        radio.addEventListener('change', filtrarCategorias);
    });

    if (document.querySelector('input[name="tipo"]:checked')) {
        filtrarCategorias();
    }
});
</script>
@endsection
