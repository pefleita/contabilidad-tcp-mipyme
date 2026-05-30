<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ActivoFijoController extends Controller
{
    public function index(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return view('activos.index', [
                'activos' => collect(),
                'sinEmpresa' => true,
            ]);
        }

        $activos = ActivoFijo::where('empresa_id', $empresa->id)
            ->when($request->tipo, function ($q) use ($request) {
                $q->where('tipo', $request->tipo);
            })
            ->when($request->has('esta_activo') && $request->esta_activo !== '', function ($q) use ($request) {
                $q->where('esta_activo', $request->esta_activo === '1');
            })
            ->when($request->busqueda, function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('nombre', 'like', "%{$request->busqueda}%")
                      ->orWhere('codigo', 'like', "%{$request->busqueda}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('activos.index', compact('activos'));
    }

    public function create()
    {
        if (!auth()->user()->empresa) {
            return redirect()->route('empresa.index')
                ->with('warning', 'Primero debes registrar los datos de tu empresa.');
        }

        return view('activos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return redirect()->route('empresa.index')
                ->with('warning', 'Primero debes registrar los datos de tu empresa.');
        }

        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:activos_fijos,codigo,NULL,id,empresa_id,' . $empresa->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:mueble,inmueble,vehiculo,equipo,otro',
            'costo_original' => 'required|numeric|min:0',
            'valor_residual' => 'nullable|numeric|min:0',
            'vida_util_anos' => 'required|integer|min:1',
            'fecha_adquisicion' => 'required|date',
            'fecha_inicio_depreciacion' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $validated['valor_residual'] = $validated['valor_residual'] ?? 0;
        $validated['empresa_id'] = $empresa->id;

        ActivoFijo::create($validated);

        return redirect()->route('activos.index')
            ->with('success', 'Activo fijo registrado correctamente.');
    }

    public function show(ActivoFijo $activo): View
    {
        $this->authorizeEmpresa($activo);

        return view('activos.show', compact('activo'));
    }

    public function edit(ActivoFijo $activo): View
    {
        $this->authorizeEmpresa($activo);

        return view('activos.edit', compact('activo'));
    }

    public function update(Request $request, ActivoFijo $activo): RedirectResponse
    {
        $this->authorizeEmpresa($activo);

        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:activos_fijos,codigo,' . $activo->id . ',id,empresa_id,' . $activo->empresa_id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:mueble,inmueble,vehiculo,equipo,otro',
            'costo_original' => 'required|numeric|min:0',
            'valor_residual' => 'nullable|numeric|min:0',
            'vida_util_anos' => 'required|integer|min:1',
            'fecha_adquisicion' => 'required|date',
            'fecha_inicio_depreciacion' => 'required|date',
            'esta_activo' => 'nullable|boolean',
            'observaciones' => 'nullable|string',
        ]);

        $validated['valor_residual'] = $validated['valor_residual'] ?? 0;
        $validated['esta_activo'] = $request->has('esta_activo');

        $activo->update($validated);

        return redirect()->route('activos.index')
            ->with('success', 'Activo fijo actualizado correctamente.');
    }

    public function destroy(ActivoFijo $activo): RedirectResponse
    {
        $this->authorizeEmpresa($activo);

        $activo->delete();

        return redirect()->route('activos.index')
            ->with('success', 'Activo fijo eliminado.');
    }

    public function depreciacion(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return view('activos.depreciacion', [
                'activos' => collect(),
                'sinEmpresa' => true,
            ]);
        }

        $activos = ActivoFijo::where('empresa_id', $empresa->id)
            ->where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        return view('activos.depreciacion', compact('activos'));
    }

    public function libroActivos(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return view('activos.libro', [
                'activos' => collect(),
                'sinEmpresa' => true,
            ]);
        }

        $activos = ActivoFijo::where('empresa_id', $empresa->id)
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        $totalesPorTipo = $activos->groupBy('tipo')->map(function ($items) {
            return [
                'cantidad' => $items->count(),
                'costo' => $items->sum('costo_original'),
                'depreciacion' => $items->sum(function ($a) { return $a->calcularDepreciacionAcumulada(); }),
                'valor_neto' => $items->sum(function ($a) { return $a->valorNeto(); }),
            ];
        });

        return view('activos.libro', compact('activos', 'totalesPorTipo'));
    }

    private function authorizeEmpresa(ActivoFijo $activo): void
    {
        $empresa = auth()->user()->empresa;

        if (!$empresa || $activo->empresa_id !== $empresa->id) {
            abort(403);
        }
    }
}
