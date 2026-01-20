<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VacunaResource;
use App\Models\Vacuna;
use App\Models\Gallo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class VacunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $gallera = $user->gallera;
        
        if (!$gallera) {
            return VacunaResource::collection([]);
        }

        $gallosIds = $gallera->gallos()->pluck('id');
        $query = Vacuna::whereIn('id_gallo', $gallosIds);

        // Filtrar por gallo
        if ($request->filled('id_gallo')) {
            $query->where('id_gallo', $request->id_gallo);
        }

        // Filtrar por nombre de vacuna
        if ($request->filled('nombre_vacuna')) {
            $query->where('nombre_vacuna', 'like', "%{$request->nombre_vacuna}%");
        }

        // Incluir relaciones
        if ($request->filled('include')) {
            $includes = explode(',', $request->include);
            $query->with($includes);
        }

        $query->orderBy('fecha_aplicacion', 'desc');

        $vacunas = $query->paginate($request->get('per_page', 15));

        return VacunaResource::collection($vacunas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): VacunaResource
    {
        $validated = $request->validate([
            'id_gallo' => 'required|exists:gallos,id',
            'nombre_vacuna' => 'required|string|max:100',
            'fecha_aplicacion' => 'required|date|before_or_equal:today',
            'dosis' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $user = $request->user();
        $gallera = $user->gallera;
        
        if (!$gallera) {
            abort(403, 'Debes tener una gallera');
        }

        $gallo = Gallo::find($validated['id_gallo']);
        if ($gallo->id_gallera !== $gallera->id) {
            abort(403, 'No tienes permiso para vacunar este gallo');
        }

        $vacuna = Vacuna::create($validated);

        return new VacunaResource($vacuna->load('gallo'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Vacuna $vacuna): VacunaResource
    {
        $user = $request->user();
        $gallera = $user->gallera;
        
        if (!$gallera || $vacuna->gallo->id_gallera !== $gallera->id) {
            abort(403, 'No tienes permiso para ver esta vacuna');
        }

        if ($request->filled('include')) {
            $includes = explode(',', $request->include);
            $vacuna->load($includes);
        }

        return new VacunaResource($vacuna);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacuna $vacuna): VacunaResource
    {
        $user = $request->user();
        $gallera = $user->gallera;
        
        if (!$gallera || $vacuna->gallo->id_gallera !== $gallera->id) {
            abort(403, 'No tienes permiso para actualizar esta vacuna');
        }

        $validated = $request->validate([
            'id_gallo' => 'sometimes|required|exists:gallos,id',
            'nombre_vacuna' => 'sometimes|required|string|max:100',
            'fecha_aplicacion' => 'sometimes|required|date|before_or_equal:today',
            'dosis' => 'sometimes|required|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        if (isset($validated['id_gallo'])) {
            $gallo = Gallo::find($validated['id_gallo']);
            if ($gallo->id_gallera !== $gallera->id) {
                abort(403, 'No tienes permiso para asignar este gallo');
            }
        }

        $vacuna->update($validated);

        return new VacunaResource($vacuna);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacuna $vacuna): Response
    {
        $user = request()->user();
        $gallera = $user->gallera;
        
        if (!$gallera || $vacuna->gallo->id_gallera !== $gallera->id) {
            abort(403, 'No tienes permiso para eliminar esta vacuna');
        }

        $vacuna->delete();

        return response()->noContent();
    }
}
