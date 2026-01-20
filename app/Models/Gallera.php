<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallera extends Model
{
    use HasFactory;

    protected $table = 'galleras';

    protected $fillable = [
        'id_user',
        'phone',
        'address',
        'city',
        'country',
    ];

    /**
     * Relación con User (1:1)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relación con Gallos
     */
    public function gallos(): HasMany
    {
        return $this->hasMany(Gallo::class);
    }
}
