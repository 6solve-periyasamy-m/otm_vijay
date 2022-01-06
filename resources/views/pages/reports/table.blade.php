@extends('layout.master')

@section('title', 'View Reports')

@push('header-stack')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#reports').DataTable({fixedHeader: true,});
        });
    </script>
@endpush

@section('content')
    @can('create', \App\Models\Report::class)
        <div class="card">
            <div class="card-body">
                <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'accommodation']) }}">
                    <i class="icon-plus"></i>
                    <span>Accommodation Report</span>
                </a>
                <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'activity']) }}">
                    <i class="icon-plus"></i>
                    <span>Activity Report</span>
                </a>
                <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'flight']) }}">
                    <i class="icon-plus"></i>
                    <span>Flight Report</span>
                </a>
                <a class="btn btn-primary float-end" style="margin-right: 5px" href="{{ route('reports.bespoke.create', ['parent' => 'transport']) }}">
                    <i class="icon-plus"></i>
                    <span>Transport Report</span>
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
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <th scope="row"><a href="{{ route('reports.bespoke.show', ['report' => $report,]) }}">{{ $report->name }}</a></th>
                        <td>{{ $report->description }}</td>
                        <td>
                            @can('update', \App\Models\Report::class)
                                <a href="{{route('reports.bespoke.edit', ['report' => $report,])}}" class="btn btn-outline-success btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </a>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-note"></i>
                                </span>
                            @endcan
                            <a href="{{ route('reports.bespoke.export', ['report' => $report, 'extension' => 'csv']) }}" class="btn btn-outline-primary btn-sm mb-1" title="Export as CSV"><i class="icon-list"></i></a>
                            <a href="{{ route('reports.bespoke.export', ['report' => $report, 'extension' => 'xlsx']) }}" class="btn btn-outline-info btn-sm mb-1" title="Export as XLSX"><i class="icon-chart"></i></a>
                            @can('delete', \App\Models\Report::class)
                                <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                                   onclick="event.preventDefault();document.getElementById('report-{{ $report->id }}-delete').submit();">
                                    <i class="icon-trash"></i>
                                </a>
                                <form id="report-{{ $report->id }}-delete"
                                      action="{{ route('reports.bespoke.delete', ['report' => $report,]) }}" method="POST"
                                      style="display: none;">{{ csrf_field() }}</form>
                            @else
                                <span class="btn btn-outline-dark btn-sm mb-1">
                                    <i class="icon-trash"></i>
                                </span>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
