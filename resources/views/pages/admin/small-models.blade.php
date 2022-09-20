@extends('layout.master')

@section('title', 'Small Model Manager')

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.datatable').DataTable({fixedHeader: true});
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-4 v-splitter-r">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Accommodation\RoomTypeRepository::class])
            <hr class="splitter">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Accommodation\BoardTypeRepository::class])
            <hr class="splitter">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Transport\OperatorRepository::class])
            <hr class="splitter">
        </div>
        <div class="col-xl-4 v-splitter-r">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Activity\TicketTypeRepository::class])
            <hr class="splitter">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Activity\ActivityTypeRepository::class])
            <hr class="splitter">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Transport\TransportTypeRepository::class])
            <hr class="splitter">
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Flight\AirlineRepository::class])
            <hr class="splitter">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Flight\AirportRepository::class])
            <hr class="splitter">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\TravelClassRepository::class])
            <hr class="splitter">
        </div>
    </div>
@endsection
