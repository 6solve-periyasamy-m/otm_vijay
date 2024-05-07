<!DOCTYPE html>
<!-- MASTER layout -->
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
        @if(!isset($tailwind) || $tailwind === true)
        <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
        @endif
        <link href="{{ asset('/css/mdb.css') }}" rel="stylesheet">
        <!-- App (including Lodash, jQuery, Bootstrap via NPM) -->
        <link href="{{ asset('/css/app.css?v=').time()}}" rel="stylesheet">
        @livewireStyles
        <script src="{{ asset('/js/app.js') }}"></script>
    </head>
    <body>
        @include('partials.navbar')
        <div class="container-fluid">
            <div class='row flex-xl-nowrap page-wrapper'>
                @include('partials.sidebar')
                <div id="container" class=' py-md-3 px-md-4 otm-content'>
                    <div id="content" class="w-100">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $error }}
                                    <button onclick="$(this).parent().remove()" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <ion-icon name="close"></ion-icon>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                        @if(\Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {!! \Session::get('success') !!}
                                <button onclick="$(this).parent().remove()" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <ion-icon name="close"></ion-icon>
                                </button>
                            </div>
                        @endif
                        <div class="heading pt-md-4 pb-md-3 pt-3">
                            <h2 class="fw-bold">@yield('title')</h2> 
                            <img src="{{ asset(setting('company.logo')) }}" alt="{{ setting('company.name') }}" class="iconLogo hide" height="40px" width="100px" />
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </body>
    <footer>
        <script src="{{ asset('js/admin/functions.js')  }}"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/dt-1.11.2/fh-3.1.9/r-2.2.9/sl-1.3.3/datatables.min.js"></script>
        <script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <div aria-live="polite" aria-atomic="true" class="position-relative">
            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                {{-- Toasts get added here in JS --}}
            </div>
        </div>
        <script type="text/javascript">
            function showToast(title, body, color = 'primary', autohide = false, delay = 5000) {
                let now = Date.now();
                $('.toast-container').append(render(template('toast'), {id: now, title: title, body: body, color: color}));
                bootstrap.Toast.getOrCreateInstance(document.getElementById(now.toString()), {'animation': true, 'autohide': autohide, 'delay': delay}).show();
            }
        </script>
        <script type="text/template" data-template="toast">
            @include('partials.toast')
        </script>
        @yield('footer-script')
        @livewireScripts
@livewire('livewire-ui-modal')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
@stack('footer-stack')
<script type="text/javascript">
    $(document).ready(function () {
        $('.datatable:not(.multiselect):not(.autowidth-off)').DataTable({fixedHeader: true,});
        $('.datatable.multi-select').DataTable({fixedHeader: true, select: { style: "multi+shift" },});
        $('.datatable.autowidth-off').DataTable({fixedHeader: true, autoWidth: false,});@stack('footer-ready')
    });
    function openModal(modal, options = {}) {
        Livewire.emit('openModal', modal, options);
    }
    function sysFormatDate(date) {
        return formatDate(date, '{{ setting('system.format.date') }}');
    }
    function sysFormatDateTime(date) {
        return formatDate(date, '{{ setting('system.format.date', 'd/m/Y') . ' ' . setting('system.format.time', 'H:i') }}');
    }
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
    function showOverlay(overlay) {
        let jOverlay = $(overlay);
        jOverlay.removeClass('hidden');
        $('body').addClass('overflow-hidden');
    }
    function hideOverlay(overlay) {
        let jOverlay = $(overlay);
        jOverlay.addClass('hidden');
        $('body').removeClass('overflow-hidden');
    }
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
    Livewire.on('showToast', (data) => {
        const title = data.title;
        const body = data.body;
        const color = data.color ?? 'primary';
        const autoHide = data.autoHide ?? false;
        const delay = data.delay ?? 5000;
        showToast(title, body, color, autoHide, delay);
    })
</script>
    </footer>
</html>
