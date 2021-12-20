@extends('layout.form', ['action' => route('tours.update', ['tour' => $tour,]),])

@section('title', 'Update Tour')

@section('form-body')
    @include('partials.models.tours.form', [
      'event_id' => $tour->event_id,
      'name' => $tour->name,
      'description' => $tour->description,
      'date_from' => $tour->date_from,
      'date_to' => $tour->date_to,
      'base_price_per_person' => $tour->base_price_per_person,
      'margin' => $tour->margin,
      'deposit' => $tour->deposit,
      'single_occupancy_surcharge' => $tour->single_occupancy_surcharge,
      'stock_control_active' => $tour->stock_control_active,
      'stock' => $tour->stock,
      'booking_form_url' => $tour->booking_form_url,
      'tour_category_id' => $tour->tour_category_id,
      'is_active' => $tour->is_active,
      'notes' => $tour->notes,
    ])
@endsection
