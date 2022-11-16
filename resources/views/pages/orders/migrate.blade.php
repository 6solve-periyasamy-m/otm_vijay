@extends('layout.form', ['action' => route('orders.migrate', ['order' => $order,]),])

@php
/**
 * @var \App\Models\Order\Order $order
 */
@endphp

@section('title', 'Migrate Order')

@section('form-body')
    @can('create', \App\Models\Tour\Tour::class)
        @include('partials.fields.selector.adder',
                    ['name' => 'Tour', 'field' => 'tour_id', 'value' => $tour_id ?? 0,
                     'route' => 'tours', 'createRoute' => route('tours.create'),])
    @else
        @include('partials.fields.selector.default',
                  ['name' => 'Tour', 'field' => 'tour_id', 'value' => $tour_id ?? 0,
                   'route' => 'tours',])
    @endcan
    @include('partials.fields.checkbox',
                ['name' => 'Reset Customer Price', 'field' => 'reset_price', 'width' => 6, 'value' => true,])
    @include('partials.fields.checkbox',
                ['name' => 'Reset Adjustments', 'field' => 'reset_adjustments', 'width' => 6,])
    @include('partials.fields.submit')
@endsection

@push('footer-stack')
    <script type="text/javascript" defer>
        $('#form-main').attr('onsubmit', 'return confirm("This change is permanent and may mix up the groupings, continue?");')
    </script>
@endpush
