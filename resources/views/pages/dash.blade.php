@extends('layout.master')

@section('title', 'Dashboard')

@push('header-stack')
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
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Expected Revenue</h4>
                    </div>
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
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Revenue over 7 days</h4>
                    </div>
                    {{ \App\Repository\ChartRepository::getRevenueChart(now()->subDays(7)) }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h4 class="fw-bold">Abandoned Bookings</h4>
                        </div>
                        @include('partials.reports.tables.abandoned-bookings', ['data' => \App\Repository\Reporting\ReportRepository::getAbandonedBookingsReport(7)])
                    </div>
                </div>
            </div>
    </div>
@endsection
