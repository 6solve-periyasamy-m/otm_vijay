@extends('layout.main')

@section('title', 'All Ticket Types')

@section('content')
    <a class="btn btn-primary" href="{{ route('ticket-types.create') }}">Create New</a>
    <table id="ticketType" style="width: 100%;" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        @foreach($ticketTypes as $ticketType)
            @include('partials.models.ticket_types.row', [
              'ticketType' => $ticketType,
              'name' => $ticketType->name,
            ])
        @endforeach
    </table>
@endsection
