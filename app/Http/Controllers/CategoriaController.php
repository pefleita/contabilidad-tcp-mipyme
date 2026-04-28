<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoriaController extends Controller
{
    public function index(Request $request): View
    {
        $empresaId = $request->user()->empresa?->id ?? null;
        
        $categorias = Categoria::when($empresaId, function ($query) use ($empresaId) {
            $query->where('empresa_id', $empresaId);
        })
        ->when($request->tipo, function ($query) use ($request) {
            $query->where('tipo', $request->tipo);
        })
        ->orderBy('tipo')
        ->orderBy('nombre')
        ->paginate(15);

        return view('categorias.index', compact('categorias'));
    }

    public function create(): View
    {
        return view('categorias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'required|in:ingreso,gasto',
            'color' => 'nullable|string|max:20',
            'icono' => 'nullable|string|max:50',
        ]);

        $request->user()->empresa?->categorias()->create($validated);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada correctamente');
    }

    public function edit(Categoria $categoria): View
    {
        $this->authorizeCategoria($categoria);
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $this->authorizeCategoria($categoria);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'required|in:ingreso,gasto',
            'color' => 'nullable|string|max:20',
            'icono' => 'nullable|string|max:50',
            'es_activo' => 'nullable|boolean',
        ]);

        $categoria->update($validated);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        $this->authorizeCategoria($categoria);

        if ($categoria->transacciones()->count() > 0) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene transacciones asociadas');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente');
    }

    private function authorizeCategoria(Categoria $categoria): void
    {
        $empresaId = auth()->user()->empresa?->id;
        
        if ($categoria->empresa_id !== $empresaId) {
            abort(403, 'No tienes acceso a esta categoría');
        }
    }
}