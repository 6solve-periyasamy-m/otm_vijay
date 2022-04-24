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
    <div class="card">
        <div class="card-body">
            <h2 class="col-md-12 mb-0">Travellers</h2>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            Group assignments may differ slightly if incorrect group sizes were provided.
            <hr class="splitter">
            <table class="table table-striped text-center">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Room Type</th>
                    <th scope="col">Group</th>
                </tr>
                </thead>
                <tbody>
                @foreach($travellers as $traveller)
                    <tr>
                        <td>{{ $traveller->customer->full_name }}</td>
                        <td>{{ $traveller->room_type }}</td>
                        <td>{{ $traveller->group->name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <a href="{{ route('customer-booking.index', ['bookingUrl' => $tour->booking_form_url, 'token' => $token]) }}"
               class="btn btn-info text-white float-end" onclick="return confirm('Warning: Editing order details will clear add-ons/upgrades. Continue?');">Edit Order Details</a>
        </div>
    </div>
    @foreach($customers as $customerData)
        <div class="card">
            <div class="card-body">
                <h2 class="col-md-12 mb-0">Accommodation</h2>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
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
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2 class="col-md-12 mb-0">Activities</h2>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
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
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2 class="col-md-12 mb-0">Flights</h2>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
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
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2 class="col-md-12 mb-0">Transport</h2>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
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
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2 class="col-md-12 mb-0">Add-ons and Extras</h2>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
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
            </div>
        </div>
        @break
    @endforeach
    <div class="card">
        <div class="card-body">
            <h2 class="col-md-12 mb-0">Cost Summary</h2>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped text-center">
                <thead>
                <tr>
                    <th scope="col">Description</th>
                    <th scope="col">Cost</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Base Cost</td>
                    <td>{{ StringFormatter::formatCurrency($billing['cost']) }}</td>
                    <td>{{ $billing['customers'] }}</td>
                    <td>{{ StringFormatter::formatCurrency($billing['cost'] * $billing['customers']) }}</td>
                </tr>
                @if($billing['additionals'] > 0)
                <tr>
                    <td>Additional Costs (As Above)</td>
                    <td>{{ StringFormatter::formatCurrency($billing['additionals'] / $billing['customers']) }}</td>
                    <td>{{ $billing['customers'] }}</td>
                    <td>{{ StringFormatter::formatCurrency($billing['additionals']) }}</td>
                </tr>
                @endif
                @if($billing['single_occupants'] > 0)
                <tr>
                    <td>Single Occupancy Surcharge</td>
                    <td>{{ StringFormatter::formatCurrency($billing['surcharge'])}}</td>
                    <td>{{ $billing['single_occupants'] }}</td>
                    <td>{{ StringFormatter::formatCurrency($billing['surcharge'] * $billing['single_occupants']) }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="3">Total Cost</td>
                    <td>{{ StringFormatter::formatCurrency($billing['total']) }}</td>
                </tr>
                <tr>
                    <td>Deposit (Due Today)</td>
                    <td>{{ StringFormatter::formatCurrency($billing['deposit']) }}</td>
                    <td>{{ $billing['customers'] }}</td>
                    <td>{{ StringFormatter::formatCurrency($billing['today']) }}</td>
                </tr>
                </tbody>
            </table>
            <hr class="splitter">
            <form class="form-material" action="{{ route('customer-booking.deposit', ['bookingUrl' => $tour->booking_form_url, 'token' => $token]) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="booking_reference" id="form-booking-reference">
                <div class="form-material row">
                    <div class="form-group col-12 col-xl-10">
                        <input class="form-control form-control-line" name="amount" type="text" placeholder="Amount to Pay" value="{{ $billing['today'] }}" required/>
                    </div>
                    <div class="form-group col-12 col-xl-2">
                        <input class="form-control form-control-line" type="submit" value="Make Payment">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
