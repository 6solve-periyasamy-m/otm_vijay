@extends('layout.master')

@section('title', 'Update Ticket Type')

@section('content')
    @include('partials.models.ticket_types.form', ['action' => route('ticket-types.update', ['ticketType' => $ticketType,]),
      'name' => $ticketType->name,
    ])
@endsection
