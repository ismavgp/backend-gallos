<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EntrenamientoResource;
use App\Models\Entrenamiento;
use App\Models\Gallo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class EntrenamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Entrenamiento::whereHas('gallo', function ($q) use ($user) {
            $q->where('id_user', $user->id);
        });

        // Filtrar por gallo
        if ($request->filled('id_gallo')) {
            $query->where('id_gallo', $request->id_gallo);
        }

        // Filtrar por tipo de entrenamiento
        if ($request->filled('tipo_entrenamiento')) {
            $query->where('tipo_entrenamiento', 'like', '%' . $request->tipo_entrenamiento . '%');
        }

        // Filtrar por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        // Incluir relaciones permitidas
        if ($request->filled('include')) {
            $includes = collect(explode(',', $request->include))
                ->intersect(['gallo']); // whitelist
            $query->with($includes->all());
        }

        $entrenamientos = $query
            ->orderByDesc('fecha')
            ->paginate($request->integer('per_page', 15));

        return EntrenamientoResource::collection($entrenamientos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): EntrenamientoResource
    {
        $validated = $request->validate([
            'id_gallo' => 'required|exists:gallos,id',
            'fecha' => 'required|date',
            'duracion_minutos' => 'required|integer|min:1',
            'tipo_entrenamiento' => 'required|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $user = $request->user();
        $gallo = Gallo::find($validated['id_gallo']);
        
        if ($gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para entrenar este gallo');
        }

        $entrenamiento = Entrenamiento::create($validated);

        return new EntrenamientoResource($entrenamiento->load('gallo'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Entrenamiento $entrenamiento): EntrenamientoResource
    {
        $user = $request->user();
        if (!$entrenamiento || $entrenamiento->gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para ver este entrenamiento');
        }



        if ($request->filled('include')) {
            $includes = explode(',', $request->include);
            $entrenamiento->load($includes);
        }

        return new EntrenamientoResource($entrenamiento);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entrenamiento $entrenamiento): EntrenamientoResource
    {
        $user = $request->user();
        if (!$entrenamiento || $entrenamiento->gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para actualizar este entrenamiento');
        }

        $validated = $request->validate([
            'id_gallo' => 'sometimes|required|exists:gallos,id',
            'fecha' => 'sometimes|required|date',
            'duracion_minutos' => 'sometimes|required|integer|min:1',
            'tipo_entrenamiento' => 'sometimes|required|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        if (isset($validated['id_gallo'])) {
            $gallo = Gallo::find($validated['id_gallo']);
            if ($gallo->id_user !== $user->id) {
                abort(403, 'No tienes permiso para asignar este gallo');
            }
        }

        $entrenamiento->update($validated);

        return new EntrenamientoResource($entrenamiento);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entrenamiento $entrenamiento): Response
    {
        $user = request()->user();

        if (!$entrenamiento || $entrenamiento->gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para eliminar este entrenamiento');
        }

        $entrenamiento->delete();

        return response()->noContent();
    }

    /**
     * Obtener resumen de entrenamientos por gallo
     */
    public function resumenPorGallo(Request $request, int $galloId): \Illuminate\Http\JsonResponse
    {
        $query = Entrenamiento::where('id_gallo', $galloId);

        // Filtrar por rango de fechas si se proporciona
        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        $entrenamientos = $query->get();

        return response()->json([
            'total_entrenamientos' => $entrenamientos->count(),
            'total_minutos' => $entrenamientos->sum('duracion_minutos'),
            'por_tipo' => $entrenamientos->groupBy('tipo_entrenamiento')->map->count(),
            'promedio_duracion' => round($entrenamientos->avg('duracion_minutos'), 2),
        ]);
    }
}
