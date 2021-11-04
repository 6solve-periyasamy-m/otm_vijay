@extends('layout.master')

@section('title', 'Create Manual Adjustment')

@section('content')
    @include('partials.models.manual_adjustments.form', ['action' => route('manual-adjustments.store', ['order' => $order, ]),])
@endsection
