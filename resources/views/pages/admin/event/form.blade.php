@php
    /**
     * @var \App\Models\Tour\Event|null $event
     */
    $event = $event ?? null;
    $title = __('event.form.title.' . ($event === null ? 'create' : 'update'));
    $route = $event === null ?
        route('events.store') :
        route('events.update', ['event' => $event,]);
@endphp

@extends('layout.form', ['action' => $route, 'multipart' => true,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $event?->name ?? null, 'width' => 8,])
    <x-livewire.input.select.tax-bracket name="tax_bracket_id" label="Tax Bracket" value="{{ $event?->tax_bracket_id }}" width="4" />
    @include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'width' => 8, 'value' => $event?->description ?? null,])
    @include('partials.fields.file', ['name' => 'Image', 'field' => 'image', 'width' => 4, 'value' => $event?->image_url])
    @include('partials.fields.date',
                ['name' => 'Start Date', 'field' => 'starts_at', 'value' => $event?->starts_at ?? null,
                 'onChange' => 'changeDate($(\'#starts_at-input\'), $(\'#ends_at-input\'))', 'width' => 6,])
    @include('partials.fields.date',
                ['name' => 'End Date', 'field' => 'ends_at', 'value' => $event?->ends_at ?? null,
                 'onChange' => 'removeAutoset($(\'#starts_at-input\'), $(\'#ends_at-input\'));', 'classes' => 'autoset', 'width' => 6,])
    <div class="form-group col-xl-6">
    @include('partials.fields.raw.dropdown', ['name' => 'Event Category', 'field' => 'event_category','selected' => $event?->event_category, 'values' => ["0" => "Normal Event", "1" => "Main Event"]])
    </div>
    @include('partials.fields.text', ['name' => 'Booking URL', 'field' => 'booking_url', 'value' => $event?->booking_url ?? null,])
    @include('partials.fields.textarea', ['name' => 'Notes', 'field' => 'notes', 'value' => $event?->notes ?? null])
    @include('partials.fields.submit')
@endsection