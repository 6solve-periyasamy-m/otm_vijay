@extends('layout.master')

@section('title', 'View Report')

@section('content')
    <x-admin.section.card>
        @can('update', \App\Models\System\Report::class)
            <a class="btn btn-warning float-end" href="{{ route('reports.bespoke.edit', ['report' => $report,]) }}">
                {{ Icon::edit() }}
                <span>Edit Report</span>
            </a>
        @endcan
        <a class="btn btn-primary float-end" target="_blank" href="{{ route('reports.bespoke.export', ['report' => $report, 'extension' => 'csv']) }}" style="margin-right: 5px">
            {{ Icon::csv() }}
            <span>Export to CSV</span>
        </a>
        <a class="btn btn-info float-end" target="_blank"  href="{{ route('reports.bespoke.export', ['report' => $report, 'extension' => 'xlsx']) }}" style="margin-right: 5px">
            {{ Icon::excel() }}
            <span>Export to XLSX</span>
        </a>
    </x-admin.section.card>
    @include('partials.reports.bespoke.output')
@endsection
