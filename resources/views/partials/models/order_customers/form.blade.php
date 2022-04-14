@include('partials.fields.selector.adder',
            ['name' => 'Customer', 'field' => 'customer_id', 'value' => $customer_id ?? 0, 'route' => 'customers',
             'fullRoute' => route('api.available-customers.select', ['order' => $order,]), 'createRoute' => route('customers.create')])
@include('partials.fields.text', ['name' => 'Tour Cost', 'field' => 'tour_cost', 'value' => $tour_cost ?? $order->leadBooker->tour_cost ?? null])
@include('partials.fields.text', ['name' => 'Single Occupancy Surcharge', 'field' => 'single_occupancy_surcharge', 'value' => $single_occupancy_surcharge ??$order->leadBooker->single_occupancy_surcharge ?? null])
@include('partials.fields.text', ['name' => 'Travel Insurer', 'field' => 'travel_insurer', 'value' => $travel_insurer ?? null])
@include('partials.fields.text', ['name' => 'Policy Number', 'field' => 'policy_number', 'value' => $policy_number ?? null])
@include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'internal_notes', 'value' => $internal_notes ?? null, ])
@include('partials.fields.textarea', ['name' => 'External Notes', 'field' => 'external_notes', 'value' => $external_notes ?? null, ])
@include('partials.fields.textarea', ['name' => 'Accommodation Notes', 'field' => 'accommodation_notes', 'value' => $accommodation_notes ?? null, ])
@include('partials.fields.textarea', ['name' => 'Activity Notes', 'field' => 'activity_notes', 'value' => $activity_notes ?? null, ])
@include('partials.fields.textarea', ['name' => 'Flight Notes', 'field' => 'flight_notes', 'value' => $flight_notes ?? null, ])
@include('partials.fields.textarea', ['name' => 'Transport Notes', 'field' => 'transport_notes', 'value' => $transport_notes ?? null, ])
@include('partials.fields.submit')
