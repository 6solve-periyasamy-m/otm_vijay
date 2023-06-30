@php
/**
 * @param \App\Models\Voucher\VoucherCode $voucher;
 */
@endphp

@extends('layout.master')

@section('title', 'View Voucher')

@section('content')
    <livewire:admin.voucher.details :voucher="$voucher" />
    <div class="row">
        <div class="col-xl-6">
            <livewire:admin.voucher.result.card :voucher="$voucher" />
        </div>
        <div class="col-xl-6">
            <livewire:admin.voucher.tour.card :voucher="$voucher" />
        </div>
    </div>
@endsection
