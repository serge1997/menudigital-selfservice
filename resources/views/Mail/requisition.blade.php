<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<style>
    ul{
        list-style-type: none;
        padding: 4px;
    }
</style>
<body style="font-family: 'Segoe UI'; display: flex; align-items:center; justify-content: center; padding: 12px; background-color: #f4f4f5;">
    <div style="width: 90%; margin: auto; background-color: #f8fafc;">
        @php
            $date = new DateTime();
            $date = $date->format('d/m/Y');
        @endphp
        <div style="width: 70%; height: 340px; margin: auto;" class="col-md-8">
            <h3 style="color: #ef4444; text-decoration: underline; text-align: center;">Óla, uma nova requisição foi enviada</h3>
            <ul style="list-style: none; font-size: 1.1em; color: #71717a;" class="list-group">
                <li style="padding: 3px;">#Data: {{ $date }}</li>
                @if (isset($user))
                    <li style="padding: 3px;">#Requerente: {{ $user->name }}</li>
                @endif
                @if (isset($requisition))
                    <li style="padding: 3px;">#Data de entrega (expetativa do requerente): {{ date('d/m/Y', strtotime($requisition->delivery_date)) }}</li>
                    <li style="padding: 3px;">#Código da requisição: {{ $requisition->requisition_code }}</li>
                @endif
            </ul>
        </div>
    </div>
</body>
</html>
