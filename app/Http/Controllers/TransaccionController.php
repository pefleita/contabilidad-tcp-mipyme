<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use App\Models\Categoria;
use App\Models\Comprobante;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class TransaccionController extends Controller
{
    public function index(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return view('transacciones.index', [
                'transacciones' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15),
                'sinEmpresa' => true,
                'totalIngresos' => 0,
                'totalGastos' => 0,
                'balance' => 0,
            ]);
        }

        $query = Transaccion::where('empresa_id', $empresa->id);

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('busqueda')) {
            $query->where(function ($q) use ($request) {
                $q->where('descripcion', 'like', "%{$request->busqueda}%")
                  ->orWhere('monto', 'like', "%{$request->busqueda}%");
            });
        }

        $transacciones = $query->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalIngresos = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')
            ->where('estado', '!=', 'anulado')
            ->sum('monto');

        $totalGastos = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')
            ->where('estado', '!=', 'anulado')
            ->sum('monto');

        $balance = $totalIngresos - $totalGastos;

        $categorias = Categoria::where('empresa_id', $empresa->id)
            ->where('es_activo', true)
            ->orderBy('nombre')
            ->get();

        return view('transacciones.index', compact(
            'transacciones', 'totalIngresos', 'totalGastos', 'balance', 'categorias'
        ));
    }

    public function create(): View
    {
        $empresa = auth()->user()->empresa;

        if (!$empresa) {
            return redirect()->route('empresa.index')
                ->with('warning', 'Primero debes registrar los datos de tu empresa.');
        }

        $categorias = Categoria::where('empresa_id', $empresa->id)
            ->where('es_activo', true)
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return view('transacciones.create', compact('categorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $empresa = $request->user()->empresa;

        $validated = $request->validate([
            'tipo' => 'required|in:ingreso,gasto',
            'categoria_id' => 'required|exists:categorias,id',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'metodo_pago' => 'required|in:efectivo,transferencia,electronico',
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'nullable|in:confirmado,pendiente,anulado',
        ]);

        $categoria = Categoria::findOrFail($validated['categoria_id']);

        if ($categoria->tipo !== $validated['tipo']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La categoría seleccionada no coincide con el tipo de transacción.');
        }

        $validated['empresa_id'] = $empresa->id;
        $validated['estado'] = $validated['estado'] ?? 'confirmado';

        $transaccion = Transaccion::create($validated);

        return redirect()->route('transacciones.show', $transaccion)
            ->with('success', 'Transacción registrada correctamente.');
    }

    public function show(Transaccion $transaccion): View
    {
        $this->authorizeTransaccion($transaccion);

        $comprobantes = $transaccion->comprobantes()->orderBy('created_at', 'desc')->get();

        return view('transacciones.show', compact('transaccion', 'comprobantes'));
    }

    public function edit(Transaccion $transaccion): View
    {
        $this->authorizeTransaccion($transaccion);

        $empresa = auth()->user()->empresa;

        $categorias = Categoria::where('empresa_id', $empresa->id)
            ->where('es_activo', true)
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return view('transacciones.edit', compact('transaccion', 'categorias'));
    }

    public function update(Request $request, Transaccion $transaccion): RedirectResponse
    {
        $this->authorizeTransaccion($transaccion);

        $validated = $request->validate([
            'tipo' => 'required|in:ingreso,gasto',
            'categoria_id' => 'required|exists:categorias,id',
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'metodo_pago' => 'required|in:efectivo,transferencia,electronico',
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'nullable|in:confirmado,pendiente,anulado',
        ]);

        $categoria = Categoria::findOrFail($validated['categoria_id']);

        if ($categoria->tipo !== $validated['tipo']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La categoría seleccionada no coincide con el tipo de transacción.');
        }

        $validated['estado'] = $validated['estado'] ?? 'confirmado';
        $transaccion->update($validated);

        return redirect()->route('transacciones.show', $transaccion)
            ->with('success', 'Transacción actualizada correctamente.');
    }

    public function destroy(Transaccion $transaccion): RedirectResponse
    {
        $this->authorizeTransaccion($transaccion);

        foreach ($transaccion->comprobantes as $comprobante) {
            Storage::disk('public')->delete($comprobante->archivo);
            $comprobante->delete();
        }

        $transaccion->delete();

        return redirect()->route('transacciones.index')
            ->with('success', 'Transacción eliminada correctamente.');
    }

    public function uploadComprobante(Request $request, Transaccion $transaccion): RedirectResponse
    {
        $this->authorizeTransaccion($transaccion);

        $validated = $request->validate([
            'archivo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'tipo' => 'required|in:factura,recibo,otro',
        ]);

        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();

        $ruta = $archivo->store('comprobantes/' . now()->format('Y') . '/' . now()->format('m'), 'public');

        Comprobante::create([
            'transaccion_id' => $transaccion->id,
            'archivo' => $ruta,
            'nombre_original' => $nombreOriginal,
            'tipo' => $validated['tipo'],
            'tamano' => $archivo->getSize(),
        ]);

        return redirect()->route('transacciones.show', $transaccion)
            ->with('success', 'Comprobante adjuntado correctamente.');
    }

    public function deleteComprobante(Transaccion $transaccion, Comprobante $comprobante): RedirectResponse
    {
        $this->authorizeTransaccion($transaccion);

        Storage::disk('public')->delete($comprobante->archivo);
        $comprobante->delete();

        return redirect()->route('transacciones.show', $transaccion)
            ->with('success', 'Comprobante eliminado correctamente.');
    }

    private function authorizeTransaccion(Transaccion $transaccion): void
    {
        $empresaId = auth()->user()->empresa?->id;

        if ($transaccion->empresa_id !== $empresaId) {
            abort(403, 'No tienes acceso a esta transacción');
        }
    }
}
