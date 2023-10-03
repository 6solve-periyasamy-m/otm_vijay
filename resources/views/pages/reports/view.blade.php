@extends('layout.master')

@section('title', ($title . ' ' ?? '') . 'Report Result')

@section('content')
    <div class="card">
        <div class="card-body">
            <a class="btn btn-amber float-end" style="margin-left: 5px;" href="{{ $csvExport }}">
                {{ Icon::create() }}
                <span>Export as CSV</span>
            </a>
            <a class="btn btn-success float-end" style="margin-left: 5px;" href="{{ $xlsxExport }}">
                {{ Icon::create() }}
                <span>Export as Excel</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @include($tableView)
        </div>
    </div>
@endsection
