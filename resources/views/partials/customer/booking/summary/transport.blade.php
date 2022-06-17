@php /** @var \App\Models\Booking\BookingTraveller $traveller */ @endphp
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
            @foreach($traveller->transport()->with('tourComponent', 'tourComponent.inventory', 'tourComponent.inventory.transport')->get() as $component)
                <tr>
                    <td>{{ f_datetime($component->tourComponent->inventory->departs_at) . ' to ' . f_datetime($component->tourComponent->inventory->arrives_at) }}</td>
                    <td>{{ $component->tourComponent->inventory->transport->name }}</td>
                    @if($component->tourComponent->tour_component_type == 'Included')
                        <td colspan="2">{{ $component->tourComponent->tour_component_type }}</td>
                    @else
                        <td>{{ $component->tourComponent->tour_component_type }}</td>
                        <td>{{ f_currency($component->tourComponent->tour_sales_price) }}</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>