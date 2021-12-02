<!DOCTYPE html>
<!-- THIS LAYOUT IS FOR THE NEW FRONTEND -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Octopus Travel Matrix</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.11.2/fh-3.1.9/r-2.2.9/sl-1.3.3/datatables.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> 
    <!-- Styles -->
    <link href="{{ asset('/css/mdb.css') }}" rel="stylesheet">
    <!-- App (including Lodash, jQuery, Bootstrap via NPM) -->
    <link href="{{ asset('/css/app.css?v=').time()}}" rel="stylesheet">
    <script src="{{ asset('js/app.js') . '?' . date('U')  }}"></script>
    <!-- TODO: Remove IonIcons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    @yield('header-script')
    @stack('header-stack') <!-- TODO: Rename to script once all sections are converted -->
    <script type="text/javascript">
        $(document).ready(function () {
            @stack('header-ready')
        });
    </script>
</head>
<body>
@include('partials.navbar')
<div class="container-fluid">
    <div class='row flex-xl-nowrap'>
        @include('partials.sidebar')
        <div id="container" class='col-12 col-md-9 col-xl-10 py-md-3 px-md-4 otm-content'>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $error }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <ion-icon name="close"></ion-icon>
                        </button>
                    </div>
                @endforeach
            @endif
            <div id="content" class="">
                <div class="heading pt-md-4 pb-md-3 pt-3">
                    <h2 class="fw-bold">@yield('title')</h2>
                </div>
                @yield('content')
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/manifest.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.11.2/fh-3.1.9/r-2.2.9/sl-1.3.3/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/pdfmake.min.js" integrity="sha512-uO6vGk8coV9uDaoMwYUTVO2nQ3XS4MVePe6qVif3PkiYRZ2y+707M4HOdaYPF0jqxhgNenarJ/1RlfRTs37SeA==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/vfs_fonts.js" integrity="sha512-vv3EN6dNaQeEWDcxrKPFYSFba/kgm//IUnvLPMPadaUf5+ylZyx4cKxuc4HdBf0PPAlM7560DV63ZcolRJFPqA==" crossorigin="anonymous"></script>
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
<script>
    $('.sidebar-toggler').click(function() {
        $('.otm-sidebar').toggleClass('show');
    });
</script>
@yield('footer-script')
<script type="text/javascript">
    $(document).ready(function () {
        @stack('footer-ready')
    });
    function changeDate(invar, outvar) {
        if (outvar.hasClass('autoset')) {
            outvar.val(invar.val());
        }
    }
    function removeAutoset(invar, outvar) {
        if (outvar.hasClass('autoset') && outvar.val() !== invar.val()) {
            outvar.removeClass('autoset')
        }
    }
</script>
@stack('footer-stack')
</body>
</html>
