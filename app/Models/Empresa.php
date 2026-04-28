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

    public function esContabilidadFormal(): bool
    {
        return $this->tipo_contabilidad === 'formal';
    }

    public function esContabilidadSimplificada(): bool
    {
        return $this->tipo_contabilidad === 'simplificada';
    }
}