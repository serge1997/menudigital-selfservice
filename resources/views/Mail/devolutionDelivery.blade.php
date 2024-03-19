<html>
    <body style="font-family: 'Segoe UI'; padding: 12px; background-color: #f4f4f5;">
        <div style="width: 90%; margin: auto; background-color: #f8fafc;">
            @php
                $date = new DateTime();
                $date = $date->format('d/m/Y');
            @endphp
            <div style="width: 70%; height: 340px; margin: auto;">
                <h3 style="color: #ef4444; text-decoration: underline; text-align: center;">Óla, uma nova devolução foi registrada</h3>
                <ul style="list-style: none; font-size: 1.1em; color: #71717a;">
                    <li style="padding: 3px;">#Data: {{ $date }}</li>
                    @if (isset($user))
                        <li style="padding: 3px;">#Devolução feita por : {{ $user->name }}</li>
                    @endif
                    @if (isset($requisition, $total))
                        <li style="padding: 3px;">#Valor da devolção: {{ $total }}</li>
                        <li style="padding: 3px;">#Código da requisição: {{ $requisition->requisition_code }}</li>
                    @endif
                </ul>
            </div>
        </div>

    </body>
</html>
