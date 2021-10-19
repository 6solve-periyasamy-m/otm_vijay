@extends('layout.main')

@section('title', 'All Manual Adjustment')

@section('content')
    <a class="btn btn-primary" href="{{ route('manual-adjustments.create', ['order' => $order, ]) }}">Create New</a>
    <table id="manualAdjustment" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Order Id</th>
            <th scope="col">Amount</th>
            <th scope="col">Reason</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($manualAdjustments as $manualAdjustment)
            @include('partials.models.manual_adjustments.row', [
              'manualAdjustment' => $manualAdjustment,
              'order_id' => $manualAdjustment->order_id,
              'amount' => $manualAdjustment->amount,
              'reason' => $manualAdjustment->reason,
            ])
        @endforeach
    </table>
@endsection
