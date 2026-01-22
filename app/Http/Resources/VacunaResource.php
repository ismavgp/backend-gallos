<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacunaResource extends JsonResource
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
            'nombre_vacuna' => $this->nombre_vacuna,
            'fecha_aplicacion' => optional($this->fecha_aplicacion)->format('d/m/Y'),
            'dosis' => $this->dosis,
            'observaciones' => $this->observaciones,

            // Relaciones
            'gallo' => new GalloResource($this->whenLoaded('gallo')),
        ];
    }
}
