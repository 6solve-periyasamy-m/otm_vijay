@extends('layout.form', ['action' => route('accommodations.update', ['accommodation' => $accommodation,]), 'multipart' => true,])

@section('title', 'Update Accommodation')

@section('form-body')
    @include('partials.models.accommodations.form', [
      'region_id' => $accommodation->region_id,
      'name' => $accommodation->name,
      'description' => $accommodation->description,
      'audit_date' => $accommodation->audit_date,
      'address' => $accommodation->address,
      'currency' => $accommodation->currency,
    ])
@endsection
