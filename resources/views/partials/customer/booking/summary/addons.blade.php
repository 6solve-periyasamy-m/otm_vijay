@php
    /**
     * @var \App\Repository\Abstracts\InventoryTourRepository[] $addons
     * @var \App\Models\Booking\BookingTraveller $traveller
     */
    $booking = $traveller->booking;
@endphp
<x-customer.accordion id="addons-{{ $traveller->id }}-collapse" nobg>
    <x-slot:header>
        <h2 class="col-md-12 mb-0">Add-ons and Extras</h2>
    </x-slot:header>
    <table id="merchandise-table" class="table table-striped table-responsive-sm text-center">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Component Type</th>
            <th scope="col">Cost</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($addons as $addonRepository)
            @php $bookingComponent = $addonRepository->getBookingComponent($traveller) @endphp
            <tr>
                <td data-content="Name">{{ $addonRepository->__toString() }}</td>
                @if($addonRepository->get()->tour_component_type === 'Included')
                    <td colspan="2" data-content="Component Type">
                        {{ $addonRepository->getTourComponentType() }}
                    </td>
                @else
                    <td data-content="Component Type">
                        {{ $addonRepository->getTourComponentType()  }}
                    </td>
                    <td data-content="Cost">
                        {{ f_currency($addonRepository->getCost()) }}
                    </td>
                @endif
                <td data-content="Actions">
                    @if(isset($bookingComponent))
                        <a href="{{ route('customer-booking.remove-addon',
                                        ['bookingUrl' => $booking->tour->booking_form_url,
                                         'token' => $booking->token,
                                         'type' => $addonRepository->getComponentType(),
                                         'id' => $addonRepository->get()->id,]) }}"
                           class="btn btn-danger ms-1">-</a>
                    @else
                        <a href="{{ route('customer-booking.purchase-addon',
                                        ['bookingUrl' => $booking->tour->booking_form_url,
                                         'token' => $booking->token,
                                         'type' => $addonRepository->getComponentType(),
                                         'id' => $addonRepository->get()->id,]) }}"
                           class="btn btn-success ms-1">+</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</x-customer.accordion>
