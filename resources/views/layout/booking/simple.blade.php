@php use App\Models\System\Brand; @endphp
@php /** @var Brand $brand */ @endphp
        <!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Simple Booking Form')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <link href="{{ asset('external/summernote/emoji/css/emoji.css') }}" rel="stylesheet">
    <script src="{{ asset('external/summernote/emoji/js/config.js') }}"></script>
    <script src="{{ asset('external/summernote/emoji/js/tam-emoji.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <script src="https://checkout.airwallex.com/assets/elements.bundle.min.js"></script>

    <script type="text/javascript" src="//c.webtrends-optimize.com/acs/accounts/7988bb72-52e8-4499-a177-7583e905f074/js/wt.js"></script>
    @livewireStyles

    <link rel="stylesheet" href="{{ asset('css/booking/simple.css') }}">

    <script src="{{ asset('js/booking/simple.js') }}"></script>

    <script type="text/javascript">
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-P6TKNMN');
    </script>

    <script type="text/javascript">
        window.addEventListener('popupCheckout', (event) => {
            Airwallex.init({
                env: '{{ config('app.gateways.airwallex.live', false) ? 'prod' : 'demo' }}',
                origin: window.location.origin,
            });
            const element = Airwallex.createElement('dropIn', {
                intent_id: event.detail.key,
                client_secret: event.detail.secret,
                currency: '{{ setting('system.currency', 'GBP') }}',
            });
            let mount = element.mount('airwallex-container');
            mount.addEventListener('onSuccess', (event) => {
                window.location = event.detail.intent.return_url;
            });
        });
    </script>

    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}"

    @stack('scripts')
</head>
<body>
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P6TKNMN" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

    <header>
        <section class="top-head test-class-pdf">
            <div class="row">
                <div class="col-one">
                    <div class="img-contain">
                        <img style="width: 300px; height: 32px;" src="{{ $brand->alt_image }}" alt="logo">
                    </div>
                </div>
                <div class="col-two">
                    <div class="cont-details">
                        <div class="single-div">
                            <p>
                                Need to customise your package? Contact our team
                            </p>
                        </div>
                        <div class="single-div">
                            <a class="phone" href="tel:{{ $brand->phone }}">
                                <span>{{ $brand->phone }}
                                </span>
                            </a>
                        </div>
                        <div class="single-div">
                            <a class="contact" href="https://www.kpt.com.au/contact-us/" target="_blank">ENQUIRE NOW</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </header>
    <main>
        <section class="top-block">
            <div class="row">
                <div class="top-nav-sec">
                    @if(!empty($return ?? null))
                        <div class="navi">
                            <a href="{{ $return }}">
                                <img src="{{ asset('css/booking/icon/arrow-left.svg') }}" alt="left-arrow">
                                <p>Back</p>
                            </a>
                        </div>
                    @endif
                    <div class="head">
                        <h1>Secure Booking</h1>
                    </div>
                </div>
            </div>
        </section>
        <section id="kpt-ms-sec-idmod" class="ma-block">
            @yield('content')
            {{-- Toast Notifications --}}
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <img src="{{ asset('css/booking/icon/toast-msg.svg') }}" class="rounded me-2" alt="...">
                        <strong class="me-auto">Message</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Hello, world! This is a toast message.
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <div class="bottom-foot">
            <div class="row">
                <div class="col-one">
                    <p>© 2024 {{ $brand->name }}.</p>
                </div>
                <div class="col-two">
                    <div class="contain">
                        <p>Legal Notices <span>|</span></p>
                        <p>
                            <a href="https://www.kpt.com.au/privacy-policy/" target="_blank">Privacy Policy</a>
                            <span>|</span>
                        </p>
                        <p>
                            <a href="https://www.kpt.com.au/terms-and-conditions/" target="_blank">Terms & Conditions</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @stack('popups')
        @livewireScripts
        @livewire('livewire-ui-modal')
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    </footer>
</body>
