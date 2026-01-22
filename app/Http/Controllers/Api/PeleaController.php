<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeleaResource;
use App\Models\Gallo;
use App\Models\Pelea;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class PeleaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        $gallos = Gallo::where('id_user', $user->id);

        $gallosIds = $gallos->pluck('id');
        $query = Pelea::whereIn('id_gallo', $gallosIds);

        // Filtrar por gallo
        if ($request->filled('id_gallo')) {
            $query->where('id_gallo', $request->id_gallo);
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtrar por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        // Peleas próximas
        if ($request->boolean('proximas')) {
            $query->where('fecha', '>=', now())
                ->where('estado', 'Pendiente');
        }

        // Incluir relaciones
        $query->with('gallo');

        $query->orderBy('fecha', 'desc');

        $peleas = $query->paginate($request->get('per_page', 15));

        return PeleaResource::collection($peleas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): PeleaResource
    {
        $validated = $request->validate([
            'id_gallo' => 'required|exists:gallos,id',
            'fecha' => 'required|date',
            'lugar' => 'required|string|max:255',
            'estado' => ['required', Rule::in(['Pendiente', 'Ganada', 'Perdida', 'Empatada'])],
        ]);

        $user = $request->user();

        $gallo = Gallo::find($validated['id_gallo']);
        if ($gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para registrar peleas de este gallo');
        }

        $pelea = Pelea::create($validated);

        return new PeleaResource($pelea->load('gallo'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelea $pelea): PeleaResource
    {
        $user = request()->user();

        if (!$pelea || $pelea->gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para ver esta pelea');
        }


        $pelea->load('gallo');

        return new PeleaResource($pelea);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelea $pelea): PeleaResource
    {
        $user = $request->user();
        if (!$pelea || $pelea->gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para actualizar esta pelea');
        }



        $validated = $request->validate([
            'id_gallo' => 'sometimes|required|exists:gallos,id',
            'fecha' => 'sometimes|required|date',
            'lugar' => 'sometimes|required|string|max:255',
            'estado' => ['nullable', Rule::in(['Pendiente', 'Ganada', 'Perdida', 'Empatada'])],
        ]);

        if (isset($validated['id_gallo'])) {
            $gallo = Gallo::find($validated['id_gallo']);
            if ($gallo->id_user !== $user->id) {
                abort(403, 'No tienes permiso para asignar este gallo');
            }
        }

        $pelea->update($validated);

        return new PeleaResource($pelea->load('gallo'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelea $pelea): Response
    {
        $user = request()->user();
        if (!$pelea || $pelea->gallo->id_user !== $user->id) {
            abort(403, 'No tienes permiso para eliminar esta pelea');
        }



        $pelea->delete();

        return response()->noContent();
    }

    /**
     * Actualizar resultado de la pelea
     */
    public function actualizarResultado(Request $request, Pelea $pelea): PeleaResource
    {
        $validated = $request->validate([
            'estado' => ['required', Rule::in(['Pendiente', 'Ganada', 'Perdida', 'Empatada'])],
        ]);

        $pelea->update($validated);

        return new PeleaResource($pelea->load('gallo'));
    }
}
