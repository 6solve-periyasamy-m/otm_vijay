<style>
    .info-col {
        width: 25%;
        text-align: center;
        border: 1px solid black;
    }
</style>

<table style="width: 100%;">
    <tr>
        <td class="info-col"><h2>{{ $flight->airline->name }}</h2></td>
        <td class="info-col"><h2>{{ $flight->arrivalAirport->name }}</h2></td>
        <td class="info-col"><h2>{{ $flight->departureAirport->name }}</h2></td>
        <td class="info-col"><h2>{{ $flight->is_domestic ? "Domestic" : "International" }}</h2></td>
    </tr>
</table>
{{ $flight->notes }}
<a class="btn btn-success" href="{{route('flights.edit', ['flight' => $flight,])}}">Edit Flight</a>
