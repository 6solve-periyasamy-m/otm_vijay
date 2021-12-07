@include('partials.fields.selector.adder',
            ['name' => 'Customer', 'field' => 'customer_id', 'value' => $customer_id ?? 0,
             'route' => 'customers', 'createRoute' => route('customers.create')])
@include('partials.fields.text', ['name' => 'Tour Cost', 'field' => 'tour_cost', 'value' => $tour_cost ?? null])
@include('partials.fields.text', ['name' => 'Single Occupancy Surcharge', 'field' => 'single_occupancy_surcharge', 'value' => $single_occupancy_surcharge ?? null])
@include('partials.fields.text', ['name' => 'Travel Insurer', 'field' => 'travel_insurer', 'value' => $travel_insurer ?? null])
@include('partials.fields.text', ['name' => 'Policy Number', 'field' => 'policy_number', 'value' => $policy_number ?? null])
@include('partials.fields.submit')
