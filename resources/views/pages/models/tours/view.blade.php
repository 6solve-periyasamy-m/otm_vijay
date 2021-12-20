@extends('layout.main')

@section('title', 'View Tour')

@section('content')
    Event Id: {{ $tour->event_id }}<br/>
    Name: {{ $tour->name }}<br/>
    Description: {{ $tour->description }}<br/>
    Date From: {{ $tour->date_from }}<br/>
    Date To: {{ $tour->date_to }}<br/>
    Base Price Per Person: {{ $tour->base_price_per_person }}<br/>
    Margin: {{ $tour->margin }}<br/>
    Single Occupancy Surcharge: {{ $tour->single_occupancy_surcharge }}<br/>
    Stock Control Active: {{ $tour->stock_control_active }}<br/>
    Stock: {{ $tour->stock }}<br/>
    Booking Form Url: {{ $tour->booking_form_url }}<br/>
    Tour Colour Id: {{ $tour->tour_category_id }}<br/>
    Is Active: {{ $tour->is_active }}<br/>
    Notes: {{ $tour->notes }}<br/>
@endsection
