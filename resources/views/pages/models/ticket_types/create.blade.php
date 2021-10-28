@extends('layout.master')

@section('title', 'Create Ticket Type')

@section('content')
    @include('partials.models.ticket_types.form', ['action' => route('ticket-types.store'),])
@endsection
