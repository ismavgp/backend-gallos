<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntrenamientoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_gallo' => $this->id_gallo,
            'fecha' => $this->fecha?->toISOString(),
            'fecha_formateada' => $this->fecha?->format('Y-m-d H:i'),
            'duracion_minutos' => $this->duracion_minutos,
            'tipo_entrenamiento' => $this->tipo_entrenamiento,
            'observaciones' => $this->observaciones,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relaciones
            'gallo' => new GalloResource($this->whenLoaded('gallo')),
        ];
    }
}
