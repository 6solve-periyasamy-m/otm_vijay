@extends('layout.form', ['action' => route('merchandise.update', ['tour' => $tour, 'merchandise' => $merchandise,]), 'multipart' => true,])

@section('title', 'Update Merchandise')

@section('form-body')
    @include('partials.models.merchandise.form', [
      'name' => $merchandise->name,
      'stock' => $merchandise->stock,
      'purchase_price' => $merchandise->purchase_price,
      'sales_price' => $merchandise->tour_sales_price,
      'notes' => $merchandise->notes,
    ])
@endsection
