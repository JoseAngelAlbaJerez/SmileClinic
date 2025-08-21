<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Paciente</title>
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #166088;
            --accent-color: #4fc3f7;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --light-text: #7f8c8d;
        }

        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            color: var(--dark-text);
            background-color: #fff;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;

            text-align: center;
            position: relative;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .header::after {
            content: "";
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 40px;
            background-color: white;
            transform: skewY(-2deg);
            z-index: 1;
        }

        .patient-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #fff;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 40px;
            font-weight: bold;
            border: 4px solid white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .content {
            padding: 40px;
        }

        .section {
            margin-bottom: 30px;
            position: relative;
        }

        .section h2 {
            font-size: 18px;
            color: var(--secondary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-color);
            display: flex;
            align-items: center;
        }

        .section h2::before {
            content: "";
            display: inline-block;
            width: 8px;
            height: 20px;
            background-color: var(--accent-color);
            margin-right: 10px;
            border-radius: 4px;
        }

        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }

        .data-item {
            background-color: var(--light-bg);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .data-item:hover {
            transform: translateY(-3px);
        }

        .data-label {
            font-weight: 600;
            color: var(--light-text);
            font-size: 13px;
            margin-bottom: 5px;
        }

        .data-value {
            font-size: 15px;
            color: var(--dark-text);
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-inactive {
            background-color: #ffebee;
            color: #c62828;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: var(--light-bg);
            color: var(--light-text);
            font-size: 12px;
            border-top: 1px solid #eee;
        }

        .watermark {
            position: fixed;
            bottom: 50px;
            right: 50px;
            opacity: 0.1;
            font-size: 120px;
            font-weight: bold;
            color: var(--primary-color);
            pointer-events: none;
            z-index: -1;
            transform: rotate(-15deg);
        }

        @media print {
            .container {
                box-shadow: none;
            }
            .footer {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="patient-photo">
                {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
            </div>

        </div>

        <div class="content">
            <div class="section">
                <h2>Información Personal</h2>
                <div class="data-grid">
                    <div class="data-item">
                        <div class="data-label">Nombre </div>
                        <div class="data-value">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">DNI</div>
                        <div class="data-value">{{ $patient->DNI }}</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Fecha de Nacimiento</div>
                        <div class="data-value">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} años)</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Teléfono</div>
                        <div class="data-value">{{ $patient->phone_number }}</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Dirección</div>
                        <div class="data-value">{{ $patient->address }}</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">ARS</div>
                        <div class="data-value">{{ $patient->ars }}</div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Motivo de Consulta</div>
                        <div class="data-value">{{ $patient->motive }}</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Historial Médico</h2>
                <div class="data-grid">
                    <div class="data-item">
                        <div class="data-label">Complicaciones Médicas</div>
                        <div class="data-value">
                            {{ $patient->complications ? 'Sí' : 'No' }}
                            @if ($patient->complications)
                                <div style="margin-top: 8px; font-size: 13px; color: #666;">
                                    {{ $patient->complications_detail }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Alergias</div>
                        <div class="data-value">
                            {{ $patient->alergies ? 'Sí' : 'No' }}
                            @if ($patient->alergies)
                                <div style="margin-top: 8px; font-size: 13px; color: #666;">
                                    {{ $patient->alergies_detail }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="data-item">
                        <div class="data-label">Medicamentos Actuales</div>
                        <div class="data-value">
                            {{ $patient->drugs ? 'Sí' : 'No' }}
                            @if ($patient->drugs)
                                <div style="margin-top: 8px; font-size: 13px; color: #666;">
                                    {{ $patient->drugs_detail }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Estado del Paciente</h2>
                <div class="data-grid">
                    <div class="data-item">
                        <div class="data-label">Estado Actual</div>
                        <div class="data-value">
                            <span class="status-badge {{ $patient->active ? 'status-active' : 'status-inactive' }}">
                                {{ $patient->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            Reporte generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} | Sistema de Gestión de Pacientes
        </div>
    </div>

    <div class="watermark">CONFIDENCIAL</div>
</body>
</html>
