<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesController extends Controller
{
    /**
     * Generar ficha técnica de gallo en PDF
     */
    public function fichaTecnicaGallo(Request $request)
    {
        $validated = $request->validate([
            'placa' => 'required|string|exists:gallos,placa',
        ]);

        $user = $request->user();

        // Buscar el gallo por placa
        $gallo = Gallo::where('placa', $validated['placa'])
            ->with([
                'padre',
                'madre',
                'user',
                'peleas' => function($query) {
                    $query->orderBy('fecha', 'desc');
                },
                'entrenamientos' => function($query) {
                    $query->orderBy('fecha', 'desc');
                },
                'vacunas' => function($query) {
                    $query->orderBy('fecha_aplicacion', 'desc');
                }
            ])
            ->first();

        // Verificar permisos
        if ($gallo->id_user !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para generar este reporte',
            ], 403);
        }

        // Calcular estadísticas
        $estadisticas = [
            'total_peleas' => $gallo->peleas->count(),
            'peleas_ganadas' => $gallo->peleas->where('estado', 'Ganada')->count(),
            'peleas_perdidas' => $gallo->peleas->where('estado', 'Perdida')->count(),
            'peleas_empatadas' => $gallo->peleas->where('estado', 'Empatada')->count(),
            'total_entrenamientos' => $gallo->entrenamientos->count(),
            'total_horas_entrenamiento' => round($gallo->entrenamientos->sum('duracion_minutos') / 60, 2),
            'total_vacunas' => $gallo->vacunas->count(),
            'edad_meses' => $gallo->fecha_nacimiento?->diffInMonths(now()),
            'edad_texto' => $this->calcularEdad($gallo->fecha_nacimiento),
        ];

        // Calcular porcentaje de victorias
        if ($estadisticas['total_peleas'] > 0) {
            $estadisticas['porcentaje_victorias'] = round(
                ($estadisticas['peleas_ganadas'] / $estadisticas['total_peleas']) * 100, 
                2
            );
        } else {
            $estadisticas['porcentaje_victorias'] = 0;
        }

        // Datos para el PDF
        $data = [
            'gallo' => $gallo,
            'estadisticas' => $estadisticas,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
        ];

        // Generar PDF
        $pdf = Pdf::loadView('reportes.ficha-tecnica-gallo', $data);
        
        // Configurar PDF
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOption('defaultFont', 'Arial');

        // Descargar o mostrar
        if ($request->get('download', true)) {
            return $pdf->download("ficha-tecnica-{$gallo->placa}.pdf");
        } else {
            return $pdf->stream("ficha-tecnica-{$gallo->placa}.pdf");
        }
    }

    /**
     * Generar reporte de rendimiento de gallo
     */
    public function reporteRendimiento(Request $request)
    {
        $validated = $request->validate([
            'placa' => 'required|string|exists:gallos,placa',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $user = $request->user();

        $gallo = Gallo::where('placa', $validated['placa'])
            ->where('id_user', $user->id)
            ->first();

        if (!$gallo) {
            return response()->json([
                'success' => false,
                'message' => 'Gallo no encontrado o no tienes permiso',
            ], 404);
        }

        // Filtrar por fechas si se proporcionan
        $peleaQuery = $gallo->peleas();
        $entrenamientoQuery = $gallo->entrenamientos();

        if ($request->filled('fecha_inicio')) {
            $peleaQuery->where('fecha', '>=', $validated['fecha_inicio']);
            $entrenamientoQuery->where('fecha', '>=', $validated['fecha_inicio']);
        }

        if ($request->filled('fecha_fin')) {
            $peleaQuery->where('fecha', '<=', $validated['fecha_fin']);
            $entrenamientoQuery->where('fecha', '<=', $validated['fecha_fin']);
        }

        $peleas = $peleaQuery->orderBy('fecha', 'desc')->get();
        $entrenamientos = $entrenamientoQuery->orderBy('fecha', 'desc')->get();

        $estadisticas = [
            'total_peleas' => $peleas->count(),
            'peleas_ganadas' => $peleas->where('estado', 'Ganada')->count(),
            'peleas_perdidas' => $peleas->where('estado', 'Perdida')->count(),
            'peleas_empatadas' => $peleas->where('estado', 'Empatada')->count(),
            'total_entrenamientos' => $entrenamientos->count(),
            'total_minutos_entrenamiento' => $entrenamientos->sum('duracion_minutos'),
            'promedio_duracion' => round($entrenamientos->avg('duracion_minutos') ?? 0, 2),
        ];

        if ($estadisticas['total_peleas'] > 0) {
            $estadisticas['porcentaje_victorias'] = round(
                ($estadisticas['peleas_ganadas'] / $estadisticas['total_peleas']) * 100, 
                2
            );
        } else {
            $estadisticas['porcentaje_victorias'] = 0;
        }

        $data = [
            'gallo' => $gallo,
            'peleas' => $peleas,
            'entrenamientos' => $entrenamientos,
            'estadisticas' => $estadisticas,
            'periodo' => [
                'inicio' => $validated['fecha_inicio'] ?? 'Inicio',
                'fin' => $validated['fecha_fin'] ?? 'Actualidad',
            ],
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = Pdf::loadView('reportes.rendimiento-gallo', $data);
        $pdf->setPaper('letter', 'portrait');

        if ($request->get('download', true)) {
            return $pdf->download("rendimiento-{$gallo->placa}.pdf");
        } else {
            return $pdf->stream("rendimiento-{$gallo->placa}.pdf");
        }
    }

    /**
     * Calcular edad en texto legible
     */
    private function calcularEdad($fechaNacimiento)
    {
        if (!$fechaNacimiento) {
            return 'N/A';
        }

        $diff = $fechaNacimiento->diff(now());
        
        $partes = [];
        
        if ($diff->y > 0) {
            $partes[] = $diff->y . ' año' . ($diff->y > 1 ? 's' : '');
        }
        
        if ($diff->m > 0) {
            $partes[] = $diff->m . ' mes' . ($diff->m > 1 ? 'es' : '');
        }
        
        if (empty($partes) && $diff->d > 0) {
            $partes[] = $diff->d . ' día' . ($diff->d > 1 ? 's' : '');
        }

        return empty($partes) ? 'Recién nacido' : implode(' y ', $partes);
    }
}
