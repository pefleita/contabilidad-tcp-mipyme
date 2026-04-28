@extends('layouts.app')

@section('title', 'Nueva Categoría')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('categorias.index') }}" class="p-2 text-slate-400 hover:text-slate-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Nueva Categoría</h3>
            <p class="text-slate-500">Crea una categoría para clasificar tus transacciones</p>
        </div>
    </div>

    <form method="POST" action="{{ route('categorias.store') }}" class="space-y-6">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('nombre') border-red-500 @enderror">
                    @error('nombre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipo *</label>
                    <select name="tipo" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('tipo') border-red-500 @enderror">
                        <option value="">Seleccionar...</option>
                        <option value="ingreso" {{ old('tipo') === 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                        <option value="gasto" {{ old('tipo') === 'gasto' ? 'selected' : '' }}>Gasto</option>
                    </select>
                    @error('tipo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Color</label>
                    <div class="flex gap-3 flex-wrap">
                        @php $colors = ['emerald' => '#10b981', 'blue' => '#3b82f6', 'rose' => '#f43f5e', 'amber' => '#f59e0b', 'purple' => '#8b5cf6', 'slate' => '#64748b'] @endphp
                        @foreach($colors as $name => $hex)
                        <label class="cursor-pointer">
                            <input type="radio" name="color" value="{{ $name }}" {{ old('color', 'emerald') === $name ? 'checked' : '' }} class="sr-only">
                            <span class="w-8 h-8 rounded-full border-2 border-transparent hover:border-slate-400 block" style="background-color: {{ $hex }}"></span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('categorias.index') }}" class="px-6 py-2 text-slate-600 hover:text-slate-800">Cancelar</a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">Guardar</button>
        </div>
    </form>
</div>
@endsection