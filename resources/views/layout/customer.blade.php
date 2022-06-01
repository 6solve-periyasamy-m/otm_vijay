<!DOCTYPE html>
<html dir="ltr" lang="en">

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
    </script>
</head>
<body>
    <!-- Preloader -->
    @include('pages.customer.layout.preloader')

    <div id="app" data-layout="vertical" class="vh-100">
        <!-- Topbar header -->
        @include('pages.customer.layout.navbar')

        @if ($errors->any())
            <div class="container">
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Main Body -->
        <div class="container h-80" @if(isset($overflow) && !$overflow) style="overflow: hidden;" @endif>
            <div class="page-breadcrumb">
                <div class="row align-items-center">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="page-title mb-0 p-0"></h3>
                    </div>        
                </div>
            </div>
            @yield('content')
        </div>

        <div class="customer-footer" style="position: fixed; bottom: 0;">
            <hr />
            <div class="socials">
                @if(empty(\App\Repository\SettingsRepository::get('social.facebook')))
                  <div class="facebook">
                      <a href="{{ \App\Repository\SettingsRepository::get('social.facebook') }}" class="icon-social-facebook"></a>
                  </div>
                @endif
                @if(empty(\App\Repository\SettingsRepository::get('social.twitter')))
                  <div class="twitter">
                    <a href="{{ \App\Repository\SettingsRepository::get('social.twitter') }}" class="icon-social-twitter"></a>
                  </div>
                @endif
                @if(empty(\App\Repository\SettingsRepository::get('social.instagram')))
                    <div class="instagram">
                        <a href="{{ \App\Repository\SettingsRepository::get('social.instagram') }}" class="icon-social-instagram"></a>
                    </div>
                @endif
              </div>
            <div class="d-flex justify-content-between align-items-center footer-wrapper" style="font-size: 13px; font-weight: 600;">
                <span class="p-3 pe-5 d-flex flex-column">
                    <img class="stamp-logo sidebar-logo" src="{{ asset(\App\Repository\SettingsRepository::getOrDefault('atol.stamp', '')) }}" />
                </span>
                <span class="p-3 d-flex flex-column footer-client-details">
                    <span>{{ \App\Repository\SettingsRepository::getOrDefault('company.name', '') }}</span>
                    <span><i class="icon-envelope"></i> {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.email', '') }}</span>
                    <span><i class="icon-call-end"></i> {{ \App\Repository\SettingsRepository::getOrDefault('company.contact.phone', '') }}</span>
                </span>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/customer/sidebarmenu.js') . '?' . date('U')  }}"></script>
    <script src="{{ asset('js/customer/customer.js') . '?' . date('U')  }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('footer-script')
    @stack('footer-stack')
</body>
