@extends('layout.master')

@section('title', 'All Flights')

@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () { $('#flight').DataTable({fixedHeader: true}); });
</script>
@endsection

@section('content')
@can('create', \App\Models\Flight::class)
<div class="card">
    <div class="card-body">
        <a class="btn btn-primary float-end" href="{{ route('flights.create') }}">
            <i class="icon-plus"></i>
            <span>Create New</span>
        </a>
    </div>
</div>
@endcan
<div class="card">
    <div class="card-body">
        <table id="flight" style="width: 100%;" class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Airline</th>
                <th scope="col">Departure Airport</th>
                <th scope="col">Arrival Airport</th>
                <th scope="col">Is Domestic</th>
                <th scope="col">Available After</th>
                <th scope="col">Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($flights as $flight)
                @include('partials.models.flights.row', [
                'flight' => $flight,
                'is_domestic' => $flight->is_domestic,
                'notes' => $flight->notes,
                'available_after' => $flight->available_after,
                ])
            @endforeach
        </table>
    </div>
</div>
@endsection
