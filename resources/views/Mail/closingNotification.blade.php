<html>
    <body style="font-family: 'Segoe UI'; padding: 12px; background-color: #f4f4f5;">
        <div style="width: 90%; margin: auto; background-color: #f8fafc; font-size: 1.1em;">
            @php
                $total = 0;
            @endphp
            <div style="width: 70%; margin: auto;">
                <table style="color: #71717a;">
                    <thead>
                        <tr>
                        <th style="padding: 4px">Meio de Pagamento</th>
                        <th style="padding: 4px">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($data))
                            @foreach ($data as $value)
                                @php $total += $value->venda; @endphp
                                <tr>
                                    <td style="padding: 8px">{{ $value->stat_desc}}</td>
                                    <td style="padding: 8px">{{ $value->venda }} <small>R$</small></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th style="padding: 4px">Total: </th>
                            <th style="padding: 4px">{{ $total }} R$</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </body>
</html>
