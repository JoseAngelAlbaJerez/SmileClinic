<!DOCTYPE html>
<html>

<head>
    <title>Reporte Ingresos</title>

<body>

    <style>
        /*
  Common invoice styles. These styles will work in a browser or using the HTML
  to PDF anvil endpoint.
*/

        body {
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr td {
            padding: 0;
        }

        table tr td:last-child {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .large {
            font-size: 2em;
        }

        .total {
            font-weight: bold;
            color: #fb7578;
        }


        .invoice-info-container {
            font-size: 0.875em;
            margin-left: 15px;
        }

        .invoice-info-container td {
            padding: 4px 0;
        }

        .client-name {
            font-size: 2em;
            vertical-align: top;
        }

        .line-items-container {
            margin: 20px 0;
            font-size: 0.875em;
        }

        .line-items-container th {
            text-align: left;
            color: var(--background);
            border-bottom: 2px solid #ddd;
            padding: 10px 0 15px 0;
            font-size: 1em;
            text-transform: uppercase;
        }

        .line-items-container th:last-child {
            text-align: right;
        }

        .line-items-container td {
            padding: 15px 0;
        }

        .line-items-container tbody tr:first-child td {
            padding-top: 25px;
        }

        .line-items-container.has-bottom-border tbody tr:last-child td {
            padding-bottom: 25px;
            border-bottom: 2px solid #ddd;
        }

        .line-items-container.has-bottom-border {
            margin-bottom: 0;
        }

        .line-items-container th.heading-quantity {
            width: 50px;
        }

        .line-items-container th.heading-price {
            text-align: right;
            width: 100px;
        }

        .line-items-container th.heading-subtotal {
            width: 100px;
        }

        .payment-info {
            width: 38%;
            font-size: 1em;
            line-height: 1.5;
        }

        .footer {
            margin-top: 100px;
        }

        .footer-thanks {
            font-size: 1.125em;
        }

        .footer-thanks img {
            display: inline-block;
            position: relative;
            top: 1px;
            width: 16px;
            margin-right: 4px;
        }

        .footer-info {
            float: right;
            margin-top: 5px;
            font-size: 0.75em;
            color: #ccc;
        }

        .footer-info span {
            padding: 0 5px;
            color: black;
        }

        .footer-info span:last-child {
            padding-right: 0;
        }

        td {
            font-size: 1em;
        }
    </style>



    <table class="invoice-info-container">
        <tr>
            <td rowspan="2" class="client-name">
                <div class="logo-container">

                </div>
            </td>
            <td>
                Smile Clinic
            </td>
        </tr>
        <tr>
            <td>
                (809) 970-4382
            </td>
        </tr>
        <tr>
            <td>
                <p><strong>Paciente:</strong> {{ $budget->patient->first_name ?? '' }}</p>
                <p><strong>Doctor:</strong> {{ $budget->doctor->name ?? '' }}</p>
                <p><strong>Cuenta por cobrar:</strong> {{ $budget->CXC->balance ?? 'No asignada' }}</p>

            </td>
            <td>
                Duarte 5, Cayetano Germosén 56000
            </td>
        </tr>
        <tr>
            <td>
                Fecha: <strong>{{ $budget->created_at }} </strong>
            </td>
            <td>
                SmileClinic@gmail.com
            </td>
        </tr>
    </table>

    <table class="line-items-container">
        <thead>
            <th>#</th>
            <th>Procedimiento</th>

            <th>Cantidad</th>
            <th>Monto</th>
            <th>Descuento</th>
            <th>Total</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($budget->budgetdetail as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->procedure->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->amount, 2) }}</td>
                    <td>{{ $detail->discount }}%</td>
                    <td>{{ number_format($detail->total, 2) }}</td>
                </tr>
            @endforeach


        </tbody>
    </table>

    <hr style="color: #ddd;">




</body>

<script></script>

</html>
