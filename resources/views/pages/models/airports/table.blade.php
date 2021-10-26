@extends('layout.master')

@section('title', 'All Airports')

@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#airport').DataTable({fixedHeader: true});
    });
</script>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <a class="btn btn-primary float-end" href="{{ route('airports.create') }}">
            <i class="icon-plus"></i>
            <span>Create New</span>
        </a>
    </div>
</div>
<div class="card">
    <div class="card-body">                
        <table id="airport" style="width: 100%;" class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Iata Code</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($airports as $airport)
                @include('partials.models.airports.row', [
                'airport' => $airport,
                'name' => $airport->name,
                'iata_code' => $airport->iata_code,
                ])
            @endforeach
        </table>
    </div>
</div>
@endsection
