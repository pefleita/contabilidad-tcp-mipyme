<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmpresaController extends Controller
{
    public function index(Request $request): View
    {
        $empresa = $request->user()->empresa;
        
        if (!$empresa) {
            return view('empresa.create');
        }
        
        return view('empresa.show', compact('empresa'));
    }

    public function edit(Request $request): View
    {
        $empresa = $request->user()->empresa;
        
        if (!$empresa) {
            return view('empresa.create');
        }
        
        $this->authorizeEdit($empresa);
        
        return view('empresa.edit', compact('empresa'));
    }

    public function update(Request $request): RedirectResponse
    {
        $empresa = $request->user()->empresa;
        
        if (!$empresa) {
            return redirect()->route('empresa.index')
                ->with('error', 'No tienes una empresa registrada');
        }
        
        $this->authorizeEdit($empresa);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'required|string|max:50|unique:empresas,nit,' . $empresa->id,
            'actividad_economica' => 'required|string|max:255',
            'tipo_contabilidad' => 'required|in:simplificada,formal',
            'logo' => 'nullable|string|max:255',
        ]);
        
        $empresa->update($validated);
        
        return redirect()->route('empresa.index')
            ->with('success', 'Datos de la empresa actualizados correctamente');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'required|string|max:50|unique:empresas,nit',
            'actividad_economica' => 'required|string|max:255',
            'tipo_contabilidad' => 'required|in:simplificada,formal',
            'logo' => 'nullable|string|max:255',
        ]);
        
        $validated['user_id'] = $request->user()->id;
        
        $empresa = Empresa::create($validated);
        
        return redirect()->route('empresa.index')
            ->with('success', 'Empresa creada correctamente');
    }

    private function authorizeEdit(Empresa $empresa): void
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Solo el administrador puede modificar los datos de la empresa');
        }
    }
}