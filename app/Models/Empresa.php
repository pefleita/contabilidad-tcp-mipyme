<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'nit',
        'actividad_economica',
        'tipo_contabilidad',
        'logo',
    ];

    protected function casts(): array
    {
        return [
            'tipo_contabilidad' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class);
    }

    public function transacciones(): HasMany
    {
        return $this->hasMany(Transaccion::class);
    }

    public function cuentasContables(): HasMany
    {
        return $this->hasMany(CuentaContable::class);
    }

    public function activosFijos(): HasMany
    {
        return $this->hasMany(ActivoFijo::class);
    }

    public function esContabilidadFormal(): bool
    {
        return $this->tipo_contabilidad === 'formal';
    }

    public function esContabilidadSimplificada(): bool
    {
        return $this->tipo_contabilidad === 'simplificada';
    }

    public function ingresosAnuales(): float
    {
        return (float) $this->transacciones()
            ->where('tipo', 'ingreso')
            ->where('estado', 'confirmado')
            ->whereYear('fecha', now()->year)
            ->sum('monto');
    }

    public function requiereContabilidadFormal(): bool
    {
        return $this->ingresosAnuales() > 500000;
    }
}