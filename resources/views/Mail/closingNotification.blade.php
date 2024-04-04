<x-mail::message>
## Closing resume notification
@php
    $total = 0;
@endphp
@if ( isset($order) )
<x-mail::table>
    |Paiement type                  | Value               |
    |------------------------------:|--------------------:|
    @foreach ( $order as $data)
    |{{ $data->stat_desc }}          |{{ $data->venda }} R$|
        @php $total += $data->venda @endphp
    @endforeach
</x-mail::table>
## Total: {{ $total }}
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
