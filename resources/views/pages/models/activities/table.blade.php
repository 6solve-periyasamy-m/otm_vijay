@extends('layout.master')

@section('title', 'All Activities')

@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#activity').DataTable({fixedHeader: true});
    });
</script>
@endsection

@section('content')
@can('create', \App\Models\Activity::class)
<div class="card">
    <div class="card-body">
        <a class="btn btn-primary float-end" href="{{ route('activities.create') }}">
            <i class="icon-plus"></i>
            <span>Create New</span>
        </a>
    </div>
</div>
@endcan
<div class="card">
    <div class="card-body">
        <table id="activity" style="width: 100%;" class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Activity Type</th>
                <th scope="col">Location</th>
                <th scope="col">Description</th>
                <th scope="col">Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($activities as $activity)
                @include('partials.models.activities.row', [
                'activity' => $activity,
                'name' => $activity->name,
                'description' => $activity->description,
                'notes' => $activity->notes,
                ])
            @endforeach
        </table>
    </div>
</div>
@endsection
