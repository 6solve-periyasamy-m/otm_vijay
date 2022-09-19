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
        <div class="col-xl-6">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Accommodation\RoomTypeRepository::class])
            <hr class="splitter">
        </div>
        <div class="col-xl-6">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Accommodation\BoardTypeRepository::class])
            <hr class="splitter">
        </div>
    </div>
@endsection
