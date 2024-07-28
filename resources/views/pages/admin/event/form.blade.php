@php
    /**
     * @var \App\Models\Tour\Event|null $event
     */
    $event = $event ?? null;
    $title = __('event.form.title.' . ($event === null ? 'create' : 'update'));
    $route = $event === null ?
        route('events.store') :
        route('events.update', ['event' => $event,]);
    $categories = \App\Models\Helper\Enum\EventType::toArray();
@endphp

@extends('layout.form', ['action' => $route, 'multipart' => true, 'showErrors' => false,])

@section('title', $title)

@section('form-body')
    <x-livewire.input name="name" label="Name" value="{{ $event?->name }}" width="6" />
    <x-livewire.input.select.tax-bracket name="tax_bracket_id" label="Tax Bracket" value="{{ $event?->tax_bracket_id }}" width="2"/>
    <x-livewire.input.select.brand name="brand_id" label="Brand" value="{{ $event?->brand_id }}" width="2" />
    <x-livewire.input.dropdown name="event_category" label="Event Category" :items="$categories" value="{{ $event?->event_category->value }}" width="2" />
    <x-livewire.input name="description" label="Description" value="{{ $event?->description }}" />
    <x-livewire.input type="date" name="starts_at" label="Starts At" width="5" value="{{ $event?->starts_at?->format('Y-m-d') }}" />
    <x-livewire.input type="date" name="ends_at" label="Ends At" width="5" value="{{ $event?->ends_at?->format('Y-m-d') }}" />
    <x-livewire.input type="file" name="image" label="Image" width="2" />
    <x-livewire.input name="booking_url" value="{{ $event?->booking_url }}" label="Booking URL" />
    <x-livewire.input.text-area name="notes" value="{{ $event?->notes }}" label="Notes" />
    @include('partials.fields.submit')
@endsection
