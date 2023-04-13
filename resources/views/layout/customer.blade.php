<!DOCTYPE html>
<html dir="ltr" lang="en">
@php
    $branding = $branding ?? \App\Models\System\Brand::getSystemBrand();
@endphp
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="octopus, customer portal, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, materialpro admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, materialpro admin lite design, materialpro admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description"
        content="Octopus Travel Matrix Customer End Portal">
    <meta name="robots" content="noindex,nofollow">    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - OTM Customer End Portal</title>    
    <!-- Custom CSS -->
    <link href="{{ asset('/css/app.css?v=').time()}}" rel="stylesheet">
    <link href="{{ asset('/css/customer.css?v=').time() }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="{{ asset('js/app.js') . '?' . date('U')  }}"></script>
    <style>
        .select2-container--default .select2-selection--single {
            border: none !important;
        }
    </style>

    @stack('header-stack') <!-- TODO: Rename to script once all sections are converted -->
    <script type="text/javascript">
        $(document).ready(function () {
            @stack('header-ready')
        });
        window.addEventListener('livewireAlert', event => {
            alert(event.detail.message);
        });
        function toggleExpander(accordion) {
            accordion = $(accordion);
            let body = $(accordion.attr('data-target'));
            if (body.hasClass('flex-wrap')) {
                body.removeClass('flex-wrap');
                accordion.attr('aria-expanded', 'false');
            } else {
                body.addClass('flex-wrap');
                accordion.attr('aria-expanded', 'true');
            }
        }
    </script>
    @livewireStyles
</head>
<body>
    <!-- Preloader -->
    @include('pages.customer.layout.preloader')

    <div id="app" data-layout="vertical" class="vh-100">
        <!-- Topbar header -->
        @include('pages.customer.layout.navbar', ['branding' => $branding,])

        @if ($errors->any())
            <div class="container topbar-padding">
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Main Body -->
        <div class="container h-80 {{ $errors->any() ? '' : 'topbar-padding' }}" @if(isset($overflow) && !$overflow) style="overflow: hidden;" @endif>
            @yield('content')
        </div>
        @yield('footer')

    </div>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" defer>$(".preloader").fadeOut();</script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>

    @yield('footer-script')
    @stack('footer-stack')
    @livewireScripts
    @livewire('livewire-ui-modal')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
</body>
