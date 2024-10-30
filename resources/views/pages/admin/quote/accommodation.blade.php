@php /** @var \App\Models\Quote\Quote|null $quote */ $quote = $quote ?? null; @endphp

@extends('layout.master')

@section('title', 'Accommodation Selector')

@section('content')
    <livewire:admin.quote.accommodation-selector :start="$quote?->date_from" :end="$quote?->date_to" :quote="$quote->id" />
@endsection
