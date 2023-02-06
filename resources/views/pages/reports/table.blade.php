@extends('layout.master')

@section('title', 'View Reports')

@push('header-stack')
    <script type="text/javascript">
        $(document).ready(function () { $('#reports').DataTable({fixedHeader: true,}); });
        @if(flag('atol.enabled', true))
        function showOrderedOnReport() {
            let year = $('#year-input').val(); let quarter = $('#quarter-input').val();
            if (isNaN(year) || isNaN(quarter) || year === "" || quarter === "") {
                alert('Both the year and quarter must be numbers'); return;
            }
            let route = "{{ route('reports.atol.ordered', ['year' => 'year', 'quarter'=>'quarter',]) }}"
            window.location = route.replace('year', year).replace('quarter', quarter);
        }
        function showDepartedInReport() {
            let year = $('#year-input').val(); let quarter = $('#quarter-input').val();
            if (isNaN(year) || isNaN(quarter) || year === "" || quarter === "") {
                alert('Both the year and quarter must be numbers'); return;
            }
            let route = "{{ route('reports.atol.departed-in', ['year' => 'year', 'quarter'=>'quarter',]) }}"
            window.location = route.replace('year', year).replace('quarter', quarter);
        }
        function showDepartedAfterReport() {
            let year = $('#year-input').val(); let quarter = $('#quarter-input').val();
            if (isNaN(year) || isNaN(quarter) || year === "" || quarter === "") {
                alert('Both the year and quarter must be numbers'); return;
            }
            let route = "{{ route('reports.atol.departs-after', ['year' => 'year', 'quarter'=>'quarter',]) }}"
            window.location = route.replace('year', year).replace('quarter', quarter);
        }
        function showOrderedOnCertificates() {
            let year = $('#year-input').val(); let quarter = $('#quarter-input').val();
            if (isNaN(year) || isNaN(quarter) || year === "" || quarter === "") {
                alert('Both the year and quarter must be numbers'); return;
            }
            let route = "{{ route('reports.atol.certificate.ordered', ['year' => 'year', 'quarter'=>'quarter',]) }}"
            window.location = route.replace('year', year).replace('quarter', quarter);
        }
        function showDepartedInCertificates() {
            let year = $('#year-input').val(); let quarter = $('#quarter-input').val();
            if (isNaN(year) || isNaN(quarter) || year === "" || quarter === "") {
                alert('Both the year and quarter must be numbers'); return;
            }
            let route = "{{ route('reports.atol.certificate.departed-in', ['year' => 'year', 'quarter'=>'quarter',]) }}"
            window.location = route.replace('year', year).replace('quarter', quarter);
        }
        function showDepartedAfterCertificates() {
            let year = $('#year-input').val(); let quarter = $('#quarter-input').val();
            if (isNaN(year) || isNaN(quarter) || year === "" || quarter === "") {
                alert('Both the year and quarter must be numbers'); return;
            }
            let route = "{{ route('reports.atol.certificate.departs-after', ['year' => 'year', 'quarter'=>'quarter',]) }}"
            window.location = route.replace('year', year).replace('quarter', quarter);
        }
        @endif
    </script>
@endpush

@section('content')
    @can('create', \App\Models\System\Report::class)
        <div class="card">
            <div class="card-body">
                <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'accommodation']) }}">
                    <x-icon icon="plus" />
                    <span>Accommodation Report</span>
                </a>
                <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'activity']) }}">
                    <x-icon icon="plus" />
                    <span>Activity Report</span>
                </a>
                <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'flight']) }}">
                    <x-icon icon="plus" />
                    <span>Flight Report</span>
                </a>
                <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'transport']) }}">
                    <x-icon icon="plus" />
                    <span>Transport Report</span>
                </a>
                <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'customer']) }}">
                    <x-icon icon="plus" />
                    <span>Customer Report</span>
                </a>
                <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'order-installment']) }}">
                    <x-icon icon="plus" />
                    <span>Order Installment Report</span>
                </a>
                <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'payment']) }}">
                    <x-icon icon="plus" />
                    <span>Payment Report</span>
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="reports">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Type</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($system as $report)
                    <tr>
                        <th scope="row"><a href="{{ route($report['view']) }}">{{ $report['name'] }}</a></th>
                        <td>{{ $report['details'] }}</td>
                        <td>System</td>
                        <td>
                            <a href="{{ route($report['export'], ['extension' => 'csv',]) }}" class="btn btn-outline-primary btn-sm mb-1" title="Export as CSV"><x-icon icon="list" /></a>
                            <a href="{{ route($report['export'], ['extension' => 'xlsx',]) }}" class="btn btn-outline-info btn-sm mb-1" title="Export as XLSX"><x-icon icon="chart" /></a>
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <x-icon icon="note" />
                            </span>
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <x-icon icon="trash" />
                            </span>
                        </td>
                    </tr>
                @endforeach
                @foreach($reports as $report)
                    <tr>
                        <th scope="row"><a href="{{ route('reports.bespoke.show', ['report' => $report,]) }}">{{ $report->name }}</a></th>
                        <td>{{ $report->description }}</td>
                        <td>Custom</td>
                        <td>
                            <a href="{{ route('reports.bespoke.export', ['report' => $report, 'extension' => 'csv']) }}" class="btn btn-outline-primary btn-sm mb-1" title="Export as CSV"><x-icon icon="list" /></a>
                            <a href="{{ route('reports.bespoke.export', ['report' => $report, 'extension' => 'xlsx']) }}" class="btn btn-outline-info btn-sm mb-1" title="Export as XLSX"><x-icon icon="chart" /></a>
                            @can('update', \App\Models\System\Report::class)
                                <a href="{{route('reports.bespoke.edit', ['report' => $report,])}}" class="btn btn-outline-success btn-sm mb-1">
                                    <x-icon icon="note" />
                                </a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    <x-icon icon="note" />
                                </span>
                            @endcan
                            @can('delete', \App\Models\System\Report::class)
                                <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                                   onclick="event.preventDefault();document.getElementById('report-{{ $report->id }}-delete').submit();">
                                    <x-icon icon="trash" />
                                </a>
                                <form id="report-{{ $report->id }}-delete"
                                      action="{{ route('reports.bespoke.delete', ['report' => $report,]) }}" method="POST"
                                      style="display: none;">{{ csrf_field() }}</form>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    <x-icon icon="trash" />
                                </span>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if(flag('atol.enabled', true))
    <div class="heading pt-md-4 pb-md-3 pt-3">
        <h2 class="fw-bold">ATOL Reporting</h2>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                @include('partials.fields.text', ['name'=>'Year','field'=>'year','width'=>6])
                @include('partials.fields.text', ['name'=>'Quarter','field'=>'quarter','width'=>6])
                <hr class="splitter">
                <div class="row">
                    <button class="btn m-2 btn-primary col-3" onclick="showOrderedOnReport()"><x-icon icon="plus" />Show Ordered On Report</button>
                    <button class="btn m-2 btn-primary col-3" onclick="showDepartedInReport()"><x-icon icon="plus" />Show Departed In Report</button>
                    <button class="btn m-2 btn-primary col-3" onclick="showDepartedAfterReport()"><x-icon icon="plus" />Show Departed After Report</button>
                </div>
                <hr class="splitter">
                <div class="row">
                    <button class="btn m-2 btn-primary col-3" onclick="showOrderedOnCertificates()"><x-icon icon="plus" />Export Ordered On Certificates</button>
                    <button class="btn m-2 btn-primary col-3" onclick="showDepartedInCertificates()"><x-icon icon="plus" />Export Departed In Certificates</button>
                    <button class="btn m-2 btn-primary col-3" onclick="showDepartedAfterCertificates()"><x-icon icon="plus" />Export Departed After Certificates</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
