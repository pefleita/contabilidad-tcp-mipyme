<?php

namespace App\Http\Controllers;

use App\Models\AsientoContable;
use App\Models\CuentaContable;
use App\Models\PartidaAsiento;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ContabilidadController extends Controller
{
    public function index(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa || !$empresa->esContabilidadFormal()) {
            return view('contabilidad.index', [
                'asientos' => collect(),
                'sinEmpresa' => true,
                'esFormal' => false,
            ]);
        }

        $asientos = AsientoContable::where('empresa_id', $empresa->id)
            ->with('partidas')
            ->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('contabilidad.index', [
            'asientos' => $asientos,
            'sinEmpresa' => false,
            'esFormal' => true,
        ]);
    }

    public function create(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa || !$empresa->esContabilidadFormal()) {
            abort(403, 'Contabilidad formal no habilitada para esta empresa.');
        }

        $cuentas = CuentaContable::where('empresa_id', $empresa->id)
            ->where('es_movimiento', true)
            ->orderBy('codigo')
            ->get();

        $proximoNumero = $this->proximoNumeroAsiento($empresa->id);

        return view('contabilidad.create', compact('cuentas', 'proximoNumero'));
    }

    public function store(Request $request): RedirectResponse
    {
        $empresa = $request->user()->empresa;

        if (!$empresa || !$empresa->esContabilidadFormal()) {
            abort(403);
        }

        $validated = $request->validate([
            'fecha' => 'required|date',
            'descripcion' => 'required|string|max:500',
            'numero_asiento' => 'required|string|max:20|unique:asientos_contables,numero_asiento',
            'partidas' => 'required|array|min:2',
            'partidas.*.cuenta_id' => 'required|exists:cuentas_contables,id',
            'partidas.*.debe' => 'required_without:partidas.*.haber|numeric|min:0',
            'partidas.*.haber' => 'required_without:partidas.*.debe|numeric|min:0',
            'partidas.*.descripcion' => 'nullable|string|max:255',
        ]);

        $totalDebe = 0;
        $totalHaber = 0;

        foreach ($validated['partidas'] as $partida) {
            $debe = (float) ($partida['debe'] ?? 0);
            $haber = (float) ($partida['haber'] ?? 0);

            if ($debe > 0 && $haber > 0) {
                return back()->withErrors(['partidas' => 'Una partida no puede tener debe y haber simultáneamente.'])->withInput();
            }

            if ($debe == 0 && $haber == 0) {
                return back()->withErrors(['partidas' => 'Cada partida debe tener un valor en debe o haber.'])->withInput();
            }

            $totalDebe += $debe;
            $totalHaber += $haber;
        }

        if (bccomp((string) $totalDebe, (string) $totalHaber, 2) !== 0) {
            return back()->withErrors(['partidas' => 'La suma del debe (' . number_format($totalDebe, 2) . ') debe ser igual a la suma del haber (' . number_format($totalHaber, 2) . ').'])->withInput();
        }

        $asiento = DB::transaction(function () use ($empresa, $validated) {
            $asiento = AsientoContable::create([
                'empresa_id' => $empresa->id,
                'fecha' => $validated['fecha'],
                'descripcion' => $validated['descripcion'],
                'numero_asiento' => $validated['numero_asiento'],
                'estado' => 'confirmado',
            ]);

            foreach ($validated['partidas'] as $partida) {
                $asiento->partidas()->create([
                    'cuenta_id' => $partida['cuenta_id'],
                    'debe' => $partida['debe'] ?? 0,
                    'haber' => $partida['haber'] ?? 0,
                    'descripcion' => $partida['descripcion'] ?? null,
                ]);
            }

            return $asiento;
        });

        return redirect()->route('contabilidad.show', $asiento)
            ->with('success', 'Asiento contable creado y confirmado.');
    }

    public function show(Request $request, AsientoContable $asiento): View
    {
        $empresa = $request->user()->empresa;

        if ($asiento->empresa_id !== $empresa->id) {
            abort(403);
        }

        $asiento->load('partidas.cuenta');

        return view('contabilidad.show', compact('asiento'));
    }

    public function edit(Request $request, AsientoContable $asiento): View
    {
        $empresa = $request->user()->empresa;

        if ($asiento->empresa_id !== $empresa->id) {
            abort(403);
        }

        if ($asiento->estado !== 'borrador') {
            abort(403, 'Solo se pueden editar asientos en estado borrador.');
        }

        $cuentas = CuentaContable::where('empresa_id', $empresa->id)
            ->where('es_movimiento', true)
            ->orderBy('codigo')
            ->get();

        $asiento->load('partidas');

        return view('contabilidad.edit', compact('asiento', 'cuentas'));
    }

    public function update(Request $request, AsientoContable $asiento): RedirectResponse
    {
        $empresa = $request->user()->empresa;

        if ($asiento->empresa_id !== $empresa->id) {
            abort(403);
        }

        if ($asiento->estado !== 'borrador') {
            return back()->withErrors(['error' => 'Solo se pueden editar asientos en estado borrador.']);
        }

        $validated = $request->validate([
            'fecha' => 'required|date',
            'descripcion' => 'required|string|max:500',
            'partidas' => 'required|array|min:2',
            'partidas.*.cuenta_id' => 'required|exists:cuentas_contables,id',
            'partidas.*.debe' => 'required_without:partidas.*.haber|numeric|min:0',
            'partidas.*.haber' => 'required_without:partidas.*.debe|numeric|min:0',
            'partidas.*.descripcion' => 'nullable|string|max:255',
        ]);

        $totalDebe = 0;
        $totalHaber = 0;

        foreach ($validated['partidas'] as $partida) {
            $debe = (float) ($partida['debe'] ?? 0);
            $haber = (float) ($partida['haber'] ?? 0);

            if ($debe > 0 && $haber > 0) {
                return back()->withErrors(['partidas' => 'Una partida no puede tener debe y haber simultáneamente.'])->withInput();
            }

            if ($debe == 0 && $haber == 0) {
                return back()->withErrors(['partidas' => 'Cada partida debe tener un valor en debe o haber.'])->withInput();
            }

            $totalDebe += $debe;
            $totalHaber += $haber;
        }

        if (bccomp((string) $totalDebe, (string) $totalHaber, 2) !== 0) {
            return back()->withErrors(['partidas' => 'La suma del debe debe ser igual a la suma del haber.'])->withInput();
        }

        DB::transaction(function () use ($asiento, $validated) {
            $asiento->update([
                'fecha' => $validated['fecha'],
                'descripcion' => $validated['descripcion'],
            ]);

            $asiento->partidas()->delete();

            foreach ($validated['partidas'] as $partida) {
                $asiento->partidas()->create([
                    'cuenta_id' => $partida['cuenta_id'],
                    'debe' => $partida['debe'] ?? 0,
                    'haber' => $partida['haber'] ?? 0,
                    'descripcion' => $partida['descripcion'] ?? null,
                ]);
            }
        });

        return redirect()->route('contabilidad.show', $asiento)
            ->with('success', 'Asiento contable actualizado.');
    }

    public function destroy(Request $request, AsientoContable $asiento): RedirectResponse
    {
        $empresa = $request->user()->empresa;

        if ($asiento->empresa_id !== $empresa->id) {
            abort(403);
        }

        if ($asiento->estado !== 'borrador') {
            return back()->withErrors(['error' => 'Solo se pueden eliminar asientos en estado borrador.']);
        }

        $asiento->partidas()->delete();
        $asiento->delete();

        return redirect()->route('contabilidad.index')
            ->with('success', 'Asiento contable eliminado.');
    }

    public function generarDesdeTransaccion(Request $request, Transaccion $transaccion): RedirectResponse
    {
        $empresa = $request->user()->empresa;

        if (!$empresa || !$empresa->esContabilidadFormal()) {
            abort(403);
        }

        if ($transaccion->empresa_id !== $empresa->id) {
            abort(403);
        }

        $asientoExistente = AsientoContable::where('descripcion', 'Transacción #' . $transaccion->id)->first();

        if ($asientoExistente) {
            return redirect()->route('contabilidad.show', $asientoExistente)
                ->with('info', 'Ya existe un asiento para esta transacción.');
        }

        $cuentaEfectivo = CuentaContable::where('empresa_id', $empresa->id)
            ->where('codigo', '1101')
            ->first();

        $cuentaIngreso = CuentaContable::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')
            ->where('es_movimiento', true)
            ->first();

        $cuentaGasto = CuentaContable::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')
            ->where('es_movimiento', true)
            ->first();

        if (!$cuentaEfectivo || (!$cuentaIngreso && !$cuentaGasto)) {
            return back()->withErrors(['error' => 'No se encontraron cuentas contables necesarias. Configure el plan de cuentas primero.']);
        }

        $monto = (float) $transaccion->monto;
        $numeroAsiento = $this->proximoNumeroAsiento($empresa->id);

        $asiento = DB::transaction(function () use ($empresa, $transaccion, $cuentaEfectivo, $cuentaIngreso, $cuentaGasto, $monto, $numeroAsiento) {
            $asiento = AsientoContable::create([
                'empresa_id' => $empresa->id,
                'fecha' => $transaccion->fecha,
                'descripcion' => 'Transacción #' . $transaccion->id . ': ' . $transaccion->descripcion,
                'numero_asiento' => $numeroAsiento,
                'estado' => 'confirmado',
            ]);

            if ($transaccion->esIngreso()) {
                $asiento->partidas()->createMany([
                    [
                        'cuenta_id' => $cuentaEfectivo->id,
                        'debe' => $monto,
                        'haber' => 0,
                        'descripcion' => $transaccion->descripcion,
                    ],
                    [
                        'cuenta_id' => $cuentaIngreso->id,
                        'debe' => 0,
                        'haber' => $monto,
                        'descripcion' => $transaccion->descripcion,
                    ],
                ]);
            } else {
                $asiento->partidas()->createMany([
                    [
                        'cuenta_id' => $cuentaGasto->id,
                        'debe' => $monto,
                        'haber' => 0,
                        'descripcion' => $transaccion->descripcion,
                    ],
                    [
                        'cuenta_id' => $cuentaEfectivo->id,
                        'debe' => 0,
                        'haber' => $monto,
                        'descripcion' => $transaccion->descripcion,
                    ],
                ]);
            }

            return $asiento;
        });

        return redirect()->route('contabilidad.show', $asiento)
            ->with('success', 'Asiento contable generado desde la transacción.');
    }

    public function verificarBalance(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa || !$empresa->esContabilidadFormal()) {
            abort(403);
        }

        $totalDebe = PartidaAsiento::whereHas('asiento', function ($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id)->where('estado', 'confirmado');
        })->sum('debe');

        $totalHaber = PartidaAsiento::whereHas('asiento', function ($q) use ($empresa) {
            $q->where('empresa_id', $empresa->id)->where('estado', 'confirmado');
        })->sum('haber');

        $cuadra = bccomp((string) $totalDebe, (string) $totalHaber, 2) === 0;

        return view('contabilidad.balance-comprobacion', compact('totalDebe', 'totalHaber', 'cuadra'));
    }

    public function libroDiario(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa || !$empresa->esContabilidadFormal()) {
            abort(403);
        }

        $query = AsientoContable::where('empresa_id', $empresa->id)
            ->with('partidas.cuenta')
            ->where('estado', 'confirmado');

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('numero_asiento')) {
            $query->where('numero_asiento', 'like', '%' . $request->numero_asiento . '%');
        }

        $asientos = $query->orderBy('fecha', 'asc')
            ->orderBy('numero_asiento', 'asc')
            ->paginate(30);

        return view('contabilidad.libro-diario', compact('asientos'));
    }

    public function libroMayor(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa || !$empresa->esContabilidadFormal()) {
            abort(403);
        }

        $cuentas = CuentaContable::where('empresa_id', $empresa->id)
            ->where('es_movimiento', true)
            ->orderBy('codigo')
            ->get();

        $cuentaId = $request->get('cuenta_id');
        $partidas = collect();
        $cuenta = null;
        $saldoAnterior = 0;
        $totalDebe = 0;
        $totalHaber = 0;

        if ($cuentaId) {
            $cuenta = CuentaContable::findOrFail($cuentaId);

            if ($cuenta->empresa_id !== $empresa->id) {
                abort(403);
            }

            $query = PartidaAsiento::where('cuenta_id', $cuentaId)
                ->whereHas('asiento', function ($q) use ($empresa) {
                    $q->where('empresa_id', $empresa->id)->where('estado', 'confirmado');
                })
                ->with('asiento');

            if ($request->filled('fecha_desde')) {
                $query->whereHas('asiento', function ($q) use ($request) {
                    $q->whereDate('fecha', '>=', $request->fecha_desde);
                });
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereHas('asiento', function ($q) use ($request) {
                    $q->whereDate('fecha', '<=', $request->fecha_hasta);
                });
            }

            $partidas = $query->orderBy('asiento_id')->get();
            $totalDebe = $partidas->sum('debe');
            $totalHaber = $partidas->sum('haber');

            $saldoAnteriorQuery = PartidaAsiento::where('cuenta_id', $cuentaId)
                ->whereHas('asiento', function ($q) use ($empresa, $request) {
                    $q->where('empresa_id', $empresa->id)->where('estado', 'confirmado');

                    if ($request->filled('fecha_desde')) {
                        $q->whereDate('fecha', '<', $request->fecha_desde);
                    }
                });

            $saldoAnterior = $saldoAnteriorQuery->sum('debe') - $saldoAnteriorQuery->sum('haber');
        }

        return view('contabilidad.libro-mayor', compact('cuentas', 'cuenta', 'partidas', 'saldoAnterior', 'totalDebe', 'totalHaber'));
    }

    public function estadoSituacion(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa || !$empresa->esContabilidadFormal()) {
            abort(403);
        }

        $cuentas = CuentaContable::where('empresa_id', $empresa->id)
            ->whereIn('tipo', ['activo', 'pasivo', 'patrimonio'])
            ->with(['hijos' => function ($q) {
                $q->with('hijos');
            }])
            ->whereNull('padre_id')
            ->orderBy('codigo')
            ->get();

        $totalActivo = 0;
        $totalPasivo = 0;
        $totalPatrimonio = 0;

        foreach ($cuentas as $cuenta) {
            $saldo = $this->calcularSaldoCuenta($cuenta, $empresa->id);

            if ($cuenta->tipo === 'activo') {
                $totalActivo += $saldo;
            } elseif ($cuenta->tipo === 'pasivo') {
                $totalPasivo += $saldo;
            } elseif ($cuenta->tipo === 'patrimonio') {
                $totalPatrimonio += $saldo;
            }
        }

        $cuadra = bccomp((string) $totalActivo, (string) ($totalPasivo + $totalPatrimonio), 2) === 0;

        return view('contabilidad.estado-situacion', compact('cuentas', 'totalActivo', 'totalPasivo', 'totalPatrimonio', 'cuadra'));
    }

    public function estadoRendimiento(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa || !$empresa->esContabilidadFormal()) {
            abort(403);
        }

        $cuentasIngreso = CuentaContable::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')
            ->with('hijos')
            ->whereNull('padre_id')
            ->orderBy('codigo')
            ->get();

        $cuentasGasto = CuentaContable::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')
            ->with('hijos')
            ->whereNull('padre_id')
            ->orderBy('codigo')
            ->get();

        $totalIngresos = 0;
        foreach ($cuentasIngreso as $cuenta) {
            $totalIngresos += $this->calcularSaldoCuenta($cuenta, $empresa->id);
        }

        $totalGastos = 0;
        foreach ($cuentasGasto as $cuenta) {
            $totalGastos += $this->calcularSaldoCuenta($cuenta, $empresa->id);
        }

        $resultado = $totalIngresos - $totalGastos;

        return view('contabilidad.estado-rendimiento', compact('cuentasIngreso', 'cuentasGasto', 'totalIngresos', 'totalGastos', 'resultado'));
    }

    private function proximoNumeroAsiento(int $empresaId): string
    {
        $ultimo = AsientoContable::where('empresa_id', $empresaId)
            ->orderBy('id', 'desc')
            ->value('numero_asiento');

        if (!$ultimo) {
            return 'AS-' . date('Y') . '-0001';
        }

        $partes = explode('-', $ultimo);
        $numero = (int) end($partes) + 1;

        return 'AS-' . date('Y') . '-' . str_pad((string) $numero, 4, '0', STR_PAD_LEFT);
    }

    private function calcularSaldoCuenta(CuentaContable $cuenta, int $empresaId): float
    {
        $saldo = 0;

        if ($cuenta->es_movimiento) {
            $totalDebe = PartidaAsiento::where('cuenta_id', $cuenta->id)
                ->whereHas('asiento', function ($q) use ($empresaId) {
                    $q->where('empresa_id', $empresaId)->where('estado', 'confirmado');
                })->sum('debe');

            $totalHaber = PartidaAsiento::where('cuenta_id', $cuenta->id)
                ->whereHas('asiento', function ($q) use ($empresaId) {
                    $q->where('empresa_id', $empresaId)->where('estado', 'confirmado');
                })->sum('haber');

            if (in_array($cuenta->tipo, ['activo', 'gasto'])) {
                $saldo = (float) $totalDebe - (float) $totalHaber;
            } else {
                $saldo = (float) $totalHaber - (float) $totalDebe;
            }
        }

        foreach ($cuenta->hijos as $hijo) {
            $saldo += $this->calcularSaldoCuenta($hijo, $empresaId);
        }

        return $saldo;
    }
}
