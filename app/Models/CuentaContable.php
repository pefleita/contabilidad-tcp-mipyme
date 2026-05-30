<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuentaContable extends Model
{
    use HasFactory;

    protected $table = 'cuentas_contables';

    protected $fillable = [
        'empresa_id',
        'codigo',
        'nombre',
        'tipo',
        'padre_id',
        'nivel',
        'es_movimiento',
        'es_grupo',
    ];

    protected function casts(): array
    {
        return [
            'es_movimiento' => 'boolean',
            'es_grupo' => 'boolean',
            'nivel' => 'integer',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function padre(): BelongsTo
    {
        return $this->belongsTo(CuentaContable::class, 'padre_id');
    }

    public function hijos(): HasMany
    {
        return $this->hasMany(CuentaContable::class, 'padre_id');
    }
}
