@extends('layout.main')

@section('title', 'View Manual Adjustment')

@section('content')
    Order Id: {{ $manualAdjustment->order_id }}<br/>
    Amount: {{ $manualAdjustment->amount }}<br/>
    Reason: {{ $manualAdjustment->reason }}<br/>
@endsection
