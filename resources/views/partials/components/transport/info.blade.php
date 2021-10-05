<table style="width: 100%;">
    <tr>
        <td style="width: 20%; text-align: center; border: 1px solid black"><h2>{{ $transport->name }}</h2></td>
        <td style="width: 20%; text-align: center; border: 1px solid black"><h2>{{ $transport->transportType->name }}</h2></td>
        <td style="width: 60%; text-align: center; border: 1px solid black"><h2>{{ $transport->operator->name }}</h2></td>
    </tr>
    <tr>
        <td style="width: 20%; text-align: center; border: 1px solid black"><h2>{{ $transport->departureLocation->name }}</h2></td>
        <td style="width: 20%; text-align: center; border: 1px solid black"><h2>{{ $transport->arrivalLocation->name }}</h2></td>
        <td style="width: 60%; text-align: center; border: 1px solid black"><h2>{{ $transport->currency }}</h2></td>
    </tr>
</table>
{{ $transport->description }}
<a class="btn btn-success" href="{{route('transports.edit', ['transport' => $transport,])}}">Edit Transport</a>
