@extends('layout.master')

@php
    /**
     * @var \App\Models\Quote\Quote[] $quotes
     */
@endphp

@section('title', 'All Quotes')

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
        </div>
    </div>
@endsection
