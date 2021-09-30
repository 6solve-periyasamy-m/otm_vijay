@extends('layout.main')

@section('title', 'Update Manual Adjustments')

@section('content')
  @include('partials.models.manual_adjustments.form', ['action' => route('manual_adjustments.update', ['manualAdjustment' => $manualAdjustment,]),
    'order_id' => $manualAdjustment->order_id,
    'amount' => $manualAdjustment->amount,
    'reason' => $manualAdjustment->reason,
  ])
@endsection
