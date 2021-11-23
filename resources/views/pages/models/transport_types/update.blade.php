@extends('layout.form', ['action' => route('transport-types.update', ['transportType' => $transportType,]),])

@section('title', 'Update Transport Type')

@section('form-body')
    @include('partials.models.transport_types.form', [
      'name' => $transportType->name,
    ])
@endsection
