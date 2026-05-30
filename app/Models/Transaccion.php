<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'transacciones';

    protected $fillable = [
        'empresa_id',
        'categoria_id',
        'tipo',
        'monto',
        'descripcion',
        'fecha',
        'metodo_pago',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'fecha' => 'date',
            'estado' => 'string',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function comprobantes(): HasMany
    {
        return $this->hasMany(Comprobante::class);
    }

    public function esIngreso(): bool
    {
        return $this->tipo === 'ingreso';
    }

    public function esGasto(): bool
    {
        return $this->tipo === 'gasto';
    }

    public function estaConfirmado(): bool
    {
        return $this->estado === 'confirmado';
    }

    public function estaAnulado(): bool
    {
        return $this->estado === 'anulado';
    }
}
