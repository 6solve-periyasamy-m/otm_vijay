@extends('layout.master')

@section('title', 'View Report')

@push('header-stack')
    <script type="text/javascript">
        function save() {
            $.post('{{ route('api.reports.bespoke.save') }}', {!! json_encode(array_merge($report->toArray(), ['__api_token' => Auth::user()->getCurrentToken()->token,])) !!})
            .done(function (xhr, textStatus, errorThrown) {
                window.location = xhr.message;
            });
        }
        function exportXlsx() {
            $.post('{{ route('api.reports.bespoke.export') }}', {!! json_encode(array_merge($report->toArray(), ['__api_token' => Auth::user()->getCurrentToken()->token, 'filetype' => 'xslx',])) !!})
            .done(function (xhr, textStatus, errorThrown) {
                window.open(xhr.message);
            });
        }
        function exportCsv() {
            $.post('{{ route('api.reports.bespoke.export') }}', {!! json_encode(array_merge($report->toArray(), ['__api_token' => Auth::user()->getCurrentToken()->token, 'filetype' => 'csv',])) !!})
            .done(function (xhr, textStatus, errorThrown) {
                window.open(xhr.message);
            });
        }
    </script>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            @can('create', \App\Models\Report::class)
            <a class="btn btn-success float-end" href="#" onclick="save()">
                <i class="icon-plus"></i>
                <span>Save Report</span>
            </a>
            @endcan
            @if(false)
            {{-- TODO: Fix temporary exporting --}}
            <a class="btn btn-primary float-end" href="#" onclick="exportCsv()" style="margin-right: 5px">
                <i class="icon-list"></i>
                <span>Export to CSV</span>
            </a>
            <a class="btn btn-info float-end" href="#" onclick="exportXlsx()" style="margin-right: 5px">
                <i class="icon-chart"></i>
                <span>Export to XLSX</span>
            </a>
            @endif
        </div>
    </div>
    @include('partials.reports.bespoke.output')
@endsection
