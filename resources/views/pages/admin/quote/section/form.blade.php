@php
/**
 * @var \App\Models\Quote\Quote $quote
 * @var \App\Models\Quote\QuoteSection|null $section
 */
$section = $section ?? null;
$title = isset($section) ? "Update Quote Section" : "Create Quote Section";
@endphp

@extends('layout.master')

@section('title', $title)

@section('content')
    <livewire:admin.quote.section.form :quote="$quote" :section="$section" />
@endsection
