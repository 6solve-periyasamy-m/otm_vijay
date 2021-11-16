<!DOCTYPE html>
<!-- THIS LAYOUT IS NOT TO BE CHANGED EXCEPT BY Celeste -->
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
    <!-- Styles -->
    <link href="{{ asset('/css/mdb.css') }}" rel="stylesheet">
    <!-- app -->
    <link href="{{ asset('/css/app.css?v=').time()}}" rel="stylesheet">
    <script src="{{ asset('js/app.js') . '?' . date('U')  }}"></script>
    <!-- jQuery & Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.11.2/fh-3.1.9/r-2.2.9/sl-1.3.3/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.11.2/fh-3.1.9/r-2.2.9/sl-1.3.3/datatables.min.js"></script>
    <!-- IonIcons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('header-script')
</head>
<body>
@include('partials.navbar')
<div class="d-flex">
    @include('partials.sidebar')
    <p></p>
    <div class="container" style="padding-left: 0.5%; padding-right: 0.5%; padding-top: 0.5%; min-width: calc(100vw - 298px); min-height: calc(100vh - 49px);">
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
        <div class="bg-light text-dark" style="padding: 1% 100px; border: 5px solid black; border-radius: 25px;">
            @yield('content')
        </div>
    </div>
</div>
@yield('footer-script')
<script src="{{ asset('js/manifest.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/pdfmake.min.js" integrity="sha512-uO6vGk8coV9uDaoMwYUTVO2nQ3XS4MVePe6qVif3PkiYRZ2y+707M4HOdaYPF0jqxhgNenarJ/1RlfRTs37SeA==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/vfs_fonts.js" integrity="sha512-vv3EN6dNaQeEWDcxrKPFYSFba/kgm//IUnvLPMPadaUf5+ylZyx4cKxuc4HdBf0PPAlM7560DV63ZcolRJFPqA==" crossorigin="anonymous"></script>
</body>
</html>
