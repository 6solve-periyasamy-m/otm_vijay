@php
/** @var \App\Models\Quote\Quote $quote */
$quote = $quote ?? null;
@endphp

@extends('layout.master')

@section('title', $quote === null ? 'Create Quote' : "Update Quote - {$quote->reference}")

@section('content')
    <livewire:admin.quote.form :quote="$quote" />
@endsection
