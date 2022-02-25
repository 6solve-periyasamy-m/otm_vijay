@extends('layout.master')

@section('title', 'View Report')

@section('content')
    <div class="card">
        <div class="card-body">
            @can('update', \App\Models\Report::class)
                <a class="btn btn-warning float-end" href="{{ route('reports.bespoke.edit', ['report' => $report,]) }}">
                    <i class="icon-note"></i>
                    <span>Edit Report</span>
                </a>
            @endcan
            <a class="btn btn-primary float-end" href="{{ route('reports.bespoke.export', ['report' => $report, 'extension' => 'csv']) }}" style="margin-right: 5px">
                <i class="icon-list"></i>
                <span>Export to CSV</span>
            </a>
            <a class="btn btn-info float-end" href="{{ route('reports.bespoke.export', ['report' => $report, 'extension' => 'xlsx']) }}" style="margin-right: 5px">
                <i class="icon-chart"></i>
                <span>Export to XLSX</span>
            </a>
        </div>
    </div>
    @include('partials.reports.bespoke.output')
@endsection
