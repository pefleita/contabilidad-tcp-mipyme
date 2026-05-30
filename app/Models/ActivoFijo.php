<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivoFijo extends Model
{
    use HasFactory;

    protected $table = 'activos_fijos';

    protected $fillable = [
        'empresa_id',
        'codigo',
        'nombre',
        'descripcion',
        'tipo',
        'costo_original',
        'valor_residual',
        'vida_util_anos',
        'depreciacion_acumulada',
        'fecha_adquisicion',
        'fecha_inicio_depreciacion',
        'esta_activo',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'costo_original' => 'decimal:2',
            'valor_residual' => 'decimal:2',
            'depreciacion_acumulada' => 'decimal:2',
            'fecha_adquisicion' => 'date',
            'fecha_inicio_depreciacion' => 'date',
            'esta_activo' => 'boolean',
            'vida_util_anos' => 'integer',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function depreciacionAnual(): float
    {
        return ($this->costo_original - $this->valor_residual) / $this->vida_util_anos;
    }

    public function depreciacionMensual(): float
    {
        return $this->depreciacionAnual() / 12;
    }

    public function mesesTranscurridos(): int
    {
        $inicio = $this->fecha_inicio_depreciacion;
        $ahora = now();

        return $inicio->diffInMonths($ahora);
    }

    public function valorNeto(): float
    {
        return $this->costo_original - $this->calcularDepreciacionAcumulada();
    }

    public function calcularDepreciacionAcumulada(): float
    {
        $meses = min($this->mesesTranscurridos(), $this->vida_util_anos * 12);
        return $meses * $this->depreciacionMensual();
    }
}
