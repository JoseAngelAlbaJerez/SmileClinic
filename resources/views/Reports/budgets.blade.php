<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Presupuesto Smile Clinic</title>
    <style>
        @font-face {
            font-family: 'Montserrat';
            src: url('{{ public_path('fonts/Montserrat-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            color: #322d28;
            background-color: #ffffff;
            margin: 40px;
            font-size: 15px;
        }

        .invoice {
            width: 100%;
        }

        .invoice .header td img {
            max-width: 200px;
        }

        .invoice .header h2 {
            font-family: 'Montserrat', sans-serif;
            text-align: right;
            font-size: 2rem;
            color: pink;
            margin: 0;
        }

        .invoice .intro td {
            padding-top: 15px;
            vertical-align: top;
        }

        .invoice .intro td:nth-child(2) {
            text-align: right;
        }

        .invoice .num {
            font-weight: 200;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.8em;
        }

        .invoice .details td {
            padding-top: 30px;
        }

        .invoice table.inner-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice table.inner-table th,
        .invoice table.inner-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #c8c3be;
            text-align: left;
        }

        .invoice table.inner-table thead {
            background-color: pink;
            color: white;
        }

        .invoice .totals {
            margin-top: 30px;
            width: 100%;
        }

        .invoice .totals td {
            padding: 5px 10px;
            font-size: 0.9em;
        }

        .invoice .totals td:nth-child(2) {
            text-align: right;
        }

        .invoice .totals .total td {
            font-size: 1.2em;
            font-weight: bold;
            padding-top: 10px;
        }

        h5 {
            font-size: 1em;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-family: 'Montserrat', sans-serif;
            color: pink;
            margin-bottom: 5px;
        }

        .additional-info {
            margin-top: 40px;
            font-size: 0.9em;
        }

        .additional-info p {
            line-height: 1.5em;
            margin: 2px 0;
        }

        .footer {
            margin-top: 60px;
            font-size: 0.85em;
            text-align: center;
            color: #999;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <table class="invoice">
        <tr class="header">
            <td>
                <img height="50" width="200" src="{{ public_path('img/Logo-COLOR.jpg') }}">
            </td>
            <td>
                <h2>Presupuestos</h2>
            </td>
        </tr>
        <tr class="intro">
            <td>

                Fecha: <strong>{{ \Carbon\Carbon::parse(now()->format('d/m/Y')) }}</strong><br>
            </td>
            <td>
                <span class="num">Presupuesto ID: #</span><br>
                <span class="num">Balance:

                </span><br>
            </td>
        </tr>
        <tr class="details">
            <td colspan="2">
                <table class="inner-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Paciente</th>
                            <th>Tipo</th>
                            <th>Doctor</th>
                            <th>Fecha de Emision</th>

                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sumaTotal = 0; @endphp
                        @foreach ($budgets as $index => $budget)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="text-align:left;">{{ $budget->patient->first_name }}
                                    {{ $budget->patient->last_name }}</td>
                                <td>{{ $budget->type }}</td>
                                <td style="text-align:left;">{{ $budget->doctor->name }}
                                    {{ $budget->doctor->last_name }}</td>
                                <td style="text-align:left;">{{ $budget->emission_date }}</td>
                                <td style="text-align:left;">$ {{ number_format($budget->total, 2) }}</td>
                            </tr>

                            @php $sumaTotal += $budget->total; @endphp

                            @if (($index + 1) % 10 === 0 && $index + 1 !== count($budgets))
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="page-break"></div>
            </td>
        </tr>
        <tr class="details">
            <td colspan="2">
                <table class="inner-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Paciente</th>
                            <th>Tipo</th>
                            <th>Doctor</th>
                            <th>Fecha de Emision</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </td>
        </tr>
        <tr class="totals">
            <td></td>
            <td>
                <table class="totals">
                    <tr>
                        <td>Subtotal</td>
                        <td>$ {{ number_format($sumaTotal, 2) }}</td>
                    </tr>
                    <tr class="total">
                        <td>Total</td>
                        <td>$ {{ number_format($sumaTotal, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <section class="additional-info">
        <h5>Información de contacto</h5>
        <p>Smile Clinic</p>
        <p>Duarte 5, Cayetano Germosén 56000</p>
        <p>Tel: (809) 970-4382</p>
        <p>Email: SmileClinic@gmail.com</p>
    </section>

    <div class="footer">
        Este reporte fue generado automáticamente por el sistema Smile Clinic.
    </div>
</body>

</html>
