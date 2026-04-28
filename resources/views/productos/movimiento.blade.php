@extends('layouts.app')

@section('title', 'Movimiento de Inventario')

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
            <h3 class="text-2xl font-bold text-slate-800">Movimiento de Inventario</h3>
            <p class="text-slate-500">Registrar entrada, salida o ajuste de inventario</p>
        </div>
    </div>

    @if($productos->isEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
        <p class="text-sm text-amber-800">No hay productos disponibles para registrar movimientos. Crea primero un producto.</p>
    </div>
    @else
    <!-- Form -->
    <form method="POST" action="{{ route('productos.guardarMovimiento') }}" class="space-y-6">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">Tipo de Movimiento</h4>
            
            <div class="grid grid-cols-3 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="tipo_movimiento" value="entrada" class="peer sr-only" onchange="toggleOrigen()">
                    <div class="p-4 border-2 border-slate-200 rounded-xl text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="font-medium text-slate-700">Entrada</span>
                    </div>
                </label>
                
                <label class="cursor-pointer">
                    <input type="radio" name="tipo_movimiento" value="salida" class="peer sr-only" onchange="toggleOrigen()">
                    <div class="p-4 border-2 border-slate-200 rounded-xl text-center peer-checked:border-rose-500 peer-checked:bg-rose-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                        <span class="font-medium text-slate-700">Salida</span>
                    </div>
                </label>
                
                <label class="cursor-pointer">
                    <input type="radio" name="tipo_movimiento" value="ajuste" class="peer sr-only" onchange="toggleOrigen()">
                    <div class="p-4 border-2 border-slate-200 rounded-xl text-center peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="font-medium text-slate-700">Ajuste</span>
                    </div>
                </label>
            </div>
            @error('tipo_movimiento')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">Detalles del Movimiento</h4>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Producto *</label>
                    <select name="producto_id" id="producto_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Seleccionar producto...</option>
                        @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" data-existencia="{{ $producto->existencias }}">
                            {{ $producto->codigo }} - {{ $producto->nombre }} (Exist: {{ $producto->existencias }} {{ $producto->unidad_medida }})
                        </option>
                        @endforeach
                    </select>
                    @error('producto_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div id="origen-container">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Origen *</label>
                    <select name="tipo_origen" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Seleccionar...</option>
                        <option value="compra">Compra</option>
                        <option value="produccion">Producción</option>
                        <option value="devolucion">Devolución</option>
                    </select>
                    @error('tipo_origen')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Cantidad *</label>
                        <input type="number" name="cantidad" id="cantidad" required min="0.01" step="0.01" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @error('cantidad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Precio Unitario *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                            <input type="number" name="precio_unitario" id="precio_unitario" required min="0" step="0.01" class="w-full pl-8 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        @error('precio_unitario')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Fecha *</label>
                        <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @error('fecha')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Referencia</label>
                        <input type="text" name="referencia" placeholder="Nro. factura, orden, etc." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Notas</label>
                    <textarea name="notas" rows="2" placeholder="Observaciones adicionales..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('productos.index') }}" class="px-6 py-2 text-slate-600 hover:text-slate-800">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                Registrar Movimiento
            </button>
        </div>
    </form>
    @endif
</div>

<script>
function toggleOrigen() {
    const tipo = document.querySelector('input[name="tipo_movimiento"]:checked')?.value;
    const origenContainer = document.getElementById('origen-container');
    const origenSelect = origenContainer.querySelector('select');
    
    if (tipo === 'ajuste') {
        origenContainer.style.display = 'none';
        origenSelect.required = false;
        origenSelect.value = '';
    } else {
        origenContainer.style.display = 'block';
        origenSelect.required = true;
    }
}

document.getElementById('producto_id').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const existencia = option.getAttribute('data-existencia');
    document.getElementById('cantidad').max = existencia || '';
});
</script>
@endsection