@extends('layout.main')

@section('title', 'Update Manual Adjustment')

@section('content')
    @include('partials.models.manual_adjustments.form', ['action' => route('manual-adjustments.update', ['order' => $order, 'manualAdjustment' => $manualAdjustment,]),
      'order_id' => $manualAdjustment->order_id,
      'amount' => $manualAdjustment->amount,
      'reason' => $manualAdjustment->reason,
    ])
@endsection
