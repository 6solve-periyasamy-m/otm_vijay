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
    <link href="https://cdn.datatables.net/v/bs5/moment-2.29.4/dt-2.3.2/fh-4.0.3/sl-3.0.1/datatables.min.css" rel="stylesheet" integrity="sha384-AmSgplFI3JRSEGpYrka7tR7wlMgVcFUWqqtipvYrVZkv4Ic29T5N8w9oMGWM10gt" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Styles -->
    @if(!isset($tailwind) || $tailwind === true)
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    @endif
    <link href="{{ asset('/css/mdb.css') }}" rel="stylesheet">
    <!-- App (including Lodash, jQuery, Bootstrap via NPM) -->
    <link href="{{ asset('/css/app.css?v=').time()}}" rel="stylesheet">
       <link href="{{ asset('/css/admin/custom.css') }}" rel="stylesheet">
    @livewireStyles
    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="{{ asset('js/ckeditor.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.11.2/build/css/intlTelInput.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css" rel="stylesheet">
    {{-- Addons (fold): --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.min.css" rel="stylesheet" />

    <style>
        .CodeMirror {
            border: 1px solid #ced4da;
            border-radius: 4px;
            resize: vertical;
        }
        .CodeMirror .CodeMirror-scroll {
            font-size: .9rem;
            font-family: Nunito, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .CodeMirror .CodeMirror-gutters {
            background: #f8fafc;
            border-right-color: #ced4da;
        }
        .upcom-whol{
            width: 100%;
            background: #000000;
            color: #fff;
        }
        .upcom-cent{
            padding: 18px;
            text-align: center;
            font-size: 18px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.11.2/build/js/intlTelInput.min.js"></script>
</head>
<body>
    @include('partials.navbar')
    <div class="container-fluid">
        <div class='row flex-xl-nowrap page-wrapper'>
            @include('partials.sidebar')
            <div id="container" class=' py-md-3 px-md-4 otm-content'>
                <div id="content" class="w-100">
                    <div id="banners">
                        @if (($showErrors ?? true) && $errors->any())
                            @foreach ($errors->all() as $error)
                                <x-admin.banner :content="$error" color="danger" />
                            @endforeach
                        @endif
                        @if(\Session::has('success'))
                            <x-admin.banner content="{!! \Session::get('success') !!}" color="success" />
                        @endif
                    </div>
                    @yield('upcoming')
                    <div class="heading pt-md-4 pb-md-3 pt-3">
                        <h2 class="fw-bold">@yield('title')</h2>
                        <img src="{{ asset(setting('company.logo')) }}" alt="{{ setting('company.name') }}" class="iconLogo hide" height="40px" width="100px" />
                    </div>
                    @yield('bookings')
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
<footer>
    <script src="{{ asset('js/admin/functions.js')  }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/moment-2.29.4/dt-2.3.2/fh-4.0.3/sl-3.0.1/datatables.min.js" integrity="sha384-mx6lJGUFQPlUlJs0IhcK4Iu7qgTmacW1fENC06Xzs7VYT6HQu7kM8yINPv3Gtqxl" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            {{-- Toasts get added here in JS --}}
        </div>
    </div>
    <button onclick="scrollToTop()" id="scrollToTop" class="btn btn-primary scroll-to-top">{{ Icon::up() }}</button>
    <script type="text/javascript">
        function showToast(title, body, color = 'primary', autohide = false, delay = 5000) {
            let now = Date.now();
            $('.toast-container').append(render(template('toast'), {id: now, title: title, body: body, color: color}));
            bootstrap.Toast.getOrCreateInstance(document.getElementById(now.toString()), {'animation': true, 'autohide': autohide, 'delay': delay}).show();
        }
        function showBanner(content, color = 'danger') {
            $('#banners').append(render(template('banner-notification'), {'content': content, 'color': color}));
        }
    </script>
    <script type="text/template" data-template="toast">
        @include('partials.toast')
    </script>
    <script type="text/template" data-template="banner-notification">
        {{ \App\View\Components\Admin\Banner::getTemplate()->render() }}
    </script>
    @yield('footer-script')
    @livewireScripts
    @livewire('livewire-ui-modal')
    <!-- Modals completely brick in 3.14.3. TODO: Re-evaluate -->
    <script defer src="https://unpkg.com/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/focus@3.14.3/dist/cdn.min.js"></script>
    @stack('footer-stack')
    <script type="text/javascript">
        function appFormatDateTime(date){
            const pad = (n) => String(n).padStart(2, '0');
            const formatDatetime = (date) => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
            return formatDatetime(date);
        }

        function appFormatDate(date){
            const pad = (n) => String(n).padStart(2, '0');
            const formatDate = (date) => `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
            return formatDate(date);
        }

        $(document).ready(function () {
            $('.datatable:not(.multiselect):not(.autowidth-off)').DataTable({fixedHeader: true,});
            $('.datatable.multi-select').DataTable({fixedHeader: true, select: { style: "multi+shift" },});
            $('.datatable.autowidth-off').DataTable({fixedHeader: true, autoWidth: false,});
            $(document).on('scroll', function (event) { onScrollEvent(); })
            onScrollEvent();
            @stack('footer-ready')
        });

        function onScrollEvent() {
            let button = document.getElementById('scrollToTop');
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                button.style.display = "block";
            } else {
                button.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function scrollToTop() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        }

        function openModal(modal, options = {}) {
            Livewire.emit('openModal', modal, options);
            setTimeout(() => {$('.sized-modal').show();}, 100)

        }
        function sysFormatDate(date) {
            return formatDate(date, '{{ setting('system.format.date') }}');
        }
        function sysFormatDateTime(date) {
            return formatDate(date, '{{ setting('system.format.date', 'd/m/Y') . ' ' . setting('system.format.time', 'H:i') }}');
        }
        function sysFormatCurrency(amount, currency = "{{ setting('system.currency', 'GBP') }}") {
            return (new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: currency,
            })).format(amount);
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
        });
        Livewire.on('openInNewTab', (data) => { window.open(data.url); });
        Livewire.on('refreshPage', (data) => { window.location.reload(); });
    </script>
</footer>
</html>
