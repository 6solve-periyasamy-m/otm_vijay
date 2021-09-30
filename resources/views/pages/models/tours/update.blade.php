@extends('layout.main')

@section('title', 'Update Tours')

@section('content')
  @include('partials.models.tours.form', ['action' => route('tours.update', ['tour' => $tour,]),
    'event_id' => $tour->event_id,
    'title' => $tour->title,
    'description' => $tour->description,
    'date_from' => $tour->date_from,
    'date_to' => $tour->date_to,
    'base_price_per_person' => $tour->base_price_per_person,
    'margin' => $tour->margin,
    'single_occupancy_surcharge' => $tour->single_occupancy_surcharge,
    'stock_control_active' => $tour->stock_control_active,
    'stock' => $tour->stock,
    'booking_form_url' => $tour->booking_form_url,
    'tour_colour_id' => $tour->tour_colour_id,
    'is_active' => $tour->is_active,
    'notes' => $tour->notes,
  ])
@endsection
