<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comprobante extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaccion_id',
        'archivo',
        'nombre_original',
        'tipo',
        'tamano',
    ];

    public function transaccion(): BelongsTo
    {
        return $this->belongsTo(Transaccion::class);
    }
}
