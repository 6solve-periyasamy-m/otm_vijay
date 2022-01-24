@can('create', \App\Models\Tour::class)
@include('partials.fields.selector.adder',
            ['name' => 'Tour', 'field' => 'tour_id', 'value' => $tour_id ?? 0,
             'route' => 'tours', 'createRoute' => route('tours.create'),])
@else
@include('partials.fields.selector.default',
          ['name' => 'Tour', 'field' => 'tour_id', 'value' => $tour_id ?? 0,
           'route' => 'tours',])
@endcan
@if(!isset($update))
@can('create', \App\Models\Customer::class)
@include('partials.fields.selector.adder',
            ['name' => 'Lead Booker', 'field' => 'lead_booker_id', 'value' => null,
             'route' => 'customers', 'createRoute' => route('customers.create'),])
@else
@include('partials.fields.selector.default',
        ['name' => 'Lead Booker', 'field' => 'lead_booker_id', 'value' => null,
         'route' => 'customers',])
@endcan
@else
@include('partials.fields.text', ['name' => 'Deposit', 'field' => 'deposit', 'value' => $deposit ?? null, ])
@endif
@include('partials.fields.datetime', ['name' => 'Ordered On', 'field' => 'ordered_on', 'value' => $ordered_on ?? null, ])
@include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'internal_notes', 'value' => $internal_notes ?? null, ])
@include('partials.fields.textarea', ['name' => 'External Notes', 'field' => 'external_notes', 'value' => $external_notes ?? null, ])
@if(isset($update))
    @include('partials.fields.ckeditor', ['name' => 'Invoice Footer', 'field' => 'invoice_footer', 'value' => $order->invoice_footer, ])
@endif
@include('partials.fields.submit')
