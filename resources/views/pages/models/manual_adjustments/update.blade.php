@extends('layout.form', ['action' => route('manual-adjustments.update', ['order' => $order, 'manualAdjustment' => $manualAdjustment,]),])

@section('title', 'Update Manual Adjustment')

@section('form-body')
    @include('partials.models.manual_adjustments.form', [
      'order_id' => $manualAdjustment->order_id,
      'amount' => $manualAdjustment->amount,
      'reason' => $manualAdjustment->reason,
      'date' => $manualAdjustment->date,
    ])
@endsection
