@extends('layout.master')

@section('title', 'Small Model Manager')

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.datatable').DataTable({fixedHeader: true});
        });
        function toggleAccordion(accordion) {
            let body = $($(accordion).attr('data-target'));
            if (body.hasClass('show')) {
                body.removeClass('show');
                $(accordion).find("i").first().removeClass("icon-arrow-up")
                $(accordion).find("i").first().addClass("icon-arrow-down")
            } else {
                body.addClass('show');
                $(accordion).find("i").first().removeClass("icon-arrow-down")
                $(accordion).find("i").first().addClass("icon-arrow-up")
            }
        }
    </script>
@endsection

@section('content')
    <div class="card">
        <div class="card-body" data-target="#accommodation" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                <i class="icon-arrow-up"></i> Accommodation
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="accommodation">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Accommodation\RoomTypeRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Accommodation\BoardTypeRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#activity" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                <i class="icon-arrow-up"></i> Activity
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="activity">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Activity\ActivityTypeRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Activity\TicketTypeRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#flight" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                <i class="icon-arrow-up"></i> Flight
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="flight">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Flight\AirlineRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Flight\AirportRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\TravelClassRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#transport" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                <i class="icon-arrow-up"></i> Transport
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="transport">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Transport\OperatorRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Transport\TransportTypeRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#merchandise" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                <i class="icon-arrow-up"></i> Merchandise
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="merchandise">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Merchandise\MerchandiseTypeRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Merchandise\MerchandiseSizeRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Merchandise\VariantRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#tour" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                <i class="icon-arrow-up"></i> Tour
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="tour">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Tour\TourCategoryRepository::class])
        </div>
    </div>
    <div class="card">
        <div class="card-body" data-target="#customer" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                <i class="icon-arrow-up"></i> Customer
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="customer">
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Customer\HatSizeRepository::class])
        </div>
        <div class="col-xl-4">
            @include('partials.admin.small-model-table', ['repository' => \App\Repository\Model\Customer\TShirtSizeRepository::class])
        </div>
    </div>
@endsection
