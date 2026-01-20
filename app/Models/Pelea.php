<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelea extends Model
{
    use HasFactory;

    protected $table = 'peleas';

    protected $fillable = [
        'id_gallo',
        'fecha',
        'lugar',
        'estado',
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
