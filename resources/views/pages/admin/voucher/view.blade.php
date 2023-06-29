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
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Executors</h4>
                    </div>
                    <livewire:admin.voucher.result.table :voucher="$voucher->id" />
                </div>
            </div>
        </div>
    </div>
@endsection
