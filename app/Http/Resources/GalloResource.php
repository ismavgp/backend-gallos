<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalloResource extends JsonResource
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
            'placa' => $this->placa,
            'name' => $this->name,
            'sexo' => $this->sexo,
            'fecha_nacimiento' => $this->fecha_nacimiento?->format('Y-m-d'),
            'edad_meses' => $this->fecha_nacimiento?->diffInMonths(now()),
            'url_imagen' => $this->url_imagen,
            'color' => $this->color,
            'peso' => $this->peso,
            'talla' => $this->talla,
            'color_patas' => $this->color_patas,
            'tipo_cresta' => $this->tipo_cresta,
            'id_padre' => $this->id_padre,
            'id_madre' => $this->id_madre,
            'id_user' => $this->id_user,

            // Relaciones opcionales
            'user' => new UserResource($this->whenLoaded('user')),
            'padre' => new GalloResource($this->whenLoaded('padre')),
            'madre' => new GalloResource($this->whenLoaded('madre')),
            'vacunas' => VacunaResource::collection($this->whenLoaded('vacunas')),
            'entrenamientos' => EntrenamientoResource::collection($this->whenLoaded('entrenamientos')),
            'peleas' => PeleaResource::collection($this->whenLoaded('peleas')),

            // Estadísticas
            'total_vacunas' => $this->whenCounted('vacunas'),
            'total_entrenamientos' => $this->whenCounted('entrenamientos'),
            'total_peleas' => $this->whenCounted('peleas'),
        ];
    }
}
