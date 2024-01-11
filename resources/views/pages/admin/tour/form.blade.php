@php
    /**
     * @var \App\Models\Tour\Tour|null $tour
     */
    $tour = $tour ?? null;
    $title = __('tour.form.title.' . ($tour === null ? 'create' : 'update'));
    $route = $tour === null ?
        route('tours.store') :
        route('tours.update', ['tour' => $tour,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $tour?->name, 'width' => 8])
    @include('partials.fields.selector.default',
            ['name' => 'Branding', 'field' => 'brand_id', 'value' => $tour?->brand_id ?? null,
             'route' => 'brands', 'width' => 4,])
    @include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $tour?->description,])
    @can('create', \App\Models\Tour\Event::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Event', 'field' => 'event_id', 'value' => $tour?->event_id ?? 0,
                     'route' => 'events', 'createRoute' => route('events.create'), 'width' => 4,])
    @else
        @include('partials.fields.selector.default',
                    ['name' => 'Event', 'field' => 'event_id', 'value' => $tour?->event_id ?? 0,
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
                    ['name' => 'Tour Category', 'field' => 'tour_category_id', 'value' => $tour?->tour_category_id,
                     'route' => 'tour-categories', 'createRoute' => route('tour-categories.create'), 'width' => 4,])
    @else
        @include('partials.fields.selector.default',
                ['name' => 'Tour Category', 'field' => 'tour_category_id', 'value' => $tour?->tour_category_id,
                 'route' => 'tour-categories', 'width' => 4,])
    @endcan
    <hr class="splitter"/>
    @include('partials.fields.date',
                ['name' => 'Start Date', 'field' => 'date_from', 'value' => $tour?->date_from->format('Y-m-d'),
                 'onChange' => 'changeDate($(\'#date_from-input\'), $(\'#date_to-input\'))', 'width' => 4, ])
    @include('partials.fields.date', ['name' => 'Final Payment Date', 'field' => 'final_payment', 'value' => $tour?->final_payment, 'width' => 4])
    @include('partials.fields.date',
                ['name' => 'End Date', 'field' => 'date_to', 'value' => $tour?->date_to->format('Y-m-d'),
                 'onChange' => 'removeAutoset($(\'#date_from-input\'), $(\'#date_to-input\'));', 'classes' => 'autoset', 'width' => 4,])
    <hr class="splitter"/>
    @include('partials.fields.text', ['name' => 'Base Price Per Person', 'field' => 'base_price_per_person', 'value' => $tour?->base_price_per_person, 'width' => 6,])
    @include('partials.fields.text', ['name' => 'Margin', 'field' => 'margin', 'value' => $tour?->margin, 'width' => 6,])
    @include('partials.fields.text', ['name' => 'Deposit', 'field' => 'deposit', 'value' => $tour?->deposit, 'width' => 3,])
    @include('partials.fields.text', ['name' => 'Booking Fee', 'field' => 'booking_fee', 'value' => $tour?->booking_fee ?? null, 'width' => 3,])
    @include('partials.fields.text', ['name' => 'Single Occupancy Surcharge', 'field' => 'single_occupancy_surcharge', 'value' => $tour?->single_occupancy_surcharge, 'width' => 6,])
    <hr class="splitter"/>
    <h6 class="fw-bold">Warning: Updating this does not update existing components</h6>
    @include('partials.fields.checkbox', ['name' => 'Tour Stock Control', 'field' => 'stock_control_active', 'value' => $tour?->stock_control_active, 'width' => 2,])
    @include('partials.fields.checkbox', ['name' => 'Accommodation Stock Control', 'field' => 'accommodation_stock_control', 'value' => $tour?->accommodation_stock_control, 'width' => 2,])
    @include('partials.fields.checkbox', ['name' => 'Activity Stock Control', 'field' => 'activity_stock_control', 'value' => $tour?->activity_stock_control, 'width' => 2,])
    @include('partials.fields.checkbox', ['name' => 'Flight Stock Control', 'field' => 'flight_stock_control', 'value' => $tour?->flight_stock_control, 'width' => 2,])
    @include('partials.fields.checkbox', ['name' => 'Transport Stock Control', 'field' => 'transport_stock_control', 'value' => $tour?->transport_stock_control, 'width' => 2,])
    @include('partials.fields.checkbox', ['name' => 'Merchandise Stock Control', 'field' => 'merchandise_stock_control', 'value' => $tour?->merchandise_stock_control, 'width' => 2,])
    <hr class="splitter"/>
    @include('partials.fields.text', ['name' => 'Stock', 'field' => 'stock', 'value' => $tour?->stock,])
    <hr class="splitter"/>
    @include('partials.fields.checkbox', ['name' => 'Is Active', 'field' => 'is_active', 'value' => $tour?->is_active,])
    @include('partials.fields.text', ['name' => 'Booking Form Url', 'field' => 'booking_form_url', 'value' => $tour?->booking_form_url,])
    <hr class="splitter"/>
    @include('partials.fields.ckeditor', ['name' => 'Invoice Footer', 'field' => 'invoice_footer', 'value' => $tour?->invoice_footer,])
    <hr class="splitter"/>
    @include('partials.fields.ckeditor', ['name' => 'Terms and Conditions', 'field' => 'terms', 'value' => $tour?->terms,])
    <hr class="splitter"/>
    @include('partials.fields.prefab.notes')
    @include('partials.fields.submit')
@endsection
