<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AsientoContable extends Model
{
    use HasFactory;

    protected $table = 'asientos_contables';

    protected $fillable = [
        'empresa_id',
        'fecha',
        'descripcion',
        'numero_asiento',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'estado' => 'string',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function partidas(): HasMany
    {
        return $this->hasMany(PartidaAsiento::class, 'asiento_id');
    }

    public function validarPartidaDoble(): bool
    {
        $totalDebe = $this->partidas()->sum('debe');
        $totalHaber = $this->partidas()->sum('haber');

        return bccomp((string) $totalDebe, (string) $totalHaber, 2) === 0;
    }

    public function totalDebe()
    {
        return $this->partidas()->sum('debe');
    }

    public function totalHaber()
    {
        return $this->partidas()->sum('haber');
    }
}
