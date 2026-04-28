<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'categoria_producto',
        'codigo',
        'nombre',
        'descripcion',
        'unidad_medida',
        'precio_costo',
        'precio_venta',
        'existencias',
        'stock_minimo',
        'es_servicio',
        'esta_activo',
    ];

    protected function casts(): array
    {
        return [
            'es_servicio' => 'boolean',
            'esta_activo' => 'boolean',
            'precio_costo' => 'decimal:2',
            'precio_venta' => 'decimal:2',
            'existencias' => 'decimal:2',
            'stock_minimo' => 'decimal:2',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function kardex(): HasMany
    {
        return $this->hasMany(Kardex::class);
    }

    public function estaBajoStock(): bool
    {
        return $this->existencias < $this->stock_minimo;
    }

    public function margen(): float
    {
        if ($this->precio_costo == 0) {
            return 0;
        }
        return (($this->precio_venta - $this->precio_costo) / $this->precio_costo) * 100;
    }
}