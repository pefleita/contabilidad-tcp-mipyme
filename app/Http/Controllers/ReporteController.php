<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Transaccion;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReporteController extends Controller
{
    public function resumenAnual(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return view('reportes.resumen-anual', [
                'sinEmpresa' => true,
                'data' => null,
            ]);
        }

        $anio = $request->get('anio', now()->year);

        $ingresosPorMes = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')
            ->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $gastosPorMes = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')
            ->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $ingresosPorCategoria = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')
            ->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('categoria_id, SUM(monto) as total')
            ->groupBy('categoria_id')
            ->with('categoria')
            ->get();

        $gastosPorCategoria = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')
            ->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('categoria_id, SUM(monto) as total')
            ->groupBy('categoria_id')
            ->with('categoria')
            ->get();

        $meses = [];
        for ($m = 1; $m <= 12; $m++) {
            $meses[] = [
                'mes' => $m,
                'ingresos' => (float) ($ingresosPorMes[$m] ?? 0),
                'gastos' => (float) ($gastosPorMes[$m] ?? 0),
                'balance' => (float) (($ingresosPorMes[$m] ?? 0) - ($gastosPorMes[$m] ?? 0)),
            ];
        }

        $totalIngresos = $ingresosPorCategoria->sum('total');
        $totalGastos = $gastosPorCategoria->sum('total');

        $data = [
            'anio' => $anio,
            'meses' => $meses,
            'ingresosPorCategoria' => $ingresosPorCategoria,
            'gastosPorCategoria' => $gastosPorCategoria,
            'totalIngresos' => $totalIngresos,
            'totalGastos' => $totalGastos,
            'balanceAnual' => $totalIngresos - $totalGastos,
        ];

        return view('reportes.resumen-anual', compact('data', 'empresa'));
    }

    public function informeONAT(Request $request): View
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return view('reportes.onat', [
                'sinEmpresa' => true,
                'data' => null,
            ]);
        }

        $anio = $request->get('anio', now()->year);

        $ingresos = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')
            ->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->sum('monto');

        $gastos = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')
            ->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->sum('monto');

        $baseImponible = $ingresos - $gastos;

        $ingresosPorMes = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')
            ->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $gastosPorMes = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')
            ->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes');

        $historial = [];
        for ($y = $anio - 4; $y <= $anio; $y++) {
            $ing = Transaccion::where('empresa_id', $empresa->id)
                ->where('tipo', 'ingreso')->where('estado', '!=', 'anulado')
                ->whereYear('fecha', $y)->sum('monto');
            $gas = Transaccion::where('empresa_id', $empresa->id)
                ->where('tipo', 'gasto')->where('estado', '!=', 'anulado')
                ->whereYear('fecha', $y)->sum('monto');
            $historial[] = [
                'anio' => $y,
                'ingresos' => $ing,
                'gastos' => $gas,
                'baseImponible' => $ing - $gas,
                'tieneTransacciones' => ($ing + $gas) > 0,
            ];
        }

        $data = [
            'anio' => $anio,
            'empresa' => $empresa,
            'ingresos' => $ingresos,
            'gastos' => $gastos,
            'baseImponible' => $baseImponible,
            'ingresosPorMes' => $ingresosPorMes,
            'gastosPorMes' => $gastosPorMes,
            'historial' => $historial,
        ];

        return view('reportes.onat', compact('data'));
    }

    public function exportResumenPDF(Request $request)
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return redirect()->route('reportes.resumen-anual')->with('error', 'Debe configurar los datos de su empresa antes de exportar reportes.');
        }

        $anio = $request->get('anio', now()->year);

        $data = $this->getResumenData($empresa, $anio);

        $pdf = Pdf::loadView('reportes.pdf.resumen-anual', compact('data', 'empresa'));
        $filename = storage_path("app/resumen-anual-{$anio}.pdf");
        $pdf->save($filename);
        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function exportResumenExcel(Request $request)
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return redirect()->route('reportes.resumen-anual')->with('error', 'Debe configurar los datos de su empresa antes de exportar reportes.');
        }

        $anio = $request->get('anio', now()->year);

        $data = $this->getResumenData($empresa, $anio);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Resumen {$anio}");

        $sheet->setCellValue('A1', "Resumen Anual {$anio} - {$empresa->nombre}");
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A3', 'Mes');
        $sheet->setCellValue('B3', 'Ingresos');
        $sheet->setCellValue('C3', 'Gastos');
        $sheet->setCellValue('D3', 'Balance');
        $sheet->getStyle('A3:D3')->getFont()->setBold(true);

        $row = 4;
        foreach ($data['meses'] as $mes) {
            $sheet->setCellValue("A{$row}", \Carbon\Carbon::create()->month($mes['mes'])->format('F'));
            $sheet->setCellValue("B{$row}", $mes['ingresos']);
            $sheet->setCellValue("C{$row}", $mes['gastos']);
            $sheet->setCellValue("D{$row}", $mes['balance']);
            $row++;
        }

        $sheet->setCellValue("A{$row}", 'TOTAL');
        $sheet->setCellValue("B{$row}", $data['totalIngresos']);
        $sheet->setCellValue("C{$row}", $data['totalGastos']);
        $sheet->setCellValue("D{$row}", $data['balanceAnual']);
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);
        $filename = storage_path("app/resumen-anual-{$anio}.xlsx");
        $writer->save($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function exportONATPDF(Request $request)
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return redirect()->route('reportes.informe-onat')->with('error', 'Debe configurar los datos de su empresa antes de exportar reportes.');
        }

        $anio = $request->get('anio', now()->year);

        $data = $this->getONATData($empresa, $anio);

        $pdf = Pdf::loadView('reportes.pdf.onat', compact('data', 'empresa'));
        $filename = storage_path("app/informe-onat-{$anio}.pdf");
        $pdf->save($filename);
        return response()->download($filename)->deleteFileAfterSend(true);
    }

    private function getResumenData(?Empresa $empresa, int $anio): array
    {
        $ingresosPorMes = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->groupBy('mes')->pluck('total', 'mes');

        $gastosPorMes = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->groupBy('mes')->pluck('total', 'mes');

        $ingresosPorCategoria = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('categoria_id, SUM(monto) as total')
            ->groupBy('categoria_id')->with('categoria')->get();

        $gastosPorCategoria = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('categoria_id, SUM(monto) as total')
            ->groupBy('categoria_id')->with('categoria')->get();

        $meses = [];
        for ($m = 1; $m <= 12; $m++) {
            $meses[] = [
                'mes' => $m,
                'ingresos' => (float) ($ingresosPorMes[$m] ?? 0),
                'gastos' => (float) ($gastosPorMes[$m] ?? 0),
                'balance' => (float) (($ingresosPorMes[$m] ?? 0) - ($gastosPorMes[$m] ?? 0)),
            ];
        }

        return [
            'anio' => $anio,
            'meses' => $meses,
            'ingresosPorCategoria' => $ingresosPorCategoria,
            'gastosPorCategoria' => $gastosPorCategoria,
            'totalIngresos' => $ingresosPorCategoria->sum('total'),
            'totalGastos' => $gastosPorCategoria->sum('total'),
            'balanceAnual' => $ingresosPorCategoria->sum('total') - $gastosPorCategoria->sum('total'),
        ];
    }

    private function getONATData(?Empresa $empresa, int $anio): array
    {
        $ingresos = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)->sum('monto');

        $gastos = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)->sum('monto');

        $ingresosPorMes = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->groupBy('mes')->pluck('total', 'mes');

        $gastosPorMes = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->groupBy('mes')->pluck('total', 'mes');

        $historial = [];
        for ($y = $anio - 4; $y <= $anio; $y++) {
            $ing = Transaccion::where('empresa_id', $empresa->id)
                ->where('tipo', 'ingreso')->where('estado', '!=', 'anulado')
                ->whereYear('fecha', $y)->sum('monto');
            $gas = Transaccion::where('empresa_id', $empresa->id)
                ->where('tipo', 'gasto')->where('estado', '!=', 'anulado')
                ->whereYear('fecha', $y)->sum('monto');
            $historial[] = [
                'anio' => $y,
                'ingresos' => $ing,
                'gastos' => $gas,
                'baseImponible' => $ing - $gas,
                'tieneTransacciones' => ($ing + $gas) > 0,
            ];
        }

        return [
            'anio' => $anio,
            'ingresos' => $ingresos,
            'gastos' => $gastos,
            'baseImponible' => $ingresos - $gastos,
            'ingresosPorMes' => $ingresosPorMes,
            'gastosPorMes' => $gastosPorMes,
            'historial' => $historial,
        ];
    }

    public function exportONATExcel(Request $request)
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return redirect()->route('reportes.informe-onat')->with('error', 'Debe configurar los datos de su empresa antes de exportar reportes.');
        }

        $anio = $request->get('anio', now()->year);

        $data = $this->getONATData($empresa, $anio);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("ONAT {$anio}");

        $sheet->setCellValue('A1', "Informe ONAT {$anio} - {$empresa->nombre}");
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A3', 'Mes');
        $sheet->setCellValue('B3', 'Ingresos');
        $sheet->setCellValue('C3', 'Gastos');
        $sheet->setCellValue('D3', 'Base Imponible');
        $sheet->getStyle('A3:D3')->getFont()->setBold(true);

        $row = 4;
        for ($m = 1; $m <= 12; $m++) {
            $ing = (float) ($data['ingresosPorMes'][$m] ?? 0);
            $gas = (float) ($data['gastosPorMes'][$m] ?? 0);
            $sheet->setCellValue("A{$row}", Carbon::create()->month($m)->format('F'));
            $sheet->setCellValue("B{$row}", $ing);
            $sheet->setCellValue("C{$row}", $gas);
            $sheet->setCellValue("D{$row}", $ing - $gas);
            $row++;
        }

        $sheet->setCellValue("A{$row}", 'TOTAL');
        $sheet->setCellValue("B{$row}", $data['ingresos']);
        $sheet->setCellValue("C{$row}", $data['gastos']);
        $sheet->setCellValue("D{$row}", $data['baseImponible']);
        $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);
        $filename = storage_path("app/informe-onat-{$anio}.xlsx");
        $writer->save($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function dashboardData(Request $request): JsonResponse
    {
        $empresa = $request->user()->empresa;

        if (!$empresa) {
            return response()->json([]);
        }

        $anio = now()->year;

        $ingresosPorMes = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->groupBy('mes')->pluck('total', 'mes');

        $gastosPorMes = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('MONTH(fecha) as mes, SUM(monto) as total')
            ->groupBy('mes')->pluck('total', 'mes');

        $ingresosPorCategoria = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'ingreso')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('categoria_id, SUM(monto) as total')
            ->groupBy('categoria_id')->with('categoria')
            ->orderBy('total', 'desc')->get();

        $gastosPorCategoria = Transaccion::where('empresa_id', $empresa->id)
            ->where('tipo', 'gasto')->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)
            ->selectRaw('categoria_id, SUM(monto) as total')
            ->groupBy('categoria_id')->with('categoria')
            ->orderBy('total', 'desc')->get();

        $meses = [];
        for ($m = 1; $m <= 12; $m++) {
            $meses[] = [
                'mes' => Carbon::create()->month($m)->format('M'),
                'ingresos' => (float) ($ingresosPorMes[$m] ?? 0),
                'gastos' => (float) ($gastosPorMes[$m] ?? 0),
            ];
        }

        $totalIngresos = $ingresosPorCategoria->sum('total');
        $totalGastos = $gastosPorCategoria->sum('total');
        $numTransacciones = Transaccion::where('empresa_id', $empresa->id)
            ->where('estado', '!=', 'anulado')
            ->whereYear('fecha', $anio)->count();

        $catIngresos = $ingresosPorCategoria->map(fn ($i) => [
            'categoria' => $i->categoria->nombre ?? 'Sin categoría',
            'total' => (float) $i->total,
        ]);

        $catGastos = $gastosPorCategoria->map(fn ($g) => [
            'categoria' => $g->categoria->nombre ?? 'Sin categoría',
            'total' => (float) $g->total,
        ]);

        return response()->json([
            'meses' => $meses,
            'ingresosPorCategoria' => $catIngresos,
            'gastosPorCategoria' => $catGastos,
            'totalIngresos' => $totalIngresos,
            'totalGastos' => $totalGastos,
            'balance' => $totalIngresos - $totalGastos,
            'margenGanancia' => $totalIngresos > 0 ? round(($totalIngresos - $totalGastos) / $totalIngresos * 100, 1) : 0,
            'promedioTransaccion' => $numTransacciones > 0 ? round(($totalIngresos + $totalGastos) / $numTransacciones, 2) : 0,
            'totalTransacciones' => $numTransacciones,
        ]);
    }
}
