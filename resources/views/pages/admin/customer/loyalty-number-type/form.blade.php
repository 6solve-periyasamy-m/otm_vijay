@php
/**
 * @var App\Models\Customer\LoyaltyNumberType|null $type
 */
$type = $type ?? null;
$title = ($type === null ? 'Create' : 'Update') . " Loyalty Number Type";
@endphp

@extends('layout.master')

@section('title', $title)

@section('content')
    <livewire:admin.customer.loyalty-number-type.form :type="$type"/>
@endsection
