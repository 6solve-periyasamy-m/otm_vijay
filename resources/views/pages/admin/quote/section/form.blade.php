@php
/**
 * @var \App\Models\Quote\Quote $quote
 * @var \App\Models\Quote\QuoteSection|null $section
 */
$section = $section ?? null;
$title = isset($section) ? "Update Quote Section" : "Create Quote Section";
$route = isset($section) ? route('quotes.section.update', ['quote' => $quote, 'section' => $section,]) : route('quotes.section.create', ['quote' => $quote]);
@endphp

@extends('layout.form', ['action' => $route, 'multipart' => true])

@section('title', $title)

@section('form-body')
    <x-admin.input name="title" value="{{$section?->title}}">Title</x-admin.input>
    @include('partials.fields.ckeditor', [
        'field' => 'body',
        'name' => 'Section Body',
        'value' => $section?->body
    ])
    <x-admin.input name="image" width="4" type="file">Image</x-admin.input>
    <x-admin.input name="order" width="4" value="{{$section?->order ?? 0}}">Order</x-admin.input>
    <x-admin.input.checkbox name="hidden" width="4" value="{{$section?->hidden ?? false}}">Hide on document?</x-admin.input.checkbox>
    <input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection
