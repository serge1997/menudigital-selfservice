<div class="row">
    @php
        $date = new DateTime();
        $date = $date->format('d/m/Y');
    @endphp
    <div class="col-md-8">
        <h3>Óla, uma nova devolução foi registrada</h3>
        <ul style="padding: 8px;" class="list-group">
            <li class="list-group-item">Data: {{ $date }}</li>
            @if (isset($user))
                <li class="list-group-item">Devolução feita por : {{ $user->name }}</li>
            @endif
            @if (isset($requisition, $total))
                <li class="list-group-item">Valor da devolção: {{ $total }}</li>
                <li class="list-group-item">Código da requisição: {{ $requisition->requisition_code }}</li>
            @endif
        </ul>
    </div>
</div>
