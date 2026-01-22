<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallo extends Model
{
    use HasFactory;

    protected $table = 'gallos';

    protected $fillable = [
        'placa',
        'name',
        'sexo',
        'fecha_nacimiento',
        'url_imagen',
        'color',
        'peso',
        'talla',
        'color_patas',
        'tipo_cresta',
        'id_padre',
        'id_madre',
        'id_user',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'peso' => 'decimal:2',
        'talla' => 'decimal:2',
    ];

    /**
     * Relación con User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relación con padre
     */
    public function padre(): BelongsTo
    {
        return $this->belongsTo(Gallo::class, 'id_padre');
    }

    /**
     * Relación con madre
     */
    public function madre(): BelongsTo
    {
        return $this->belongsTo(Gallo::class, 'id_madre');
    }

    /**
     * Hijos del gallo (como padre)
     */
    public function hijosPadre(): HasMany
    {
        return $this->hasMany(Gallo::class, 'id_padre');
    }

    /**
     * Hijos del gallo (como madre)
     */
    public function hijosMadre(): HasMany
    {
        return $this->hasMany(Gallo::class, 'id_madre');
    }

    /**
     * Relación con vacunas
     */
    public function vacunas(): HasMany
    {
        return $this->hasMany(Vacuna::class, 'id_gallo');
    }

    /**
     * Relación con entrenamientos
     */
    public function entrenamientos(): HasMany
    {
        return $this->hasMany(Entrenamiento::class, 'id_gallo');
    }

    /**
     * Relación con peleas
     */
    public function peleas(): HasMany
    {
        return $this->hasMany(Pelea::class, 'id_gallo');
    }
}
