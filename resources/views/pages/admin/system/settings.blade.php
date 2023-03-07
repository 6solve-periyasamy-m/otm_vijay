@extends('layout.master')

@section('title', 'Edit System Settings')

@push('footer-stack')
    <script type="text/javascript">
        function toggleAccordion(accordion) {
            let body = $($(accordion).attr('data-target'));
            if (body.hasClass('show')) {
                body.removeClass('show');
                $(accordion).find("i").first().removeClass("fa-arrow-up")
                $(accordion).find("i").first().addClass("fa-arrow-down")
            } else {
                body.addClass('show');
                $(accordion).find("i").first().removeClass("fa-arrow-down")
                $(accordion).find("i").first().addClass("fa-arrow-up")
            }
        }
    </script>
@endpush

@section('content')
    <div class="card">
        <div class="card-body" data-target="#settings" onclick="toggleAccordion(this)">
            <h4 class="fw-bold">
                {{ Icon::minimize() }} System Settings
            </h4>
        </div>
    </div>
    <div class="row collapse show" id="settings">
        @include('partials.admin.system.settings.form')
    </div>
@endsection
