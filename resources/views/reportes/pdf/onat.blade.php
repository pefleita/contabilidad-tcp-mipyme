<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe ONAT {{ $data['anio'] }} - {{ $empresa?->nombre ?? 'Sin empresa' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; color: #1e293b; }
        h1 { font-size: 16pt; text-align: center; margin-bottom: 4px; }
        h2 { font-size: 12pt; margin-top: 20px; margin-bottom: 8px; color: #334155; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; }
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
        .company-info { border: 1px solid #e2e8f0; padding: 10px; margin: 12px 0; }
        .company-info p { margin: 3px 0; font-size: 9pt; }
        .company-info .label { color: #64748b; }
        .summary { display: flex; justify-content: space-between; margin: 12px 0; }
        .summary-box { text-align: center; padding: 10px; border: 1px solid #e2e8f0; flex: 1; margin: 0 4px; }
        .summary-box p { margin: 2px 0; }
        .summary-box .label { font-size: 8pt; color: #64748b; }
        .summary-box .value { font-size: 14pt; font-weight: bold; }
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 9pt; font-weight: bold; margin-top: 6px; }
        .status-formal { background: #dbeafe; color: #1d4ed8; }
        .status-simplified { background: #fef3c7; color: #b45309; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8pt; color: #94a3b8; padding: 10px 0; border-top: 1px solid #e2e8f0; }
        .declaration { margin-top: 24px; padding: 16px; border: 2px solid #1e293b; text-align: center; }
        .declaration h3 { font-size: 11pt; margin-bottom: 8px; }
        .declaration p { font-size: 9pt; color: #475569; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Informe para Declaración Jurada ONAT</h1>
        <p>Resolución 272/2024 - Año Fiscal {{ $data['anio'] }}</p>
    </div>

    <div class="company-info">
        <p><span class="label">Nombre:</span> {{ $empresa?->nombre ?? 'No configurada' }}</p>
        <p><span class="label">NIT:</span> {{ $empresa?->nit ?? 'No configurado' }}</p>
        <p><span class="label">Actividad Económica:</span> {{ $empresa?->actividad_economica ?? 'No especificada' }}</p>
        <p><span class="label">Tipo de Contabilidad:</span>
            <span class="status-badge {{ $empresa?->esContabilidadFormal() ? 'status-formal' : 'status-simplified' }}">
                {{ $empresa?->esContabilidadFormal() ? 'Formal' : 'Simplificada' }}
            </span>
        </p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <p class="label">Total Ingresos</p>
            <p class="value text-emerald">${{ number_format($data['ingresos'], 2) }}</p>
        </div>
        <div class="summary-box">
            <p class="label">Total Gastos Deducibles</p>
            <p class="value text-rose">${{ number_format($data['gastos'], 2) }}</p>
        </div>
        <div class="summary-box">
            <p class="label">Base Imponible</p>
            <p class="value">${{ number_format($data['baseImponible'], 2) }}</p>
        </div>
    </div>

    <h2>Resumen Mensual</h2>
    <table>
        <thead>
            <tr>
                <th>Mes</th>
                <th class="text-right">Ingresos</th>
                <th class="text-right">Gastos</th>
                <th class="text-right">Base Imponible</th>
            </tr>
        </thead>
        <tbody>
            @for ($m = 1; $m <= 12; $m++)
            @php
                $ing = (float) ($data['ingresosPorMes'][$m] ?? 0);
                $gas = (float) ($data['gastosPorMes'][$m] ?? 0);
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</td>
                <td class="text-right text-emerald">${{ number_format($ing, 2) }}</td>
                <td class="text-right text-rose">${{ number_format($gas, 2) }}</td>
                <td class="text-right">${{ number_format($ing - $gas, 2) }}</td>
            </tr>
            @endfor
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAL</td>
                <td class="text-right">${{ number_format($data['ingresos'], 2) }}</td>
                <td class="text-right">${{ number_format($data['gastos'], 2) }}</td>
                <td class="text-right">${{ number_format($data['baseImponible'], 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="declaration">
        <h3>Declaración Jurada</h3>
        <p>Declaro que los datos aquí reflejados son fiel expresión de la verdad y corresponden a las operaciones realizadas durante el año fiscal {{ $data['anio'] }}, conforme a lo establecido en la Resolución 272/2024.</p>
    </div>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} - Sistema Contabilidad TCP/Mipyme
    </div>
</body>
</html>