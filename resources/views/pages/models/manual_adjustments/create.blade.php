@extends('layout.form', ['action' => route('manual-adjustments.store', ['order' => $order, ]),])

@section('title', 'Create Manual Adjustment')

@section('form-body')
    @include('partials.models.manual_adjustments.form')
@endsection
