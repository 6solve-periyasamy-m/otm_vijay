@extends('layout.master')

@section('title', 'View Accommodation')

@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#accommodation').DataTable({fixedHeader: true});
    });
</script>
@endsection

@section('content')
    @can('create', \App\Models\AccommodationInventory::class)
    <div class="card">
        <div class="card-body">
            <a class="btn btn-primary float-end" href="{{ route('accommodations.create') }}">
                <i class="icon-plus"></i>
                <span>Create New</span>
            </a>
        </div>
    </div>
    @endcan
    <div class="card">
        <div class="card-body">
            <table id="accommodation" style="width: 100%;" class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Audit Date</th>
                    <th scope="col">Address</th>
                    <th scope="col">Currency</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                @foreach($accommodations as $accommodation)
                    @include('partials.models.accommodations.row', [
                    'accommodation' => $accommodation,
                    'region_id' => $accommodation->region_id,
                    'name' => $accommodation->name,
                    'description' => $accommodation->description,
                    'audit_date' => $accommodation->audit_date,
                    'address' => $accommodation->address,
                    'currency' => $accommodation->currency,
                    ])
                @endforeach
            </table>
        </div>
    </div>
@endsection
