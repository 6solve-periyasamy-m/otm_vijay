@extends('layout.master')

@section('title', 'Update Accommodation')

@section('content')
    @include('partials.models.accommodations.form', ['action' => route('accommodations.update', ['accommodation' => $accommodation,]),
      'region_id' => $accommodation->region_id,
      'title' => $accommodation->title,
      'description' => $accommodation->description,
      'audit_date' => $accommodation->audit_date,
      'address' => $accommodation->address,
      'currency' => $accommodation->currency,
    ])
@endsection
