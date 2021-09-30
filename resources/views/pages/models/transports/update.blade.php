@extends('layout.main')

@section('title', 'Update Transports')

@section('content')
  @include('partials.models.transports.form', ['action' => route('transports.update', ['transport' => $transport,]),
    'transport_type_id' => $transport->transport_type_id,
    'operator_id' => $transport->operator_id,
    'departure_location_id' => $transport->departure_location_id,
    'arrival_location_id' => $transport->arrival_location_id,
    'name' => $transport->name,
    'description' => $transport->description,
    'currency' => $transport->currency,
    'is_domestic' => $transport->is_domestic,
    'notes' => $transport->notes,
  ])
@endsection
