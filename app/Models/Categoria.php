<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nombre',
        'tipo',
        'color',
        'icono',
        'es_activo',
    ];

    protected function casts(): array
    {
        return [
            'es_activo' => 'boolean',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function esIngreso(): bool
    {
        return $this->tipo === 'ingreso';
    }

    public function esGasto(): bool
    {
        return $this->tipo === 'gasto';
    }
}