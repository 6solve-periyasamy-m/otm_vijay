@extends('layout.form', ['action' => route('transports.update', ['transport' => $transport,]),'multipart' => true,])

@section('title', 'Update Transport')

@section('form-body')
    @include('partials.models.transports.form', [
      'transport_type_id' => $transport->transport_type_id,
      'operator_id' => $transport->operator_id,
      'departure_address_id' => $transport->departure_address_id,
      'arrival_address_id' => $transport->arrival_address_id,
      'name' => $transport->name,
      'description' => $transport->description,
      'currency' => $transport->currency,
      'is_domestic' => $transport->is_domestic,
      'notes' => $transport->notes,
    ])
@endsection
