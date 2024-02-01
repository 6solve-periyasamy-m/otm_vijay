@php
    /**
     * @var \App\Models\Order\Order $order
     * @var \App\Models\Order\Adjustment\ManualAdjustment|null $manualAdjustment
     */
    $manualAdjustment = $manualAdjustment ?? null;
    $title = __('order.adjustment.form.title.' . ($manualAdjustment === null ? 'create' : 'update'));
    $route = $manualAdjustment === null ?
        route('manual-adjustments.store', ['order' => $order,]) :
        route('manual-adjustments.update', ['order' => $order, 'manualAdjustment' => $manualAdjustment,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Amount', 'field' => 'amount', 'value' => $manualAdjustment?->amount ?? null,])
    @include('partials.fields.text', ['name' => 'Reason', 'field' => 'reason', 'value' => $manualAdjustment?->reason ?? null,])
    @include('partials.fields.date', ['name' => 'Date', 'field' => 'date', 'value' => $manualAdjustment?->date ?? null,])
    @include('partials.fields.submit')
@endsection