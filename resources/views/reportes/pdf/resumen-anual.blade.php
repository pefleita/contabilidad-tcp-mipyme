<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen Anual {{ $data['anio'] }} - {{ $empresa?->nombre ?? 'Sin empresa' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; color: #1e293b; }
        h1 { font-size: 16pt; text-align: center; margin-bottom: 4px; }
        h2 { font-size: 12pt; margin-top: 20px; margin-bottom: 8px; color: #334155; }
        .header { text-align: center; margin-bottom: 20px; }
        .header p { font-size: 9pt; color: #64748b; margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f1f5f9; text-align: left; padding: 6px 8px; font-size: 9pt; border-bottom: 2px solid #cbd5e1; }
        td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 9pt; }
        .text-right { text-align: right; }
        .text-emerald { color: #059669; }
        .text-rose { color: #e11d48; }
        .font-bold { font-weight: bold; }
        .total-row { background: #f8fafc; font-weight: bold; }
        .summary-table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        .summary-table td { width: 25%; text-align: center; padding: 8px; border: 1px solid #e2e8f0; }
        .summary-table td p { margin: 2px 0; }
        .summary-table .label { font-size: 8pt; color: #64748b; }
        .summary-table .value { font-size: 12pt; font-weight: bold; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8pt; color: #94a3b8; padding: 10px 0; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Resumen Anual de Ingresos y Gastos</h1>
        <p>{{ $empresa?->nombre ?? 'Empresa no configurada' }} | NIT: {{ $empresa?->nit ?? 'N/A' }}</p>
        <p>Año Fiscal {{ $data['anio'] }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <td>
                <p class="label">Total Ingresos</p>
                <p class="value text-emerald">${{ number_format($data['totalIngresos'], 2) }}</p>
            </td>
            <td>
                <p class="label">Total Gastos</p>
                <p class="value text-rose">${{ number_format($data['totalGastos'], 2) }}</p>
            </td>
            <td>
                <p class="label">Balance Anual</p>
                <p class="value {{ $data['balanceAnual'] >= 0 ? '' : 'text-rose' }}">${{ number_format($data['balanceAnual'], 2) }}</p>
            </td>
            <td>
                <p class="label">Margen de Ganancia</p>
                <p class="value {{ $data['totalIngresos'] > 0 && $data['balanceAnual'] >= 0 ? 'text-emerald' : 'text-rose' }}">
                    {{ $data['totalIngresos'] > 0 ? number_format(($data['balanceAnual'] / $data['totalIngresos']) * 100, 1) : 0 }}%
                </p>
            </td>
        </tr>
    </table>

    <h2>Desglose Mensual</h2>
    <table>
        <thead>
            <tr>
                <th>Mes</th>
                <th class="text-right">Ingresos</th>
                <th class="text-right">Gastos</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['meses'] as $mes)
            <tr>
                <td>{{ \Carbon\Carbon::create()->month($mes['mes'])->format('F') }}</td>
                <td class="text-right text-emerald">${{ number_format($mes['ingresos'], 2) }}</td>
                <td class="text-right text-rose">${{ number_format($mes['gastos'], 2) }}</td>
                <td class="text-right">${{ number_format($mes['balance'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAL</td>
                <td class="text-right">${{ number_format($data['totalIngresos'], 2) }}</td>
                <td class="text-right">${{ number_format($data['totalGastos'], 2) }}</td>
                <td class="text-right">${{ number_format($data['balanceAnual'], 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 8px;">
                <h2 style="font-size: 12pt; margin-top: 0; margin-bottom: 8px; color: #334155;">Ingresos por Categoría</h2>
                <table style="width: 100%; border-collapse: collapse; margin-top: 0;">
                    <thead>
                        <tr><th style="background: #f1f5f9; text-align: left; padding: 6px 8px; font-size: 9pt; border-bottom: 2px solid #cbd5e1;">Categoría</th><th style="background: #f1f5f9; text-align: right; padding: 6px 8px; font-size: 9pt; border-bottom: 2px solid #cbd5e1;">Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($data['ingresosPorCategoria'] as $item)
                        <tr>
                            <td style="padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 9pt;">{{ $item->categoria->nombre ?? 'Sin categoría' }}</td>
                            <td style="padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 9pt; text-align: right; color: #059669;">${{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 8px;">
                <h2 style="font-size: 12pt; margin-top: 0; margin-bottom: 8px; color: #334155;">Gastos por Categoría</h2>
                <table style="width: 100%; border-collapse: collapse; margin-top: 0;">
                    <thead>
                        <tr><th style="background: #f1f5f9; text-align: left; padding: 6px 8px; font-size: 9pt; border-bottom: 2px solid #cbd5e1;">Categoría</th><th style="background: #f1f5f9; text-align: right; padding: 6px 8px; font-size: 9pt; border-bottom: 2px solid #cbd5e1;">Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($data['gastosPorCategoria'] as $item)
                        <tr>
                            <td style="padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 9pt;">{{ $item->categoria->nombre ?? 'Sin categoría' }}</td>
                            <td style="padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 9pt; text-align: right; color: #e11d48;">${{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} - Sistema Contabilidad TCP/Mipyme
    </div>
</body>
</html>