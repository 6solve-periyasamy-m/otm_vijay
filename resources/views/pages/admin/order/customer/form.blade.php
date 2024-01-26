@php
    /**
     * @var \App\Models\Order\Order $order
     * @var \App\Models\Order\OrderCustomer|null $orderCustomer
     */
    $orderCustomer = $orderCustomer ?? null;
    $title = __('order.customer.form.title.' . ($orderCustomer === null ? 'create' : 'update'));
    $route = $orderCustomer === null ?
        route('order-customers.store', ['order' => $order,]) :
        route('order-customers.update', ['order' => $order, 'orderCustomer' => $orderCustomer,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.selector.adder',
            ['name' => 'Customer', 'field' => 'customer_id', 'value' => $orderCustomer?->customer_id ?? 0, 'route' => 'customers',
             'fullRoute' => route('api.available-customers.select', ['order' => $order,]), 'createRoute' => route('customers.create'), 'width' => 10,])
    @include('partials.fields.checkbox', ['name' => 'Paying?', 'field' => 'paying', 'value' => $orderCustomer?->is_charged ?? true, 'width' => 1,])
    @include('partials.fields.checkbox', ['name' => 'Travelling?', 'field' => 'travelling', 'value' => $orderCustomer?->is_travelling ?? true, 'width' => 1,])
    @include('partials.fields.text', ['name' => 'Tour Cost', 'field' => 'tour_cost', 'value' => $orderCustomer?->tour_cost ?? $order?->leadBooker->tour_cost ?? null, 'width' => 3,])
    @include('partials.fields.text', ['name' => 'Single Occupancy Surcharge', 'field' => 'single_occupancy_surcharge', 'value' => $orderCustomer?->single_occupancy_surcharge ?? $order?->leadBooker->single_occupancy_surcharge ?? null, 'width' => 3,])
    @include('partials.fields.text', ['name' => 'Travel Insurer', 'field' => 'travel_insurer', 'value' => $orderCustomer?->travel_insurer ?? null, 'width' => 3,])
    @include('partials.fields.text', ['name' => 'Policy Number', 'field' => 'policy_number', 'value' => $orderCustomer?->policy_number ?? null, 'width' => 3,])
    <hr class="splitter" />
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'internal_notes', 'value' => $orderCustomer?->internal_notes ?? null, 'width' =>6,])
    @include('partials.fields.textarea', ['name' => 'External Notes', 'field' => 'external_notes', 'value' => $orderCustomer?->external_notes ?? null, 'width' =>6,])
    @include('partials.fields.textarea', ['name' => 'Accommodation Notes', 'field' => 'accommodation_notes', 'value' => $orderCustomer?->accommodation_notes ?? null, 'width' =>6,])
    @include('partials.fields.textarea', ['name' => 'Activity Notes', 'field' => 'activity_notes', 'value' => $orderCustomer?->activity_notes ?? null, 'width' =>6,])
    @include('partials.fields.textarea', ['name' => 'Flight Notes', 'field' => 'flight_notes', 'value' => $orderCustomer?->flight_notes ?? null, 'width' =>6,])
    @include('partials.fields.textarea', ['name' => 'Transport Notes', 'field' => 'transport_notes', 'value' => $orderCustomer?->transport_notes ?? null, 'width' =>6,])
    @include('partials.fields.submit')
@endsection
