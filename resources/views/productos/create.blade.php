@extends('layouts.app')

@section('title', 'Nuevo Producto')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('productos.index') }}" class="p-2 text-slate-400 hover:text-slate-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Nuevo Producto</h3>
            <p class="text-slate-500">Agrega un nuevo producto al inventario</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('productos.store') }}" class="space-y-6">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">Información Basic</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Código *</label>
                    <input type="text" name="codigo" value="{{ old('codigo') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('codigo') border-red-500 @enderror">
                    @error('codigo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('descripcion') }}</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Categoría</label>
                    <input type="text" name="categoria_producto" value="{{ old('categoria_producto') }}" placeholder="Ej: Electrónica, Oficina" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Unidad de Medida</label>
                    <select name="unidad_medida" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="und" {{ old('unidad_medida') == 'und' ? 'selected' : '' }}>Unidad</option>
                        <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>Kilogramo</option>
                        <option value="lt" {{ old('unidad_medida') == 'lt' ? 'selected' : '' }}>Litro</option>
                        <option value="mt" {{ old('unidad_medida') == 'mt' ? 'selected' : '' }}>Metro</option>
                        <option value="pza" {{ old('unidad_medida') == 'pza' ? 'selected' : '' }}>Pieza</option>
                        <option value="paq" {{ old('unidad_medida') == 'paq' ? 'selected' : '' }}>Paquete</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">Precios y Stock</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Precio Costo *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                        <input type="number" name="precio_costo" value="{{ old('precio_costo', 0) }}" required min="0" step="0.01" class="w-full pl-8 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('precio_costo') border-red-500 @enderror">
                    </div>
                    @error('precio_costo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Precio Venta *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                        <input type="number" name="precio_venta" value="{{ old('precio_venta', 0) }}" required min="0" step="0.01" class="w-full pl-8 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('precio_venta') border-red-500 @enderror">
                    </div>
                    @error('precio_venta')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Existencias Iniciales</label>
                    <input type="number" name="existencias" value="{{ old('existencias', 0) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Stock Mínimo</label>
                    <input type="number" name="stock_minimo" value="{{ old('stock_minimo', 0) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <p class="mt-1 text-xs text-slate-500">Alerta cuando las existencias bajen de este nivel</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3">
                <input type="checkbox" name="es_servicio" id="es_servicio" value="1" {{ old('es_servicio') ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                <label for="es_servicio" class="text-sm font-medium text-slate-700">Es un servicio (no maneja inventario)</label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('productos.index') }}" class="px-6 py-2 text-slate-600 hover:text-slate-800">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                Guardar Producto
            </button>
        </div>
    </form>
</div>
@endsection