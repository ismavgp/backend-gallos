<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeleaResource extends JsonResource
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
            'fecha' => $this->fecha?->format('Y-m-d H:i:s'),
            'lugar' => $this->lugar,
            'estado' => $this->estado,
            
            // Relaciones
            'gallo' => new GalloResource($this->whenLoaded('gallo')),
        ];
    }
}
