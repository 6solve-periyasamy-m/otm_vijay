@extends('layout.master')
@section('title', 'eCommerce Dashboard')
@section('content')
@php $xlsxExport = route('reports.booking-orders.export', ['extension' => 'xlsx']); @endphp
    <div class="row">

        <div class="col-xl-12">
            <x-admin.section.card>
                <x-slot:title>
                    <div class="flex justify-between items-center">
                        <div class="text-left">
                            Booking Orders
                        </div>
                        <div class="text-right">
                            <a class="btn btn-success float-end" style="margin-left: 5px;" href="{{ $xlsxExport }}">
                                {{ Icon::create() }}
                                <span>Export as Excel</span>
                            </a>
                        </div>
                    </div>
                </x-slot:title>
                @include('partials.reports.tables.booking-orders', ['data' => \App\Repository\Reporting\ReportRepository::getOnlineOrderReport(7, true)])
            </x-admin.section.card>
        </div>
        <div class="col-xl-12"><br></br></br></div>

        <div class="col-xl-12">
            <x-admin.section.card>
                <x-slot:title>
                    Abandoned Bookings
                </x-slot:title>
                @include('partials.reports.tables.abandoned-bookings', ['data' => \App\Repository\Reporting\ReportRepository::getAbandonedBookingsReport(7, true)])
            </x-admin.section.card>
        </div>
    </div>
@endsection
