@extends('layout.form', ['action' => route('ticket-types.store'),])

@section('title', 'Create Ticket Type')

@section('form-body')
    @include('partials.models.ticket_types.form')
@endsection
