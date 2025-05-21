@extends('layout.master')
@section('title', 'eCommerce Dashboard')
@section('content')
    <div class="row">

        <div class="col-xl-12">
            <x-admin.section.card>
                <x-slot:title>
                    Booking Orders
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
