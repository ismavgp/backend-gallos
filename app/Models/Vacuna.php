<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacuna extends Model
{
    use HasFactory;

    protected $table = 'vacunas';

    protected $fillable = [
        'id_gallo',
        'nombre_vacuna',
        'fecha_aplicacion',
        'dosis',
        'observaciones',
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
    ];

    /**
     * Relación con Gallo
     */
    public function gallo(): BelongsTo
    {
        return $this->belongsTo(Gallo::class, 'id_gallo','id');
    }
}
