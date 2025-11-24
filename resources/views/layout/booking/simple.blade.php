@php use App\Models\System\Brand; @endphp
@php /** @var Brand $brand */ @endphp
        <!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Simple Booking Form')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
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

    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">

    <script type="text/javascript" src="//c.webtrends-optimize.com/acs/accounts/7988bb72-52e8-4499-a177-7583e905f074/js/wt.js"></script>
    @livewireStyles

    <link rel="stylesheet" href="{{ asset('css/booking/simple.css') }}">

    <style>
        :root {
            --primary-color: #FFB319;
            --white: #FFFFFF;
            --text-light-dark: #383232;
            --text-dark: #000000;
            --sub-text-color: #808080;
            --sub-heading-2: #143e34;
            --next-btn:#ffb81c;
            --back-btn:#ffb81c;
        }
        body {background-color: #f4f4f4; color: #333333;}
        .top-head, .bottom-foot { background: linear-gradient(to right, #143e34 50%, #143e34 75%); }
        .top-head .row {padding: 14px 0;}
        .top-head .row .col-one .img-contain { max-height: 60px !important;}
        .top-head .row .col-two .cont-details .single-div a:hover {color: var(--primary-color);}
        .top-head .row .col-two .cont-details .single-div a.contact:after, .submit-btn-cls .inner:after   { filter: brightness(0) saturate(100%); }
        .form-field input:focus{ transition: all 0.5s; box-shadow: 0 0 40px #f9d442b9; border-color: #f9d342; outline: none; }
        .ma-block .row .left-col .contain .top-form-contain .form-field input {border: 2px solid #aaa;}
        .ma-block .row .left-col .contain .third-block .form-field:last-child select { border: 1px solid var(--primary-color) !important;}
        .bottom-foot .row { padding: 30px 0;}
        .ma-block .row {padding-bottom: 10px !important;}
        .accommodation-detail .locate {  border: 1px solid var(--primary-color);}
        .submit-btn-cls input {color:var(--text-dark)}
        .hotel-more-info a { color: #143e34;}
        .hotel-more-info a::after {filter: brightness(0) saturate(100%) invert(25%) sepia(21%) saturate(984%) hue-rotate(121deg) brightness(92%) contrast(90%);}
        .accommodation-detail .locate h5 { color: #7a7a7a;}
        .ma-block .row .right-col .contain .snd-sec .right-col p.see-more a {color: #143e34;}
        .ma-block .row .right-col.package_details_right .name_price_div h4 { color:var(--primary-color);}
        .ma-block .row .right-col .contain .snd-sec .right-col p.date:before, .upp-block-two .right-col p.date:before { filter: brightness(0) saturate(100%) invert(50%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(100%) contrast(100%);}
        .form-field-full-width .note-editor.note-frame.panel.panel-default {border: 1px solid #aaa;}
        .package_popup .upp-block-two .right-col h5, .package_popup .upp-block-two .snd-sec .right-col h6 {color:var(--primary-color);}
    </style>

    <script src="{{ asset('js/booking/simple.js') }}"></script>
    <script src="https://js.stripe.com/basil/stripe.js"></script>

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
        const stripe = Stripe('{{ config('app.gateways.stripe.publishable') }}');
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
        const fetchClientSecretFull = () => {
            return fetch('{{ route('api.stripe.checkout.secret.booking', ['token' => $booking?->token, 'full' => true]) }}')
                .then((response) => response.json())
                .then((json) => json.checkoutSessionClientSecret)
        }
        const fetchClientSecretToday = () => {
            return fetch('{{ route('api.stripe.checkout.secret.booking', ['token' => $booking?->token, 'full' => false]) }}', {method: 'GET'})
                .then((response) => response.json())
                .then((json) => json.checkoutSessionClientSecret)
        }
        window.addEventListener('popupStripeCheckout', (event) => {
            if (event.detail.checkout !== null) {
                let fn = (event.detail.full ?? false) ? fetchClientSecretFull : fetchClientSecretToday;
                stripe.initCheckout({fetchClientSecret: fn}).then((checkout) => {
                    let paymentElement = checkout.createPaymentElement();
                    paymentElement.mount('#stripe-container');

                    document.getElementById('stripe-hidden').style.visibility = 'inherit';

                    // Setup Buttons
                    const button = document.getElementById('pay-button');
                    const errors = document.getElementById('confirm-errors');
                    button.addEventListener('click', () => {
                        // Clear any validation errors
                        errors.textContent = '';

                        checkout.confirm().then((result) => {
                            if (result.type === 'error') {
                                errors.textContent = result.error.message;
                            }
                        });
                    });
                });
            }
        });
    </script>

    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}"

    @stack('scripts')
</head>
<body>
    @php //dd($brand); @endphp
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P6TKNMN" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

    <header>
        <section class="top-head test-class-pdf">
            <div class="row">
                <div class="col-one">
                    <div class="img-contain">
                        @if(isset($brand))
                            <a href="https://www.keithprowsetravel.com/" target="_blank">
                                <img style="width: 300px;" src="{{ $brand->alt_image }}" alt="lo{{ $brand->name }}go" title="{{ $brand->name }}">
                            </a>
                        @endif
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
                        <h1>@if(!empty($return ?? null))
                            Checkout
                            @else
                            Secure Booking
                        @endif
                            
                        </h1>
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
                    <p>© {{ date('Y') }} {{ $brand->name }}.</p>
                </div>
                <div class="col-two">
                    <div class="contain">
                        <p>Legal Notices <span>|</span></p>
                        <p>
                            <a href="https://www.keithprowsetravel.com/privacy-policy/" target="_blank">Privacy Policy</a>
                            <span>|</span>
                        </p>
                        <p>
                            <a href="https://www.keithprowsetravel.com/terms-and-conditions/" target="_blank">Terms & Conditions</a>
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
