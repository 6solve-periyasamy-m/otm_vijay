@extends('layout.master')

@section('title', 'Due Reminders')

@push('footer-stack')
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
@endpush

@section('content')
    @include('partials.orders.reminder.frequencies', ['route' => 'orders.reminders'])
    <x-admin.section.card>
        <ul class="scroll-list">
            @foreach($orders as $row)
                <li @if ($row->days < 0) class="overdue"
                    @endif onclick="window.location='{{ route('orders.view', ['order' => $row->order,]) }}';">
                    <div class="row">
                        <div class="col-2 text-center">{{ $row->order->booking_reference }}</div>
                        <div class="col-2 text-center">{{ $row->order->lead_booker_name }}</div>
                        <div class="col-3 text-center">{{ $row->order->leadBooker->customer->email_address }}</div>
                        <div class="col-1 text-center">{{ $row->next?->id === 0 ? 'Remaining' : 'Installment' }}</div>
                        @if($row->days  > 0)
                            <div class="col-4 text-center">{{ f_currency($row->next?->amount) }} is due
                                in {{ $row->days }} days ({{ f_date($row->next?->due_on) }})
                                @elseif($row->days === 0)
                                    <div class="col-4 text-center">{{ f_currency($row->next?->amount) }} is due today
                                        ({{ f_date($row->next?->due_on) }})
                                        @else
                                            <div class="col-4 text-center">{{ f_currency($row->next?->amount) }} was
                                                due {{ $row->days * -1 }} days ago ({{ f_date($row->next?->due_on) }})
                                                @endif
                                                @if($row->reminded)
                                                    &nbsp;(Reminded)
                                                @endif
                                            </div>
                                    </div>
                </li>
            @endforeach
        </ul>
    </x-admin.section.card>
    @include('partials.orders.reminder.authorize')
@endsection
