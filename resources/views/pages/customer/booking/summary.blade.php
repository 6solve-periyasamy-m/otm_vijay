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
    <div class="card"><div class="card-body">
    @foreach($customers as $customerData)
        <hr class="splitter">
        <div class="form-group col-md-12">
            <h5 class="col-md-12 mb-0">Accommodation</h5>
        </div>
        <hr class="splitter">
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
        <hr class="splitter">
        <div class="form-group col-md-12">
            <h5 class="col-md-12 mb-0">Activities</h5>
        </div>
        <hr class="splitter">
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
                            @if($data['component']->tourComponent->tour_component_type == 'Included')
                                No Upgrades Available
                            @else
                                <a href="{{ route('customer-booking.remove-addon',
                                        ['bookingUrl' => $tour->booking_form_url, 'token' => $token, 'type' => 'activity',
                                         'id' => $data['component']->tourComponent->id,]) }}"
                                   class="btn btn-danger ms-1">-</a>
                            @endif
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
        <hr class="splitter">
        <div class="form-group col-md-12">
            <h5 class="col-md-12 mb-0">Flights</h5>
        </div>
        <hr class="splitter">
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
        <hr class="splitter">
        <div class="form-group col-md-12">
            <h5 class="col-md-12 mb-0">Transport</h5>
        </div>
        <hr class="splitter">
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
        <hr class="splitter">
        <div class="form-group col-md-12">
            <h5 class="col-md-12 mb-0">Add-ons and Extras</h5>
        </div>
        <table id="merchandise-table" class="table table-striped table-responsive-sm text-center">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Component Type</th>
                <th scope="col">Cost</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($customerData['addons'] as $bookingComponent)
                <tr>
                    <td>{{ $bookingComponent['name'] }}</td>
                    @if($bookingComponent['type'] === 'Included')
                        <td colspan="2">
                            {{ $bookingComponent['type'] }}
                        </td>
                    @else
                        <td>
                            {{ $bookingComponent['type'] }}
                        </td>
                        <td>
                            {{ StringFormatter::formatCurrency($bookingComponent['cost']) }}
                        </td>
                    @endif
                    <td>
                        @if($bookingComponent['owned'])
                            <a href="{{ route('customer-booking.remove-addon',
                                        ['bookingUrl' => $tour->booking_form_url, 'token' => $token, 'type' => $bookingComponent['component'],
                                         'id' => $bookingComponent['id'],]) }}"
                               class="btn btn-danger ms-1">-</a>
                        @else
                            <a href="{{ route('customer-booking.purchase-addon',
                                        ['bookingUrl' => $tour->booking_form_url, 'token' => $token, 'type' => $bookingComponent['component'],
                                         'id' => $bookingComponent['id'],]) }}"
                               class="btn btn-success ms-1">+</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        <hr class="splitter">
        @break
    @endforeach
    <hr class="splitter"/>
    Base Cost: {{ StringFormatter::formatCurrency($billing['cost']) }} x {{ $billing['customers'] }} = {{ StringFormatter::formatCurrency($billing['cost'] * $billing['customers']) }}<br/>
    Additional Costs (As Above): {{ StringFormatter::formatCurrency($billing['additionals']) }}<br/>
    Single Occupancy Surcharge: {{ StringFormatter::formatCurrency($billing['surcharge']) }} x {{ $billing['single_occupants'] }} = {{ StringFormatter::formatCurrency($billing['surcharge'] * $billing['single_occupants']) }}<br/>
    Total Due: {{ StringFormatter::formatCurrency($billing['total']) }}<br/>
    Deposit: {{ StringFormatter::formatCurrency($billing['deposit']) }} x {{ $billing['customers'] }} = {{ StringFormatter::formatCurrency($billing['today']) }}<br/>
    </div></div>
@endsection
