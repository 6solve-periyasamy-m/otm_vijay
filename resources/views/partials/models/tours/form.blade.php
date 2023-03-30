@php /** @var \App\Models\Tour\Tour $tour */ $tour = $tour ?? null @endphp
@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null, 'width' => 8])
@include('partials.fields.selector.default',
        ['name' => 'Branding', 'field' => 'brand_id', 'value' => $tour?->brand_id ?? null,
         'route' => 'brands', 'width' => 4,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $description ?? null,])
@can('create', \App\Models\Tour\Event::class)
@include('partials.fields.selector.adder',
            ['name' => 'Event', 'field' => 'event_id', 'value' => $event_id ?? 0,
             'route' => 'events', 'createRoute' => route('events.create'), 'width' => 4,])
@else
@include('partials.fields.selector.default',
            ['name' => 'Event', 'field' => 'event_id', 'value' => $event_id ?? 0,
             'route' => 'events', 'width' => 4,])
@endcan
@include('partials.fields.dropdown', [
             'name' => 'ATOL Protected',
             'field' => 'atol_protected',
             'width' => 4,
             'selected' => $tour?->atol_protected ?? -1,
             'values' => [
                 -1 => "Match System (Currently: " . (flag('atol.enabled', true) ? 'Enabled' : 'Disabled') . ")",
                 0 => "Disabled",
                 1 => "Enabled",
             ]
         ])
@can('create', \App\Models\Tour\TourCategory::class)
@include('partials.fields.selector.adder',
            ['name' => 'Tour Category', 'field' => 'tour_category_id', 'value' => $tour_category_id ?? null,
             'route' => 'tour-categories', 'createRoute' => route('tour-categories.create'), 'width' => 4,])
@else
@include('partials.fields.selector.default',
        ['name' => 'Tour Category', 'field' => 'tour_category_id', 'value' => $tour_category_id ?? null,
         'route' => 'tour-categories', 'width' => 4,])
@endcan
<hr class="splitter"/>
@include('partials.fields.date',
            ['name' => 'Start Date', 'field' => 'date_from', 'value' => $date_from ?? null,
             'onChange' => 'changeDate($(\'#date_from-input\'), $(\'#date_to-input\'))', 'width' => 4, ])
@include('partials.fields.date', ['name' => 'Final Payment Date', 'field' => 'final_payment', 'value' => isset($tour) ? $tour->final_payment : null, 'width' => 4])
@include('partials.fields.date',
            ['name' => 'End Date', 'field' => 'date_to', 'value' => $date_to ?? null,
             'onChange' => 'removeAutoset($(\'#date_from-input\'), $(\'#date_to-input\'));', 'classes' => 'autoset', 'width' => 4,])
<hr class="splitter"/>
@include('partials.fields.text', ['name' => 'Base Price Per Person', 'field' => 'base_price_per_person', 'value' => $base_price_per_person ?? null, 'width' => 6,])
@include('partials.fields.text', ['name' => 'Margin', 'field' => 'margin', 'value' => $margin ?? null, 'width' => 6,])
@include('partials.fields.text', ['name' => 'Deposit', 'field' => 'deposit', 'value' => $deposit ?? null, 'width' => 3,])
@include('partials.fields.text', ['name' => 'Booking Fee', 'field' => 'booking_fee', 'value' => $tour?->booking_fee ?? null, 'width' => 3,])
@include('partials.fields.text', ['name' => 'Single Occupancy Surcharge', 'field' => 'single_occupancy_surcharge', 'value' => $single_occupancy_surcharge ?? null, 'width' => 6,])
<hr class="splitter"/>
@include('partials.fields.checkbox', ['name' => 'Stock Control Active', 'field' => 'stock_control_active', 'value' => $stock_control_active ?? null,])
@include('partials.fields.text', ['name' => 'Stock', 'field' => 'stock', 'value' => $stock ?? null,])
<hr class="splitter"/>
@include('partials.fields.checkbox', ['name' => 'Is Active', 'field' => 'is_active', 'value' => $is_active ?? null,])
@include('partials.fields.text', ['name' => 'Booking Form Url', 'field' => 'booking_form_url', 'value' => $booking_form_url ?? null,])
<hr class="splitter"/>
@include('partials.fields.ckeditor', ['name' => 'Invoice Footer', 'field' => 'invoice_footer', 'value' => isset($tour) ? $tour->invoice_footer : null, ])
<hr class="splitter"/>
@include('partials.fields.ckeditor', ['name' => 'Terms and Conditions', 'field' => 'terms', 'value' => isset($tour) ? $tour->terms : null, ])
<hr class="splitter"/>
@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
