@extends('layout.master')

@section('title', ($title . ' ' ?? '') . 'Report Result')

@section('content')
    @include('partials.orders.reminder.frequencies', ['route' => 'reports.reminders'])
    <div class="card">
        <div class="card-body">
            Negative days means that the payment is overdue
        </div>
    </div>
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
    @include('partials.orders.reminder.authorize')
@endsection
