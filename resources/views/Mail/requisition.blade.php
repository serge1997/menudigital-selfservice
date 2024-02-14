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
<body>
    <div class="row">
        @php
            $date = new DateTime();
            $date = $date->format('d/m/Y');
        @endphp
        <div class="col-md-8">
            <h3>Óla, uma nova requisição foi enviada</h3>
            <ul class="list-group">
                <li class="list-group-item">Data: {{ $date }}</li>
                @if (isset($user))
                    <li class="list-group-item">Requerente: {{ $user->name }}</li>
                @endif
                @if (isset($requisition))
                    <li class="list-group-item">Data de entrega (expetativa do requerente): {{ $requisition->delivery_date }}</li>
                    <li class="list-group-item">Código da requisição: {{ $requisition->requisition_code }}</li>
                @endif
            </ul>
        </div>
    </div>
</body>
</html>
