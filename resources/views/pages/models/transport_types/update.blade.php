@extends('layout.main')

@section('title', 'Update Transport Types')

@section('content')
  @include('partials.models.transport_types.form', ['action' => route('transport-types.update', ['transportType' => $transportType,]),
    'name' => $transportType->name,
  ])
@endsection
