<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrenamiento extends Model
{
    use HasFactory;

    protected $table = 'entrenamientos';

    protected $fillable = [
        'id_gallo',
        'fecha',
        'duracion_minutos',
        'tipo_entrenamiento',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    /**
     * Relación con Gallo
     */
    public function gallo(): BelongsTo
    {
        return $this->belongsTo(Gallo::class, 'id_gallo');
    }
}
