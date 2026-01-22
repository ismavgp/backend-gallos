<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Rendimiento - {{ $gallo->placa }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #10b981;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .stat-box {
            display: table-cell;
            text-align: center;
            padding: 15px;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            width: 25%;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }
        
        .info-box {
            background-color: #f9fafb;
            padding: 12px;
            border-left: 4px solid #10b981;
            margin-bottom: 15px;
        }
        
        .info-box strong {
            display: block;
            color: #059669;
            margin-bottom: 5px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .table th {
            background-color: #f0fdf4;
            padding: 8px;
            text-align: left;
            border: 1px solid #bbf7d0;
            font-size: 10px;
            font-weight: bold;
            color: #059669;
        }
        
        .table td {
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }
        
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .badge-ganada {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-perdida {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-empatada {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-pendiente {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            padding: 10px 0;
            border-top: 1px solid #e5e7eb;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 5px;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #10b981;
            text-align: center;
            line-height: 20px;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE RENDIMIENTO</h1>
        <p>{{ $gallo->placa }} - {{ $gallo->name }}</p>
        <p>Periodo: {{ $periodo['inicio'] }} - {{ $periodo['fin'] }}</p>
    </div>

    <!-- Resumen Ejecutivo -->
    <div class="section">
        <div class="section-title">Resumen Ejecutivo</div>
        
        <div class="stats-grid">
            <div class="stat-box">
                <span class="stat-value">{{ $estadisticas['total_peleas'] }}</span>
                <span class="stat-label">Peleas</span>
            </div>
            <div class="stat-box">
                <span class="stat-value">{{ $estadisticas['peleas_ganadas'] }}</span>
                <span class="stat-label">Ganadas</span>
            </div>
            <div class="stat-box">
                <span class="stat-value">{{ $estadisticas['peleas_perdidas'] }}</span>
                <span class="stat-label">Perdidas</span>
            </div>
            <div class="stat-box">
                <span class="stat-value">{{ $estadisticas['porcentaje_victorias'] }}%</span>
                <span class="stat-label">Efectividad</span>
            </div>
        </div>
        
        <div class="info-box">
            <strong>Tasa de Victoria</strong>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $estadisticas['porcentaje_victorias'] }}%">
                    {{ $estadisticas['porcentaje_victorias'] }}%
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Entrenamiento -->
    <div class="section">
        <div class="section-title">Entrenamiento</div>
        
        <div class="stats-grid">
            <div class="stat-box">
                <span class="stat-value">{{ $estadisticas['total_entrenamientos'] }}</span>
                <span class="stat-label">Total</span>
            </div>
            <div class="stat-box">
                <span class="stat-value">{{ number_format($estadisticas['total_minutos_entrenamiento'] / 60, 1) }}</span>
                <span class="stat-label">Horas</span>
            </div>
            <div class="stat-box">
                <span class="stat-value">{{ $estadisticas['promedio_duracion'] }}</span>
                <span class="stat-label">Promedio (min)</span>
            </div>
            <div class="stat-box">
                <span class="stat-value">{{ $estadisticas['total_minutos_entrenamiento'] }}</span>
                <span class="stat-label">Total (min)</span>
            </div>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Detalle de Peleas -->
    <div class="section">
        <div class="section-title">Detalle de Peleas</div>
        @if($peleas->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Lugar</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peleas as $index => $pelea)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pelea->fecha?->format('d/m/Y H:i') }}</td>
                            <td>{{ $pelea->lugar }}</td>
                            <td>
                                <span class="badge badge-{{ strtolower($pelea->estado) }}">
                                    {{ $pelea->estado }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #9ca3af; padding: 20px;">
                No hay peleas registradas en este periodo
            </p>
        @endif
    </div>

    <!-- Detalle de Entrenamientos -->
    <div class="section">
        <div class="section-title">Detalle de Entrenamientos</div>
        @if($entrenamientos->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Duración (min)</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entrenamientos as $index => $entrenamiento)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $entrenamiento->fecha?->format('d/m/Y') }}</td>
                            <td>{{ $entrenamiento->tipo_entrenamiento }}</td>
                            <td>{{ $entrenamiento->duracion_minutos }}</td>
                            <td>{{ Str::limit($entrenamiento->observaciones ?? '-', 40) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #9ca3af; padding: 20px;">
                No hay entrenamientos registrados en este periodo
            </p>
        @endif
    </div>

    <!-- Análisis de Rendimiento -->
    <div class="section">
        <div class="section-title">Análisis de Rendimiento</div>
        
        <div class="info-box">
            <strong>Resultado General</strong>
            <p>
                @if($estadisticas['porcentaje_victorias'] >= 70)
                    ✓ Excelente rendimiento. El gallo muestra una alta tasa de éxito.
                @elseif($estadisticas['porcentaje_victorias'] >= 50)
                    ⚠ Rendimiento aceptable. Considere revisar estrategias de entrenamiento.
                @elseif($estadisticas['total_peleas'] > 0)
                    ✗ Rendimiento bajo. Se recomienda intensificar el entrenamiento.
                @else
                    - No hay suficientes datos para análisis.
                @endif
            </p>
        </div>

        <div class="info-box">
            <strong>Volumen de Entrenamiento</strong>
            <p>
                @if($estadisticas['total_entrenamientos'] == 0)
                    ⚠ Sin entrenamientos registrados en el periodo.
                @elseif($estadisticas['total_entrenamientos'] < 5)
                    ⚠ Bajo volumen de entrenamiento. Considere incrementar la frecuencia.
                @else
                    ✓ Volumen de entrenamiento adecuado ({{ $estadisticas['total_entrenamientos'] }} sesiones).
                @endif
            </p>
        </div>

        <div class="info-box">
            <strong>Distribución de Resultados</strong>
            <p>
                Ganadas: {{ $estadisticas['peleas_ganadas'] }} | 
                Perdidas: {{ $estadisticas['peleas_perdidas'] }} | 
                Empatadas: {{ $estadisticas['peleas_empatadas'] }}
            </p>
        </div>
    </div>

    <div class="footer">
        <p>Generado el {{ $fecha_generacion }} | Sistema de Gestión de Gallos</p>
    </div>
</body>
</html>
