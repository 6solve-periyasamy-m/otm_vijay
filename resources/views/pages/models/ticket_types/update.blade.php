@extends('layout.form', ['action' => route('ticket-types.update', ['ticketType' => $ticketType,]),])

@section('title', 'Update Ticket Type')

@section('form-body')
    @include('partials.models.ticket_types.form', [
      'name' => $ticketType->name,
    ])
@endsection
