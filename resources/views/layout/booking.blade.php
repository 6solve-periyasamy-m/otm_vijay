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
    <!-- Styles -->
    <!-- <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('/css/app.css?v=').time()}}" rel="stylesheet">
    @yield('header-script')
</head>
<body>
    <div id="container" style="padding-left: 0.5%; padding-right: 0.5%; padding-top: 0.5%; min-width: calc(100vw - 298px); min-height: calc(100vh - 49px);">
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
        @yield('content')
    </div>
    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('js/app.js') . '?' . date('U')  }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/pdfmake.min.js" integrity="sha512-uO6vGk8coV9uDaoMwYUTVO2nQ3XS4MVePe6qVif3PkiYRZ2y+707M4HOdaYPF0jqxhgNenarJ/1RlfRTs37SeA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/vfs_fonts.js" integrity="sha512-vv3EN6dNaQeEWDcxrKPFYSFba/kgm//IUnvLPMPadaUf5+ylZyx4cKxuc4HdBf0PPAlM7560DV63ZcolRJFPqA==" crossorigin="anonymous"></script>
</body>
</html>
