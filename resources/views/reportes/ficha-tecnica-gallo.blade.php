<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha Técnica - {{ $gallo->placa }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background-color: #667eea;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 6px 10px;
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            width: 35%;
        }
        
        .info-value {
            display: table-cell;
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
        }
        
        .image-container {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background-color: #f9fafb;
            border: 2px dashed #d1d5db;
            border-radius: 5px;
        }
        
        .image-container img {
            max-width: 250px;
            max-height: 250px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .no-image {
            color: #9ca3af;
            font-style: italic;
            padding: 50px 0;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .stat-box {
            display: table-cell;
            text-align: center;
            padding: 15px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            width: 25%;
        }
        
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .table th {
            background-color: #f3f4f6;
            padding: 8px;
            text-align: left;
            border: 1px solid #e5e7eb;
            font-size: 10px;
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>FICHA TÉCNICA DEL GALLO</h1>
        <p>Placa: {{ $gallo->placa }} | {{ $gallo->name }}</p>
    </div>

    <!-- Información General -->
    <div class="section">
        <div class="section-title">Información General</div>
        
        @if($gallo->url_imagen)
            <div class="image-container">
                <img src="{{ public_path(str_replace('/storage', 'storage', $gallo->url_imagen)) }}" alt="{{ $gallo->name }}" onerror="this.style.display='none';">
            </div>
        @else
            <div class="image-container">
                <div class="no-image">Sin fotografía disponible</div>
            </div>
        @endif
        
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Placa</div>
                <div class="info-value">{{ $gallo->placa }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nombre</div>
                <div class="info-value">{{ $gallo->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Sexo</div>
                <div class="info-value">{{ $gallo->sexo == 'M' ? 'Macho' : 'Hembra' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Nacimiento</div>
                <div class="info-value">{{ $gallo->fecha_nacimiento?->format('d/m/Y') }} ({{ $estadisticas['edad_texto'] }})</div>
            </div>
            <div class="info-row">
                <div class="info-label">Color</div>
                <div class="info-value">{{ $gallo->color }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Peso</div>
                <div class="info-value">{{ number_format($gallo->peso, 2) }} kg</div>
            </div>
            <div class="info-row">
                <div class="info-label">Talla</div>
                <div class="info-value">{{ number_format($gallo->talla, 2) }} m</div>
            </div>
            <div class="info-row">
                <div class="info-label">Color de Patas</div>
                <div class="info-value">{{ $gallo->color_patas }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tipo de Cresta</div>
                <div class="info-value">{{ $gallo->tipo_cresta }}</div>
            </div>
        </div>
    </div>

    <!-- Información de Padres -->
    <div class="section">
        <div class="section-title">Información Genética</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Padre</div>
                <div class="info-value">
                    @if($gallo->padre)
                        {{ $gallo->padre->placa }} - {{ $gallo->padre->name }}
                    @else
                        No registrado
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Madre</div>
                <div class="info-value">
                    @if($gallo->madre)
                        {{ $gallo->madre->placa }} - {{ $gallo->madre->name }}
                    @else
                        No registrado
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="section">
        <div class="section-title">Estadísticas Generales</div>
        <div class="stats-grid">
            <div class="stat-box">
                <span class="stat-value">{{ $estadisticas['total_peleas'] }}</span>
                <span class="stat-label">Total Peleas</span>
            </div>
            <div class="stat-box">
                <span class="stat-value">{{ $estadisticas['porcentaje_victorias'] }}%</span>
                <span class="stat-label">% Victorias</span>
            </div>
            <div class="stat-box">
                <span class="stat-value">{{ $estadisticas['total_entrenamientos'] }}</span>
                <span class="stat-label">Entrenamientos</span>
            </div>
            <div class="stat-box">
                <span class="stat-value">{{ $estadisticas['total_vacunas'] }}</span>
                <span class="stat-label">Vacunas</span>
            </div>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- Historial de Peleas -->
    <div class="section">
        <div class="section-title">Historial de Peleas ({{ $gallo->peleas->count() }})</div>
        @if($gallo->peleas->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Lugar</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gallo->peleas as $pelea)
                        <tr>
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
            <p style="text-align: center; color: #9ca3af; padding: 20px;">No hay peleas registradas</p>
        @endif
    </div>

    <!-- Historial de Entrenamientos -->
    <div class="section">
        <div class="section-title">Historial de Entrenamientos ({{ $gallo->entrenamientos->count() }})</div>
        @if($gallo->entrenamientos->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Duración</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gallo->entrenamientos as $entrenamiento)
                        <tr>
                            <td>{{ $entrenamiento->fecha?->format('d/m/Y') }}</td>
                            <td>{{ $entrenamiento->tipo_entrenamiento }}</td>
                            <td>{{ $entrenamiento->duracion_minutos }} min</td>
                            <td>{{ Str::limit($entrenamiento->observaciones ?? '-', 50) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #9ca3af; padding: 20px;">No hay entrenamientos registrados</p>
        @endif
    </div>

    <!-- Historial de Vacunas -->
    <div class="section">
        <div class="section-title">Historial de Vacunación ({{ $gallo->vacunas->count() }})</div>
        @if($gallo->vacunas->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Vacuna</th>
                        <th>Dosis</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gallo->vacunas as $vacuna)
                        <tr>
                            <td>{{ $vacuna->fecha_aplicacion?->format('d/m/Y') }}</td>
                            <td>{{ $vacuna->nombre_vacuna }}</td>
                            <td>{{ $vacuna->dosis }}</td>
                            <td>{{ Str::limit($vacuna->observaciones ?? '-', 50) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #9ca3af; padding: 20px;">No hay vacunas registradas</p>
        @endif
    </div>

    <div class="footer">
        <p>Generado el {{ $fecha_generacion }} | Sistema de Gestión de Gallos</p>
    </div>
</body>
</html>
