@php
    /**
     * @var \App\Models\Activity\TicketType|null $ticketType
     */
    $ticketType = $ticketType ?? null;
    $title = __('activity.inventory.ticket-type.form.title.' . ($ticketType === null ? 'create' : 'update'));
    $route = $ticketType === null ?
        route('ticket-types.store') :
        route('ticket-types.update', ['ticketType' => $ticketType,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $ticketType?->name,])
    @include('partials.fields.submit')
@endsection