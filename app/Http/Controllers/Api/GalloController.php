<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalloResource;
use App\Models\Gallo;
use Illuminate\Http\Request;

use Illuminate\Http\Response;


class GalloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();




        $query = Gallo::where('id_user', $user->id);

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Filtrar por sexo
        if ($request->filled('sexo')) {
            $query->where('sexo', $request->sexo);
        }

        // Filtrar por color
        if ($request->filled('color')) {
            $query->where('color', 'like', "%{$request->color}%");
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Incluir relaciones
        if ($request->filled('include')) {
            $includes = explode(',', $request->include);
            $query->with($includes);
        }

        $gallos = $query->paginate($request->get('per_page', 15));

        return GalloResource::collection($gallos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): GalloResource
    {
        $user = $request->user();


        $validated = $request->validate([
            'placa' => 'required|string|max:20|unique:gallos,placa',
            'name' => 'required|string|max:100',
            'sexo' => 'required|string|size:1|in:M,H',
            'fecha_nacimiento' => 'required|date|before_or_equal:today',
            'url_imagen' => 'nullable|string|max:500',
            'color' => 'required|string|max:50',
            'peso' => 'required|numeric|min:0|max:999.99',
            'talla' => 'required|numeric|min:0|max:999.99',
            'color_patas' => 'required|string|max:50',
            'tipo_cresta' => 'required|string|max:50',
            'id_padre' => 'nullable|exists:gallos,id',
            'id_madre' => 'nullable|exists:gallos,id',
        ]);

        $validated['id_user'] = $user->id;
        $gallo = Gallo::create($validated);

        return new GalloResource($gallo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Gallo $gallo): GalloResource
    {
        $user = $request->user();

        $gallo = Gallo::find($gallo->id);

        if (!$gallo || $gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para ver este gallo');
        }
        return new GalloResource($gallo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallo $gallo): GalloResource
    {
        $user = $request->user();
        
        if (!$gallo || $gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para actualizar este gallo');
        }


        $validated = $request->validate([
            'placa' => 'sometimes|required|string|max:20|unique:gallos,placa,' . $gallo->id,
            'name' => 'sometimes|required|string|max:100',
            'sexo' => 'sometimes|required|string|size:1|in:M,F',
            'fecha_nacimiento' => 'sometimes|required|date|before_or_equal:today',
            'url_imagen' => 'nullable|string|max:500',
            'color' => 'sometimes|required|string|max:50',
            'peso' => 'sometimes|required|numeric|min:0|max:999.99',
            'talla' => 'sometimes|required|numeric|min:0|max:999.99',
            'color_patas' => 'sometimes|required|string|max:50',
            'tipo_cresta' => 'sometimes|required|string|max:50',
            'id_padre' => 'nullable|exists:gallos,id',
            'id_madre' => 'nullable|exists:gallos,id',
        ]);

        $gallo->update($validated);

        return new GalloResource($gallo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallo $gallo): Response
    {
        $user = request()->user();



        if (!$gallo || $gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para eliminar este gallo');
        }


        $gallo->delete();

        return response()->noContent();
    }

    /**
     * Obtener estadísticas del gallo
     */
    public function estadisticas(Gallo $gallo): \Illuminate\Http\JsonResponse
    {
        $user = request()->user();
        if (!$gallo || $gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para ver estas estadísticas');
        }


        $gallo->loadCount(['vacunas', 'entrenamientos', 'peleas']);

        $peleasGanadas = $gallo->peleas()->where('estado', 'Ganada')->count();
        $peleasPerdidas = $gallo->peleas()->where('estado', 'Perdida')->count();
        $peleasEmpatadas = $gallo->peleas()->where('estado', 'Empatada')->count();

        return response()->json([
            'gallo' => new GalloResource($gallo),
            'estadisticas' => [
                'total_vacunas' => $gallo->vacunas_count,
                'total_entrenamientos' => $gallo->entrenamientos_count,
                'total_peleas' => $gallo->peleas_count,
                'peleas_ganadas' => $peleasGanadas,
                'peleas_perdidas' => $peleasPerdidas,
                'peleas_empatadas' => $peleasEmpatadas,
                'porcentaje_victorias' => $gallo->peleas_count > 0
                    ? round(($peleasGanadas / $gallo->peleas_count) * 100, 2)
                    : 0,
            ],
        ]);
    }
}
