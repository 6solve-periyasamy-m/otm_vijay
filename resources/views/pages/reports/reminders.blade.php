@extends('layout.master')

@section('title', ($title . ' ' ?? '') . 'Report Result')

@section('content')
    @include('partials.orders.reminder.frequencies', ['route' => 'reports.reminders'])
    <x-admin.section.card>
        Negative days means that the payment is overdue
    </x-admin.section.card>
    <x-admin.section.card>
        <a class="btn btn-amber float-end" style="margin-left: 5px;" href="{{ $csvExport }}">
            {{ Icon::create() }}
            <span>Export as CSV</span>
        </a>
        <a class="btn btn-success float-end" style="margin-left: 5px;" href="{{ $xlsxExport }}">
            {{ Icon::create() }}
            <span>Export as Excel</span>
        </a>
    </x-admin.section.card>
    <x-admin.section.card>
        @include($tableView)
    </x-admin.section.card>
    @include('partials.orders.reminder.authorize')
@endsection
