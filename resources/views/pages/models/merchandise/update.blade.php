@extends('layout.form', ['action' => route('merchandise.update', ['tour' => $tour, 'merchandise' => $merchandise,]),])

@section('title', 'Update Merchandise')

@section('form-body')
    @include('partials.models.merchandise.form', [
      'name' => $merchandise->name,
      'stock' => $merchandise->stock,
      'purchase_price' => $merchandise->purchase_price,
      'sales_price' => $merchandise->sales_price,
      'notes' => $merchandise->notes,
    ])
@endsection
