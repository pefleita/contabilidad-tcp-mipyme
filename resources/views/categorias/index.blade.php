@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Categorías</h3>
            <p class="text-slate-500">Administra las categorías de tus transacciones</p>
        </div>
        <a href="{{ route('categorias.create') }}" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nueva Categoría
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="w-48">
                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                <select name="tipo" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Todos</option>
                    <option value="ingreso" {{ request('tipo') == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                    <option value="gasto" {{ request('tipo') == 'gasto' ? 'selected' : '' }}>Gasto</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">
                Filtrar
            </button>
            <a href="{{ route('categorias.index') }}" class="px-4 py-2 text-slate-600 hover:text-slate-800 inline-flex items-center">
                Limpiar
            </a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Color</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($categorias as $categoria)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm text-slate-800 font-medium">{{ $categoria->nombre }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $categoria->tipo === 'ingreso' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ $categoria->tipo === 'ingreso' ? 'Ingreso' : 'Gasto' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="w-4 h-4 rounded-full inline-block" style="background-color: {{ $categoria->color }}"></span>
                        <span class="text-xs text-slate-500 ml-1">{{ $categoria->color }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($categoria->es_activo)
                        <span class="px-2 py-1 text-xs bg-emerald-100 text-emerald-700 rounded">Activa</span>
                        @else
                        <span class="px-2 py-1 text-xs bg-slate-100 text-slate-500 rounded">Inactiva</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('categorias.edit', $categoria) }}" class="p-1 text-slate-400 hover:text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        No hay categorías registradas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($categorias->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $categorias->links() }}
        </div>
        @endif
    </div>
</div>
@endsection