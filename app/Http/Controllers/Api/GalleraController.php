<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleraResource;
use App\Models\Gallera;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class GalleraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $gallera = $user->gallera;
        
        if (!$gallera) {
            return GalleraResource::collection([]);
        }

        // Solo mostrar la gallera del usuario autenticado
        return GalleraResource::collection([$gallera]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): GalleraResource
    {
        $user = $request->user();
        
        // Verificar que el usuario no tenga ya una gallera
        if ($user->gallera) {
            abort(403, 'Ya tienes una gallera registrada');
        }

        $validated = $request->validate([
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
        ]);

        $validated['id_user'] = $user->id;
        $gallera = Gallera::create($validated);

        return new GalleraResource($gallera->load('user'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Gallera $gallera): GalleraResource
    {
        $user = $request->user();
        
        if ($gallera->id_user !== $user->id) {
            abort(403, 'No tienes permiso para ver esta gallera');
        }

        if ($request->filled('include')) {
            $includes = explode(',', $request->include);
            $gallera->load($includes);
        }

        return new GalleraResource($gallera);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallera $gallera): GalleraResource
    {
        $user = $request->user();
        
        if ($gallera->id_user !== $user->id) {
            abort(403, 'No tienes permiso para actualizar esta gallera');
        }

        $validated = $request->validate([
            'phone' => 'sometimes|required|string|max:15',
            'address' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'country' => 'sometimes|required|string|max:100',
        ]);

        $gallera->update($validated);

        return new GalleraResource($gallera);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallera $gallera): Response
    {
        $user = request()->user();
        
        if ($gallera->id_user !== $user->id) {
            abort(403, 'No tienes permiso para eliminar esta gallera');
        }

        $gallera->delete();

        return response()->noContent();
    }

    /**
     * Obtener estadísticas de la gallera
     */
    public function estadisticas(Gallera $gallera): \Illuminate\Http\JsonResponse
    {
        $user = request()->user();
        
        if ($gallera->id_user !== $user->id) {
            abort(403, 'No tienes permiso para ver las estadísticas de esta gallera');
        }

        $gallera->loadCount('gallos');

        $totalPeleas = \App\Models\Pelea::whereHas('gallo', function ($query) use ($gallera) {
            $query->where('id_gallera', $gallera->id);
        })->count();

        $peleasGanadas = \App\Models\Pelea::whereHas('gallo', function ($query) use ($gallera) {
            $query->where('id_gallera', $gallera->id);
        })->where('estado', 'Ganada')->count();

        return response()->json([
            'gallera' => new GalleraResource($gallera),
            'estadisticas' => [
                'total_gallos' => $gallera->gallos_count,
                'total_peleas' => $totalPeleas,
                'peleas_ganadas' => $peleasGanadas,
                'porcentaje_victorias' => $totalPeleas > 0
                    ? round(($peleasGanadas / $totalPeleas) * 100, 2)
                    : 0,
            ],
        ]);
    }
}
