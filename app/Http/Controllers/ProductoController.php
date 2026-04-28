<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Kardex;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductoController extends Controller
{
    public function index(Request $request): View
    {
        $empresaId = $request->user()->empresa?->id ?? null;
        
        $productos = Producto::when($empresaId, function ($query) use ($empresaId) {
            $query->where('empresa_id', $empresaId);
        })
        ->when($request->categoria, function ($query) use ($request) {
            $query->where('categoria_producto', $request->categoria);
        })
        ->when($request->estado !== null && $request->estado !== '', function ($query) use ($request) {
            $query->where('esta_activo', $request->estado === '1');
        })
        ->when($request->busqueda, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->busqueda}%")
                  ->orWhere('codigo', 'like', "%{$request->busqueda}%");
            });
        })
        ->orderBy('nombre')
        ->paginate(15);

        return view('productos.index', compact('productos'));
    }

    public function create(): View
    {
        return view('productos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:productos,codigo,NULL,id,empresa_id,' . $request->user()->empresa?->id,
            'nombre' => 'required|string|max:255',
            'categoria_producto' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:20',
            'precio_costo' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'existencias' => 'nullable|numeric|min:0',
            'stock_minimo' => 'nullable|numeric|min:0',
            'es_servicio' => 'nullable|boolean',
        ]);

        $request->user()->empresa?->productos()->create($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente');
    }

    public function show(Producto $producto): View
    {
        $this->authorizeProducto($producto);
        
        $kardex = $producto->kardex()->orderBy('fecha', 'desc')->limit(50)->get();
        
        return view('productos.show', compact('producto', 'kardex'));
    }

    public function edit(Producto $producto): View
    {
        $this->authorizeProducto($producto);
        
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $this->authorizeProducto($producto);

        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:productos,codigo,' . $producto->id . ',id,empresa_id,' . $producto->empresa_id,
            'nombre' => 'required|string|max:255',
            'categoria_producto' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:20',
            'precio_costo' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'existencias' => 'nullable|numeric|min:0',
            'stock_minimo' => 'nullable|numeric|min:0',
            'es_servicio' => 'nullable|boolean',
            'esta_activo' => 'nullable|boolean',
        ]);

        $producto->update($validated);

        return redirect()->route('productos.show', $producto)
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $this->authorizeProducto($producto);

        if ($producto->kardex()->exists()) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede eliminar el producto porque tiene movimientos de inventario');
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente');
    }

    public function importarExcel(Request $request): RedirectResponse
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        // Aquí se implementaría la lógica de importación desde Excel
        // usando библиотека como PhpSpreadsheet
        
        return redirect()->route('productos.index')
            ->with('success', 'Importación de productos iniciada');
    }

    public function movimiento(Request $request): View
    {
        $empresaId = $request->user()->empresa?->id ?? null;
        
        $productos = Producto::when($empresaId, function ($query) use ($empresaId) {
            $query->where('empresa_id', $empresaId);
        })
        ->where('esta_activo', true)
        ->where('es_servicio', false)
        ->orderBy('nombre')
        ->get();

        return view('productos.movimiento', compact('productos'));
    }

    public function guardarMovimiento(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo_movimiento' => 'required|in:entrada,salida,ajuste',
            'tipo_origen' => 'required_if:tipo_movimiento,entrada,salida|nullable|in:compra,venta,devolucion,produccion',
            'cantidad' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'referencia' => 'nullable|string|max:100',
            'notas' => 'nullable|string|max:500',
        ]);

        $producto = Producto::findOrFail($validated['producto_id']);
        $this->authorizeProducto($producto);

        if ($producto->es_servicio) {
            return redirect()->route('productos.movimiento')
                ->with('error', 'No se puede registrar movimiento para un servicio');
        }

        $existenciasAnterior = $producto->existencias;
        $cantidad = $validated['cantidad'];
        
        if ($validated['tipo_movimiento'] === 'salida') {
            if ($existenciasAnterior < $cantidad) {
                return redirect()->route('productos.movimiento')
                    ->with('error', 'No hay suficientes existencias. Actual: ' . $existenciasAnterior);
            }
            $existenciasNueva = $existenciasAnterior - $cantidad;
        } elseif ($validated['tipo_movimiento'] === 'entrada') {
            $existenciasNueva = $existenciasAnterior + $cantidad;
        } else {
            $existenciasNueva = $cantidad;
        }

        $kardex = Kardex::create([
            'producto_id' => $producto->id,
            'tipo_movimiento' => $validated['tipo_movimiento'],
            'tipo_origen' => $validated['tipo_origen'] ?? null,
            'cantidad' => $cantidad,
            'precio_unitario' => $validated['precio_unitario'],
            'costo_total' => $cantidad * $validated['precio_unitario'],
            'existencias_anterior' => $existenciasAnterior,
            'existencias_nueva' => $existenciasNueva,
            'fecha' => $validated['fecha'],
            'referencia' => $validated['referencia'],
            'notas' => $validated['notas'],
        ]);

        $producto->update(['existencias' => $existenciasNueva]);

        $tipoLabel = $validated['tipo_movimiento'] === 'entrada' ? 'entrada' : ($validated['tipo_movimiento'] === 'salida' ? 'salida' : 'ajuste');
        
        return redirect()->route('productos.show', $producto)
            ->with('success', ucfirst($tipoLabel) . ' registrada correctamente. Existencias: ' . $existenciasNueva);
    }

    public function reporteInventario(Request $request): View
    {
        $empresaId = $request->user()->empresa?->id ?? null;
        
        $productos = Producto::when($empresaId, function ($query) use ($empresaId) {
            $query->where('empresa_id', $empresaId);
        })
        ->where('esta_activo', true)
        ->where('es_servicio', false)
        ->orderBy('nombre')
        ->get();

        $totalCantidad = $productos->sum('existencias');
        $totalValor = $productos->sum(function ($p) {
            return $p->existencias * $p->precio_costo;
        });
        $productosBajoStock = $productos->filter(function ($p) {
            return $p->estaBajoStock();
        });

        return view('productos.reporte', compact('productos', 'totalCantidad', 'totalValor', 'productosBajoStock'));
    }

    private function authorizeProducto(Producto $producto): void
    {
        $empresaId = auth()->user()->empresa?->id;
        
        if ($producto->empresa_id !== $empresaId) {
            abort(403, 'No tienes acceso a este producto');
        }
    }
}