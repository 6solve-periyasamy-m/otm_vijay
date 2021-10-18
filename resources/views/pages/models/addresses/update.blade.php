@extends('layout.main')

@section('title', 'Update Address')

@section('content')
    @include('partials.models.addresses.form', ['action' => route('addresses.update', ['address' => $address,]),
      'address_line_1' => $address->address_line_1,
      'address_line_2' => $address->address_line_2,
      'town' => $address->town,
      'region' => $address->region,
      'country' => $address->country,
      'postcode' => $address->postcode,
    ])
@endsection
