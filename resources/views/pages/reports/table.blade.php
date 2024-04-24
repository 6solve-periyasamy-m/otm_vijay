@extends('layout.master')

@section('title', 'View Reports')

@push('footer-stack')
    <script type="text/javascript">
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
        <x-admin.section.card>
            <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'accommodation']) }}">
                {{ Icon::create() }}
                <span>Accommodation Report</span>
            </a>
            <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'activity']) }}">
                {{ Icon::create() }}
                <span>Activity Report</span>
            </a>
            <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'flight']) }}">
                {{ Icon::create() }}
                <span>Flight Report</span>
            </a>
            <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'transport']) }}">
                {{ Icon::create() }}
                <span>Transport Report</span>
            </a>
            <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'customer']) }}">
                {{ Icon::create() }}
                <span>Customer Report</span>
            </a>
            <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'order-installment']) }}">
                {{ Icon::create() }}
                <span>Order Installment Report</span>
            </a>
            <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'payment']) }}">
                {{ Icon::create() }}
                <span>Payment Report</span>
            </a>
            <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.advanced.create', ['type' => 'order',]) }}">
                {{ Icon::create() }}
                <span>Order Report</span>
            </a>
        </x-admin.section.card>
    @endcan
    <x-admin.section.card>
        <table class="datatable table table-striped" id="reports">
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
                    <th scope="row"><a href="{{ route($report['view'], ($report['params'] ?? [])) }}">{{ $report['name'] }}</a></th>
                    <td>{{ $report['details'] }}</td>
                    <td>System</td>
                    <td>
                        <a href="{{ route($report['export'], ['extension' => 'csv', ...($report['params'] ?? [])]) }}"
                           class="btn btn-outline-primary btn-sm mb-1" title="Export as CSV">{{ Icon::csv() }}</a>
                        <a href="{{ route($report['export'], ['extension' => 'xlsx', ...($report['params'] ?? [])]) }}"
                           class="btn btn-outline-info btn-sm mb-1" title="Export as XLSX">{{ Icon::excel() }}</a>
                        <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                {{ Icon::edit() }}
                            </span>
                        <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                {{ Icon::delete() }}
                            </span>
                    </td>
                </tr>
            @endforeach
            @foreach($reports as $report)
                <tr>
                    <th scope="row"><a
                                href="{{ route('reports.bespoke.show', ['report' => $report,]) }}">{{ $report->name }}</a>
                    </th>
                    <td>{{ $report->description }}</td>
                    <td>Custom</td>
                    <td>
                        <a href="{{ route('reports.bespoke.export', ['report' => $report, 'extension' => 'csv']) }}"
                           class="btn btn-outline-primary btn-sm mb-1" title="Export as CSV">{{ Icon::csv() }}</a>
                        <a href="{{ route('reports.bespoke.export', ['report' => $report, 'extension' => 'xlsx']) }}"
                           class="btn btn-outline-info btn-sm mb-1" title="Export as XLSX">{{ Icon::excel() }}</a>
                        @can('update', \App\Models\System\Report::class)
                            <a href="{{route('reports.bespoke.edit', ['report' => $report,])}}"
                               class="btn btn-outline-success btn-sm mb-1">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                        @endcan
                        @can('delete', \App\Models\System\Report::class)
                            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                               onclick="event.preventDefault();document.getElementById('report-{{ $report->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="report-{{ $report->id }}-delete"
                                  action="{{ route('reports.bespoke.delete', ['report' => $report,]) }}" method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::delete() }}
                                </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
            @foreach(\App\Models\System\BespokeReport::all() as $report)
                <tr>
                    <th scope="row">
                        <a href="{{ route('reports.advanced.view', ['report' => $report,]) }}">{{ $report->name }}</a>
                    </th>
                    <td>{{ $report->description }}</td>
                    <td>Advanced</td>
                    <td>
                        <span class="btn btn-outline-dark btn-sm mb-1" title="Export as CSV">{{ Icon::csv() }}</span>
                        <span class="btn btn-outline-dark btn-sm mb-1" title="Export as XLSX">{{ Icon::excel() }}</span>
                        @can('update', \App\Models\System\Report::class)
                            <a href="{{route('reports.advanced.edit', ['report' => $report,])}}"
                               class="btn btn-outline-success btn-sm mb-1">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </span>
                        @endcan
                        @can('delete', \App\Models\System\Report::class)
                            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                               onclick="event.preventDefault();document.getElementById('advanced-{{ $report->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="advanced-{{ $report->id }}-delete"
                                  action="{{ route('reports.advanced.delete', ['report' => $report,]) }}" method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                    {{ Icon::delete() }}
                                </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-admin.section.card>
    @if(flag('atol.enabled', true))
        <div class="heading pt-md-4 pb-md-3 pt-3">
            <h2 class="fw-bold">ATOL Reporting</h2>
        </div>
        <x-admin.section.card>
            <div class="row">
                @include('partials.fields.text', ['name'=>'Year','field'=>'year','width'=>6])
                @include('partials.fields.text', ['name'=>'Quarter','field'=>'quarter','width'=>6])
                <hr class="splitter">
                <div class="row">
                    <button class="btn m-2 btn-primary col-3" onclick="showOrderedOnReport()">{{ Icon::create() }}
                        Show Ordered On Report
                    </button>
                    <button class="btn m-2 btn-primary col-3" onclick="showDepartedInReport()">{{ Icon::create() }}
                        Show Departed In Report
                    </button>
                    <button class="btn m-2 btn-primary col-3"
                            onclick="showDepartedAfterReport()">{{ Icon::create() }}Show Departed After Report
                    </button>
                </div>
                <hr class="splitter">
                <div class="row">
                    <button class="btn m-2 btn-primary col-3"
                            onclick="showOrderedOnCertificates()">{{ Icon::create() }}Export Ordered On Certificates
                    </button>
                    <button class="btn m-2 btn-primary col-3"
                            onclick="showDepartedInCertificates()">{{ Icon::create() }}Export Departed In
                        Certificates
                    </button>
                    <button class="btn m-2 btn-primary col-3"
                            onclick="showDepartedAfterCertificates()">{{ Icon::create() }}Export Departed After
                        Certificates
                    </button>
                </div>
            </div>
            </x-admin.section.card>
    @endif
@endsection
