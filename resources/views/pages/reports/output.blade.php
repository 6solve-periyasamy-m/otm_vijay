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
    </script>
@endpush

@section('content')
    @can('create', \App\Models\Report::class)
        <div class="card">
            <div class="card-body">
                <a class="btn btn-primary float-end" href="#" onclick="save()">
                    <i class="icon-plus"></i>
                    <span>Create New</span>
                </a>
            </div>
        </div>
    @endcan
    @include('partials.reports.bespoke.output')
@endsection
