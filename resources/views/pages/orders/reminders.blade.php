@extends('layout.master')

@section('title', 'Due Reminders')

@push('header-stack')
    <style>
        .scroll-list {
            overflow: hidden;
            overflow-y: scroll;
            border: 1px solid black;
            border-radius: 10px;
            height: 300px;
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
                <div class="form-group col-12 col-xl-2 text-white">
                    <a class="form-control mx-auto btn btn-block btn-primary" href="{{ route('orders.reminders', ['max' => 30, 'min' => 14]) }}">30 Days</a>
                </div>
                <div class="form-group col-12 col-xl-2 text-white">
                    <a class="form-control mx-auto btn btn-block btn-primary" href="{{ route('orders.reminders', ['max' => 14, 'min' => 7]) }}">14 Days</a>
                </div>
                <div class="form-group col-12 col-xl-2 text-white">
                    <a class="form-control mx-auto btn btn-block btn-primary" href="{{ route('orders.reminders', ['max' => 7, 'min' => 0]) }}">7 Days</a>
                </div>
                <div class="form-group col-12 col-xl-2 text-white">
                    <a class="form-control mx-auto btn btn-block btn-primary" href="{{ route('orders.reminders', ['max' => 0, 'min' => -1]) }}">Today</a>
                </div>
                <div class="form-group col-12 col-xl-2 text-white">
                    <a class="form-control mx-auto btn btn-block btn-primary" href="{{ route('orders.reminders', ['max' => -1, 'min' => -7]) }}">1 Day Overdue</a>
                </div>
                <div class="form-group col-12 col-xl-2 text-white">
                    <a class="form-control mx-auto btn btn-block btn-primary" href="{{ route('orders.reminders', ['max' => -7, 'min' => -14]) }}">7 Days Overdue</a>
                </div>
                <div class="form-group col-12 col-xl-2 text-white">
                    <a class="form-control mx-auto btn btn-block btn-primary" href="{{ route('orders.reminders', ['max' => -14, 'min' => -1000]) }}">14 Days Overdue</a>
                </div>
                <hr class="splitter">
            </div>
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
                @foreach($orders as $row)
                    <li @if ($row->days < 0) class="overdue" @endif onclick="window.location='{{ route('orders.view', ['order' => $row->order,]) }}';">
                        <div class="row">
                            <div class="col-2 text-center">{{ $row->order->booking_reference }}</div><div class="col-2 text-center">{{ $row->order->lead_booker_name }}</div><div class="col-2 text-center">{{ $row->order->leadBooker->customer->email_address }}</div>
                            @if($row->days  > 0)
                                <div class="col-4 text-center">{{ f_currency($row->next?->amount) }} is due in {{ $row->days }} days ({{ f_date($row->next?->due_on) }})</div>
                            @elseif($row->days === 0)
                                <div class="col-4 text-center">{{ f_currency($row->next?->amount) }} is due today ({{ f_date($row->next?->due_on) }})</div>
                            @else
                                <div class="col-4 text-center">{{ f_currency($row->next?->amount) }} was due {{ $row->days * -1 }} days ago ({{ f_date($row->next?->due_on) }})</div>
                            @endif
                        </div>

                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
