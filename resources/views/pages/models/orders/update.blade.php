@extends('layout.form', ['action' => route('orders.update', ['order' => $order,]),])

@section('title', 'Update Order')

@section('form-body')
    @include('partials.models.orders.form', [
      'update' => true,
      'quote_id' => $order->quote_id,
      'tour_id' => $order->tour_id,
      'lead_booker_id' => $order->lead_booker_id,
      'token' => $order->token,
      'booking_reference' => $order->booking_reference,
      'ordered_on' => $order->ordered_on,
      'internal_notes' => $order->internal_notes,
      'external_notes' => $order->external_notes,
    ])
@endsection
