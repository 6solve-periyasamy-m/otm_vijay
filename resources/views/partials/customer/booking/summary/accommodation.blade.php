@php /** @var \App\Models\Booking\BookingTraveller $traveller */ @endphp
<x-customer.accordion id="accommodation-{{ $traveller->id }}-collapse" nobg>
    <x-slot:header class="card-body">
        <h2 class="col-md-12 mb-0">Accommodation</h2>
    </x-slot:header>

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
        @foreach($traveller->accommodation()->with('tourComponent', 'tourComponent.inventory', 'tourComponent.inventory.boardType', 'tourComponent.inventory.accommodation', 'tourComponent.inventory.accommodation.address')->get() as $component)
            <tr>
                <td data-content="Times">
                    {{ f_datetime($component->tourComponent->inventory->check_in) . ' to ' . f_datetime($component->tourComponent->inventory->check_out) }}
                </td>
                <td data-content="Description">
                    {{ $component->tourComponent->inventory->accommodation->name }}
                    ({{ $component->tourComponent->inventory->accommodation->address->region . ', ' .  $component->tourComponent->inventory->accommodation->address->country?->name}})
                    ({{ $component->tourComponent->inventory->boardType }})
                </td>
                @if($component->tourComponent->tour_component_type == 'Included')
                    <td colspan="2" data-content="Type">{{ $component->tourComponent->tour_component_type }}</td>
                @else
                    <td data-content="Type">{{ $component->tourComponent->tour_component_type }}</td>
                    <td data-content="Cost">{{ f_currency($component->tourComponent->tour_sales_price) }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</x-customer.accordion>
