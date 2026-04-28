<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kardex extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'tipo_movimiento',
        'tipo_origen',
        'cantidad',
        'precio_unitario',
        'costo_total',
        'existencias_anterior',
        'existencias_nueva',
        'fecha',
        'referencia',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'decimal:2',
            'precio_unitario' => 'decimal:2',
            'costo_total' => 'decimal:2',
            'existencias_anterior' => 'decimal:2',
            'existencias_nueva' => 'decimal:2',
            'fecha' => 'date',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function esEntrada(): bool
    {
        return $this->tipo_movimiento === 'entrada';
    }

    public function esSalida(): bool
    {
        return $this->tipo_movimiento === 'salida';
    }

    public function esAjuste(): bool
    {
        return $this->tipo_movimiento === 'ajuste';
    }
}