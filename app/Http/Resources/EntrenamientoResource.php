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
            'fecha' => optional($this->fecha)->format('d/m/Y'),
            'duracion_minutos' => $this->duracion_minutos,
            'tipo_entrenamiento' => $this->tipo_entrenamiento,
            'observaciones' => $this->observaciones,

            // Relaciones
            'gallo' => new GalloResource($this->whenLoaded('gallo')),
        ];
    }
}
