<x-mail::message>
### New requisition Notification

@php $date = date('d/m/Y H:i'); @endphp


@if ( isset( $author) )
    - Data: {{ $date }}
    - Author: {{ $author }}
@endif
@if (isset ($purchase))
    - Data de entrega (epetativa do requerente): {{ date('d/m/Y', strtotime($purchase->delivery_date)) }}.
    - Código da requisição: {{ $purchase->requisition_code }}.
@endif

<x-mail::button :url="'http://127.0.0.1:8000/dashboard/purchase/'">
ver
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
