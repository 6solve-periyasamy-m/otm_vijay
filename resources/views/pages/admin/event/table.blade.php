@extends('layout.master')

@section('title', 'All Events')

@php
/**
 * @var \App\Models\Tour\Event[] $events
 */
@endphp

@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#event').DataTable({fixedHeader: true});
    });
</script>
@endsection

@section('content')
@can('create', \App\Models\Tour\Event::class)
<div class="card">
    <div class="card-body">
        <a class="btn btn-primary float-end" href="{{ route('events.create') }}">
            <i class="icon-plus"></i>
            <span>Create New</span>
        </a>
    </div>
</div>
@endcan
<div class="card">
    <div class="card-body">
        <table id="event" style="width: 100%;" class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($events as $event)
                <tr>
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->description }}</td>
                    <td>{{ f_date($event->starts_at) }}</td>
                    <td>{{ f_date($event->ends_at) }}</td>
                    <td>{{ $event->notes }}</td>
                    <td>
                        @can('update', \App\Models\Tour\Event::class)
                            <a href="{{route('events.edit', ['event' => $event,])}}" class="btn btn-sm btn-outline-success mb-1">
                                <i class="icon-note"></i>
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <i class="icon-note"></i>
                            </span>
                        @endcan
                        @can('delete', \App\Models\Tour\Event::class)
                            <a href="javascript:$('#events-{{ $event->id }}-delete').submit()" class="btn btn-sm btn-outline-danger mb-1">
                                <i class="icon-trash"></i>
                            </a>
                            <form id="events-{{ $event->id }}-delete"
                                  action="{{ route('events.delete', ['event' => $event,]) }}" method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <i class="icon-trash"></i>
                            </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
