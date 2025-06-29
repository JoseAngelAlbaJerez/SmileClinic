<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Paciente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 40px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 18px;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .data-row {
            display: flex;
            margin-bottom: 8px;
        }
        .data-label {
            width: 200px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Reporte de Paciente</h1>

    <div class="section">
        <h2>Información Personal</h2>
        <div class="data-row"><div class="data-label">Nombre:</div> {{ $patient->first_name }} {{ $patient->last_name }}</div>
        <div class="data-row"><div class="data-label">DNI:</div> {{ $patient->DNI }}</div>
        <div class="data-row"><div class="data-label">Fecha de Nacimiento:</div> {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d/m/Y') }}</div>
        <div class="data-row"><div class="data-label">Teléfono:</div> {{ $patient->phone_number }}</div>
        <div class="data-row"><div class="data-label">Dirección:</div> {{ $patient->address }}</div>
        <div class="data-row"><div class="data-label">ARS:</div> {{ $patient->ars }}</div>
        <div class="data-row"><div class="data-label">Motivo de Consulta:</div> {{ $patient->motive }}</div>
    </div>

    <div class="section">
        <h2>Historial Médico</h2>
        <div class="data-row">
            <div class="data-label">Complicaciones:</div>
            {{ $patient->complications ? 'Sí' : 'No' }}
        </div>
        @if ($patient->complications)
            <div class="data-row"><div class="data-label">Detalles:</div> {{ $patient->complications_detail }}</div>
        @endif

        <div class="data-row">
            <div class="data-label">Alergias:</div>
            {{ $patient->alergies ? 'Sí' : 'No' }}
        </div>
        @if ($patient->alergies)
            <div class="data-row"><div class="data-label">Detalles:</div> {{ $patient->alergies_detail }}</div>
        @endif

        <div class="data-row">
            <div class="data-label">Medicamentos:</div>
            {{ $patient->drugs ? 'Sí' : 'No' }}
        </div>
        @if ($patient->drugs)
            <div class="data-row"><div class="data-label">Detalles:</div> {{ $patient->drugs_detail }}</div>
        @endif
    </div>

    <div class="section">
        <h2>Estado</h2>
        <div class="data-row"><div class="data-label">Activo:</div> {{ $patient->active ? 'Sí' : 'No' }}</div>

    </div>

</body>
</html>
