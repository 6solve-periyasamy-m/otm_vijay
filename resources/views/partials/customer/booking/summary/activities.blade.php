@php /** @var \App\Models\Booking\BookingTraveller $traveller */ @endphp
 <x-customer.accordion id="activities-{{ $traveller->id }}-collapse" nobg>
     <x-slot:header>
         <h2 class="col-md-12 mb-0">Activities</h2>
     </x-slot:header>
     If the Included Activity is Out of Stock you must select an Upgrade in order to proceed with your booking, the next available will be automatically selected, but you can select a different Upgrade from the dropdown.
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
         @foreach($traveller->activities()->with('tourComponent', 'tourComponent.inventory', 'tourComponent.inventory.activity', 'tourComponent.inventory.ticketType', 'tourComponent.inventory.activity.address')->get() as $component)
             <tr component="{{ $component->id }}">
                 <td data-content="Times">
                     {{ f_datetime($component->tourComponent->inventory->starts_at) . ' to ' . f_datetime($component->tourComponent->inventory->ends_at) }}
                 </td>
                 <td data-content="Description">
                     {{ $component->tourComponent->inventory->activity->name }} ({{ $component->tourComponent->inventory->activity->address }}) ({{ $component->tourComponent->inventory->ticketType }})
                 </td>
                 @if($component->tourComponent->tour_component_type == 'Included')
                     <td colspan="2" data-content="Type">{{ $component->tourComponent->tour_component_type }}</td>
                 @else
                     <td data-content="Type">{{ $component->tourComponent->tour_component_type }}</td>
                     <td data-content="Cost">{{ f_currency($component->tourComponent->tour_sales_price) }}</td>
                 @endif
                 <td data-content="Upgrades">
                     @if(count($component->tourComponent->repository->getUpgradeKeyMap($traveller->booking->travellers()->count())) < 2)
                         @if($component->tourComponent->tour_component_type == 'Included')
                             No Upgrades Available
                         @elseif($component->tourComponent->tour_component_type == 'Upgrade')
                             No Downgrades Available
                         @else
                             <a href="{{ route('customer-booking.remove-addon',
                                            ['bookingUrl' => $booking->tour->booking_form_url, 'token' => $booking->token, 'type' => 'activity',
                                             'id' => $component->tourComponent->id,]) }}"
                                class="btn btn-danger ms-1">-</a>
                         @endif
                     @else
                         @include('partials.fields.selector.adder-preset-booking',
                             ['field' => 'activity_' . $component->id . '_upgrade', 'preselect' => false,
                             'createRoute' => '#', 'onclick' => 'applyActivityUpgrade("activity_' . $component->id . '_upgrade-input", this)', 'target' => '',
                             'selected' => $component->tourComponent->repository->getUpgradeId(), 'options' => $component->tourComponent->repository->getBookingUpgradeKeyMap($traveller->booking->travellers()->count()),])
                     @endif
                 </td>
             </tr>
         @endforeach
         </tbody>
     </table>
 </x-customer.accordion>
