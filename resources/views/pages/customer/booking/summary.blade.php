@extends('pages.customer.booking.layout')

@section('booking-body')
    @foreach($customers as $customerData)
        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th scope="col">Times</th>
                <th scope="col">Description</th>
                <th scope="col">Type</th>
                <th scope="col">Cost</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="4">Accommodation</td>
            </tr>
            @foreach($customerData['components']['accommodation'] as $data)
                <tr>
                    <td>{{ $data['time'] }}</td>
                    <td>{{ $data['description'] }}</td>
                    @if($data['cost'] == 0)
                        <td colspan="2">{{ $data['type'] }}</td>
                    @else
                        <td>{{ $data['type'] }}</td>
                        <td>{{ StringFormatter::formatCurrency($data['cost']) }}</td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan="4">Activities</td>
            </tr>
            @foreach($customerData['components']['activities'] as $data)
                <tr>
                    <td>{{ $data['time'] }}</td>
                    <td>{{ $data['description'] }}</td>
                    @if($data['cost'] == 0)
                        <td colspan="2">{{ $data['type'] }}</td>
                    @else
                        <td>{{ $data['type'] }}</td>
                        <td>{{ StringFormatter::formatCurrency($data['cost']) }}</td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan="4">Flights</td>
            </tr>
            @foreach($customerData['components']['flights'] as $data)
                <tr>
                    <td>{{ $data['time'] }}</td>
                    <td>{{ $data['description'] }}</td>
                    @if($data['cost'] == 0)
                        <td colspan="2">{{ $data['type'] }}</td>
                    @else
                        <td>{{ $data['type'] }}</td>
                        <td>{{ StringFormatter::formatCurrency($data['cost']) }}</td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan="4">Transport</td>
            </tr>
            @foreach($customerData['components']['transport'] as $data)
                <tr>
                    <td>{{ $data['time'] }}</td>
                    <td>{{ $data['description'] }}</td>
                    @if($data['cost'] == 0)
                        <td colspan="2">{{ $data['type'] }}</td>
                    @else
                        <td>{{ $data['type'] }}</td>
                        <td>{{ StringFormatter::formatCurrency($data['cost']) }}</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
    <hr class="splitter"/>
    Base Cost: {{ StringFormatter::formatCurrency($billing['cost']) }} x {{ $billing['customers'] }} = {{ StringFormatter::formatCurrency($billing['cost'] * $billing['customers']) }}<br/>
    Additional Costs (As Above): {{ StringFormatter::formatCurrency($billing['additionals']) }}<br/>
    Single Occupancy Surcharge: {{ StringFormatter::formatCurrency($billing['surcharge']) }} x {{ $billing['single_occupants'] }} = {{ StringFormatter::formatCurrency($billing['surcharge'] * $billing['single_occupants']) }}<br/>
    Total Due: {{ StringFormatter::formatCurrency($billing['total']) }}<br/>
    Deposit: {{ StringFormatter::formatCurrency($billing['deposit']) }} x {{ $billing['customers'] }} = {{ StringFormatter::formatCurrency($billing['today']) }}<br/>
@endsection
