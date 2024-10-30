@php /** @var \App\Models\Quote\Quote|null $quote */ $quote = $quote ?? null; $travellers = $quote->paying + $quote->travelling; @endphp

@extends('layout.master')

@section('title', "Accommodation Selector - {$quote->name} - Travellers: {$travellers}")

@section('content')
    <livewire:admin.quote.accommodation-selector :start="$quote?->date_from" :end="$quote?->date_to" :quote="$quote->id" :travellers="$travellers" />
@endsection
