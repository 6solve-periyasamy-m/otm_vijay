@extends('pages.customer.booking.layout')

@section('footer-script')
    <script>
        function applyActivityUpgrade(selector, btn) {
            let upgrade_id = $('#' + selector).find(':selected').val();
            let component_id = $(btn).closest('tr').attr('component');
            if (upgrade_id != null && component_id != null) {
                $.post('{{ route('api.booking.upgrade-activity', ['token' => $token,]) }}',
                    {   '_token': '{{ csrf_token() }}',
                        'component_id': component_id,
                        'upgrade_id': upgrade_id
                    })
                    .done(function (xhr, textStatus, errorThrown) {
                        if (xhr.success) location.reload();
                        else alert(xhr.message);
                    })
                    .fail(function (xhr, textStatus, errorThrown) { alert(xhr.responseText); });
            }
        }
    </script>
@endsection

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
            </tbody>
        </table>
        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th scope="col">Times</th>
                <th scope="col">Description</th>
                <th scope="col">Type</th>
                <th scope="col">Cost</th>
                <th scope="col">Upgrades</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customerData['components']['activities'] as $data)
                <tr component="{{ $data['component']->id }}">
                    <td>{{ $data['time'] }}</td>
                    <td>{{ $data['description'] }}</td>
                    @if($data['cost'] == 0)
                        <td colspan="2">{{ $data['type'] }}</td>
                    @else
                        <td>{{ $data['type'] }}</td>
                        <td>{{ StringFormatter::formatCurrency($data['cost']) }}</td>
                    @endif
                    <td>
                        @if(count($data['component']->tourComponent->getUpgradeKeyMap()) < 2)
                            No Upgrades Available
                        @else
                            @include('partials.fields.selector.adder-preset',
                                ['field' => 'activity_' . $data['component']->id . '_upgrade', 'preselect' => false,
                                'createRoute' => '#', 'onclick' => 'applyActivityUpgrade("activity_' . $data['component']->id . '_upgrade-input", this)', 'target' => '',
                                'selected' => \App\Repository\TourRepository::getUpgradeIdFromActivity($data['component']->tourComponent), 'options' => $data['component']->tourComponent->getUpgradeKeyMap(),])
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
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
            </tbody>
        </table>
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
        @break
    @endforeach
    <hr class="splitter"/>
    Base Cost: {{ StringFormatter::formatCurrency($billing['cost']) }} x {{ $billing['customers'] }} = {{ StringFormatter::formatCurrency($billing['cost'] * $billing['customers']) }}<br/>
    Additional Costs (As Above): {{ StringFormatter::formatCurrency($billing['additionals']) }}<br/>
    Single Occupancy Surcharge: {{ StringFormatter::formatCurrency($billing['surcharge']) }} x {{ $billing['single_occupants'] }} = {{ StringFormatter::formatCurrency($billing['surcharge'] * $billing['single_occupants']) }}<br/>
    Total Due: {{ StringFormatter::formatCurrency($billing['total']) }}<br/>
    Deposit: {{ StringFormatter::formatCurrency($billing['deposit']) }} x {{ $billing['customers'] }} = {{ StringFormatter::formatCurrency($billing['today']) }}<br/>
@endsection
