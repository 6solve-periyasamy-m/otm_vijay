@extends('layout.master')

@if(kpt())
    @section('upcoming')
        <div class="upcom-whol">
            <div class="upcom-cent">
                @if($event)
                    <span class="upcom-nam-txt">{{ $event->name }} Starts in:</span>
                    <span class="upcom-time-txt" id="countdown"></span>
                @else
                    <span class="upcom-nam-txt">No upcoming events</span>
                @endif
            </div>
        </div>

        <script>
            @if($event)
            var eventDate = new Date("{{ $event->starts_at }}").getTime();

            var x = setInterval(function() {

                var now = new Date().getTime();

                var distance = eventDate - now;

                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("countdown").innerHTML = days + " days: " + hours + " hrs: " + minutes + " mins: " + seconds + " secs";

                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("countdown").innerHTML = "Event has started!";
                }
            }, 1000);
            @endif
        </script>
    @endsection

@php
$get_late = $monthsData;
$lastMonthData = array_pop($get_late);
$lastPercentageChange = $lastMonthData['percentageChange'] ?? 0;
$get_late = json_encode($monthsData);
@endphp

@section('bookings')
<style>
    .whol-sec-contain{
        display: flex;
        flex-direction: row;
        row-gap:50px ;
    }
    .whol-contain{
        display: flex;
        width: 300px;
        flex-direction: column;
        background: #A3CAEE1F;
        padding: 10px;
        height: 150px;
        row-gap: 18px;
    }
    .whol-contain .upcon{
        display: flex;
        flex-direction: row;
        align-content: flex-start;
        justify-content: flex-end;
        gap: 11px;
    }
    .whol-contain .upcon select{
        border-radius: 24px;
        font-size: 16px;
        text-align: center;
        border: 1px solid #333333;
        background: #A3CAEE1F;
    }
    .whol-contain .botcon{
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
    }
    .whol-contain .botcon .book-con{
        display: flex;
        align-content: center;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }
    .whol-contain .botcon .log-contain .book-log{
        font-size: 24px
    }
    .whol-contain .botcon .log-contain .book-log img{  
        height: 48px;
        width: 48px;
        background: #4285F433;
        border-radius: 50%;
        padding: 6px;
    }
    .whol-contain .botcon .val-contain{
        display: flex;
        flex-direction: column;
    }
    .whol-contain .botcon .val-contain .first{
        font-size: 24px;
    }
    .whol-contain .botcon .val-contain .second{
        font-size: 16px;
    }
    .whol-contain .botcon .book-perc{
        background: linear-gradient(to right, rgba(82, 216, 104, 0.1) 10%, rgba(82, 216, 104, 0.1) 100%);
       
        color: #13A22B;
        border-radius: 15px; 
    }
    
</style>
<div data-cval="{{$get_late}}" class="whol-sec-contain">
    <div class="whol-contain">
        <div class="upcon">
            <select name="all" id="">
                <option value="ALL">ALL</option>
                <option value="NOW">NOW</option>
                <option value="TEST">TEST</option>
            </select>
            <select name="month-sel" id="">
                @foreach($order_monthsarr as $order)
                <option data-count="{{ $order->count }}" value="{{ $order->month }}">{{ \Carbon\Carbon::create()->month($order->month)->format('F') }}</option>
                @endforeach
            </select>
        </div>
        <div class="botcon">
            <div class="book-con">
                <div class="log-contain">
                    <span class="book-log"><img src="/images/ticket.svg" alt="" srcset=""></span>
                </div>
                <div class="val-contain">
                    <span class="first">
                        {{$orderCount}}
                    </span>
                    <span class="second">
                        No. of bookings
                    </span>
                </div>
            </div>
            <div class="book-perc">
                
                <span>
                    @if($lastPercentageChange > 0)
                        +{{ $lastPercentageChange }}%
                    @elseif($lastPercentageChange < 0)
                        {{ $lastPercentageChange }}%
                    @else
                        0%
                    @endif
            </span>
            </div>
        </div>
    </div>
    <div class="whol-contain">
        <div class="upcon">
            <select name="all" id="">
                <option value="ALL">ALL</option>
                <option value="NOW">NOW</option>
                <option value="TEST">TEST</option>
            </select>
            <select name="month-sel" id="">
                @foreach($booking_monthsarr as $booking)
                <option data-count="{{ $booking->count }}" value="{{ $booking->month }}">{{ \Carbon\Carbon::create()->month($booking->month)->format('F') }}</option>
                @endforeach
            </select>
        </div>
        <div class="botcon">
            <div class="book-con">
                <div class="log-contain">
                    <span class="book-log"><img src="/images/user.svg" alt="" srcset=""></span>
                </div>
                <div class="val-contain">
                    <span class="first">
                        {{$bookCount}}
                    </span>
                    <span class="second">
                        No. of Inquries
                    </span>
                </div>
            </div>
            <div class="book-perc">
                <span>+372</span>
            </div>
        </div>
    </div>
</div>
@endsection
@endif

@section('title', 'Dashboard')

@push('footer-stack')
    <script type="text/javascript">
        let datatable;
        let rows = 0;
        function initTable() {
            datatable = $('.revenue-table').DataTable({fixedHeader: true, autoWidth: false, columnDefs: [{target: 0, visible: false, searchable: false,},]});
        }
        function refreshTable() {
            datatable.destroy();
            initTable();
            datatable.draw();
        }
        $(document).ready(function () {
            initTable();
            initDates();
        });
        function initDates() {
            rows++;
            $.get('{{ route('api.costing.revenue.set') }}', {
                '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                'data': [
                    @php $date = now()->subMonth()->firstOfMonth(); @endphp
                    @for($x = 0; $x < 4; $x++)
                    {
                        'from': '{{$date->addMonth()->firstOfMonth()->format('Y-m-d')}}'
                    },
                    @endfor
                ],
            }).done(function (data) {
                rows--;
                for (let datum in data.data) {
                    addRow(data.data[datum].from, data.data[datum].to, data.data[datum].expected, data.data[datum].paid, data.data[datum].count)
                }
            });
        }
        function getBetweenDates(start, end)
        {
            $.get('{{ route('api.costing.revenue.set') }}', {
                '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                'from': start,
                'to': end,
            }).done(function (data) {
                addRow(data.from, data.to, data.expected, data.paid, data.count)
            });
        }
        function addRow(from, to, total, paid, count) {
            datatable.row.add($(render(template('row'), {
                'unix': toUnix(from),
                'from': sysFormatDate(new Date(from)),
                'to': sysFormatDate(new Date(to)),
                'expected': formatCurrency(total),
                'received': formatCurrency(paid),
                'remaining': formatCurrency(total - paid),
                'percentage': (total === 0 ? 100 : Math.round(((paid/total)*100)*100)/100) + "%",
            })));
            if (rows <= 0) {
                refreshTable();
                $('.loader-replace').hide();
                $('.revenue-container').show();
            }
        }
        function formatCurrency(number) {
            let formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: '{{ setting('system.currency', 'gbp') }}'
            })
            return formatter.format(number);
        }
        function toUnix(dateString) {
            let date = Date.parse(dateString);
            return date.toString();
        }
    </script>
@endpush

@push('footer-stack')
<script type="text/template" data-template='row'><tr><td data-sort="${unix}">${from} to ${to}</td><td>${expected}</td><td>${received}</td><td>${remaining}</td><td>${percentage}</td></tr></script>
@endpush

@section('content')
    <div class="row">
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>
                    Expected Revenue
                </x-slot:title>
                <x-loading-spinner></x-loading-spinner>
                <div class="revenue-container" style="display: none;">
                    <table class="revenue-table table table-striped">
                        <thead>
                        <tr>
                            <td style="width: 30%">Dates</td>
                            <td>Expected Total Revenue</td>
                            <td>Received Revenue</td>
                            <td>Remaining Revenue</td>
                            <td>Percentage Paid</td>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </x-admin.section.card>
        </div>

        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>
                    Revenue over 7 days
                </x-slot:title>
                {{ \App\Repository\ChartRepository::getRevenueChart(now()->subDays(7)) }}
            </x-admin.section.card>
        </div>

        <div class="col-xl-12">
            <x-admin.section.card>
                <x-slot:title>
                    Abandoned Bookings
                </x-slot:title>
                @include('partials.reports.tables.abandoned-bookings', ['data' => \App\Repository\Reporting\ReportRepository::getAbandonedBookingsReport(7, true)])
            </x-admin.section.card>
        </div>
    </div>
@endsection
