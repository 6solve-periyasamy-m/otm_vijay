@extends('layout.master')

@section('title', 'Update Transport Type')

@section('content')
    @include('partials.models.transport_types.form', ['action' => route('transport-types.update', ['transportType' => $transportType,]),
      'name' => $transportType->name,
    ])
@endsection
