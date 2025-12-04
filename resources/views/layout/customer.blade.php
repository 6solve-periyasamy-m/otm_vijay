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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Custom CSS -->
    {{-- <link href="{{ asset('/css/app.css?v=').time()}}" rel="stylesheet"> --}}
    <link href="{{ asset('/css/customer/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.11.2/build/css/intlTelInput.css">
    {{-- <link href="{{ asset('/css/customer.css?v=').time() }}" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="{{ asset('js/app.js') . '?' . date('U')  }}"></script>
    <script src="{{ asset('js/admin/functions.js') . '?' . date('U')  }}"></script>
    <script src="https://checkout.airwallex.com/assets/elements.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
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
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.11.2/build/js/intlTelInput.min.js"></script>
</head>
<body>
    <!-- Preloader -->
    @include('pages.customer.layout.preloader')

    <div id="app" data-layout="vertical" class="vh-100">
        <!-- Topbar header -->
        {{-- @include('pages.customer.layout.navbar', ['branding' => $branding,]) --}}

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
            <div class="body_content">
                @if(\App\Repository\Authentication\CustomerAuthenticationRepository::getCustomer() !== null)
                    <livewire:customer.leftsidebar />
                @endif
                @yield('content')
            </div>
        </div>
        @yield('footer')

    </div>
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            {{-- Toasts get added here in JS --}}
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnstyle-cancel" data-bs-dismiss="modal">Cancel</button>

                    <button type="button" class="btn btn-danger btnstyle-logout" id="confirmLogoutBtn">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/template" data-template="toast">
    @include('partials.toast')
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" defer>$(".preloader").fadeOut();</script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>

    @yield('footer-script')
    @stack('footer-stack')
    @livewireScripts
    @livewire('livewire-ui-modal')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            @stack('footer-ready')
        });
        function showToast(title, body, color = 'primary', autohide = false, delay = 5000) {
            let now = Date.now();
            $('.toast-container').append(render(template('toast'), {id: now, title: title, body: body, color: color}));
            bootstrap.Toast.getOrCreateInstance(document.getElementById(now.toString()), {'animation': true, 'autohide': autohide, 'delay': delay}).show();
        }
        Livewire.on('showToast', (data) => {
            const title = data.title;
            const body = data.body;
            const color = data.color ?? 'primary';
            const autoHide = data.autoHide ?? false;
            const delay = data.delay ?? 5000;
            showToast(title, body, color, autoHide, delay);
        })
    </script>
</body>
