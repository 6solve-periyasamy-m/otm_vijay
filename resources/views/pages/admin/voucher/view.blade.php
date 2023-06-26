@php
/**
 * @param \App\Models\Voucher\VoucherCode $voucher;
 */
@endphp

@extends('layout.master')

@section('title', 'View Voucher')

@section('content')
    <livewire:admin.voucher.details :voucher="$voucher" />
@endsection
