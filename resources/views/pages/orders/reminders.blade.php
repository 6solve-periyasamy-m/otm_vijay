@extends('layout.master')

@section('title', 'Due Reminders')

@php
    /**
     * @var \App\Models\Order\Order[] $orders
     */
@endphp

@push('header-stack')
    <style>
        .scroll-list {
            overflow: hidden;
            overflow-y: scroll;
            border: 1px solid black;
            border-radius: 10px;
            height: 500px;
            width: 100%;
            list-style: none;
            padding: 0;
        }
        .overdue {
            color: #dc3545;
        }
        .scroll-list > li {
            border-bottom: 1px solid black;
            padding: 5px 10px;
        }
        .scroll-list > li:hover {
            background: #007bff;
            cursor: pointer;
            color: white;
        }
        .scroll-list > li.overdue:hover {
            background: #dc3545 !important;
            color: white !important;
            cursor: pointer;
        }
    </style>
    <script type="text/javascript">
        function showNewRange() {
            let max = $('#max-input').val();
            let min = $('#min-input').val();
            window.location = '{{ route('orders.reminders') }}/' + max + '/' + min;
        }
    </script>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                @include('partials.fields.text', ['name' => 'Maximum Days', 'field' => 'max', 'value' => $max, 'width' => 5])
                @include('partials.fields.text', ['name' => 'Minimum Days', 'field' => 'min', 'value' => $min, 'width' => 5])
                <div class="form-group col-12 col-xl-2">
                    <label></label>
                    <a class="form-control mx-auto btn btn-block btn-primary" onclick="event.preventDefault();showNewRange()">Show Range</a>
                </div>
                <hr class="splitter">
            </div>
            <ul class="scroll-list">
                @foreach($orders as $order)
                    @php $daysDue = $order->days_until_next_payment; $next = $order->next_installment; @endphp
                    <li @if ($daysDue < 0) class="overdue" @endif onclick="window.location='{{ route('orders.view', ['order' => $order,]) }}';">
                        <div class="row">
                            <div class="col-2 text-center">{{ $order->booking_reference }}</div><div class="col-2 text-center">{{ $order->lead_booker_name }}</div><div class="col-2 text-center">{{ $order->leadBooker->customer->email_address }}</div>
                            @if($daysDue > 0)
                                <div class="col-4 text-center">{{ f_currency($next->amount) }} is due in {{ $daysDue }} days ({{ f_date($next->due_on) }})</div>
                            @elseif($daysDue === 0)
                                <div class="col-4 text-center">{{ f_currency($next->amount) }} is due today ({{ f_date($next->due_on) }})</div>
                            @else
                                <div class="col-4 text-center">{{ f_currency($next->amount) }} was due {{ $daysDue * -1 }} days ago ({{ f_date($next->due_on) }})</div>
                            @endif
                        </div>

                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
