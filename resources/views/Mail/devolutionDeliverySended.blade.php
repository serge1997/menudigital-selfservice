<x-mail::message>
### New delivery devolution notification

@php
    $date = date('d/m/Y H:i');
@endphp
@if ( isset($author, $delivered_at, $requisitionCode) )
    - Date: {{ $date }}
    - Date of delivery: {{ date('d/m/Y', strtotime($delivered_at)) }}
    - Author: {{ $author }}
    - Requisition code: {{ $requisitionCode }}
@endif

<x-mail::button :url="'http://127.0.0.1:8000/dashboard/consult-delivery'">
ver
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
