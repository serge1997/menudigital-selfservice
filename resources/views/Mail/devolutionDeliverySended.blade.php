<x-mail::message>
### New delivery devolution notification

@php
    $date = date('d/m/Y H:i');
@endphp
@if ( isset($author, $delivery) )
    - Date: {{ $date }}
    - Author: {{ $author }}
    - Requisition code: {{ $delivery->requisition_code}}
@endif

<x-mail::button :url="'http://127.0.0.1:8000/dashboard/consult-delivery'">
ver
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
