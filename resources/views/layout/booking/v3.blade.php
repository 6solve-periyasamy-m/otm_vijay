<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keith Prowse Travel</title>
    <link rel="stylesheet" href="{{ asset('css/booking/v3.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    <script src="https://js.stripe.com/basil/stripe.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script src="https://use.fontawesome.com/52e183519a.js"></script>
    <link href="{{ asset('external/summernote/emoji/css/emoji.css') }}" rel="stylesheet">
    <script src="{{ asset('external/summernote/emoji/js/config.js') }}"></script>
    <script src="{{ asset('external/summernote/emoji/js/tam-emoji.min.js') }}"></script>
    <script src="{{ asset('/js/booking/v3.js') }}"></script>
    <script>
        document.emojiSource = "{{ asset('external/summernote/emoji/img') }}";
    </script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ setting('booking.captcha.key') }}"></script>
    <script>
        async function runRecaptcha(action, callback) {
            grecaptcha.ready(function () {
                grecaptcha.execute("{{ setting('booking.captcha.key') }}", {action: action})
                    .then(function (token) {
                        callback(token);
                    });
            });
        }
    </script>
    <script type="text/javascript">
        const stripe = Stripe('{{ config('app.gateways.stripe.publishable') }}');
        let elements;
        let paymentElement;
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
            return fetch('{!! route('api.stripe.checkout.secret.booking', ['token' => $booking?->token, 'full' => false]) !!}')
                .then((response) => response.json())
                .then((json) => {
                    return {intent: json.intent, secret: json.checkoutSessionClientSecret};
                })
        }
        const fetchClientSecretToday = () => {
            return fetch('{!! route('api.stripe.checkout.secret.booking', ['token' => $booking?->token, 'full' => false]) !!}', {method: 'GET'})
                .then((response) => response.json())
                .then((json) => {
                    return {intent: json.intent, secret: json.checkoutSessionClientSecret};
                })
        }
        window.addEventListener('popupStripeCheckout', async (event) => {
            if (event.detail.checkout !== null) {
                let fn = (event.detail.full ?? false) ? fetchClientSecretFull : fetchClientSecretToday;
                let keys = await fn();

                elements = stripe.elements({clientSecret: keys.secret, paymentMethodCreation: 'manual'});
                const elementOptions = {layout: 'accordion'};

                paymentElement = elements.create("payment", elementOptions);
                paymentElement.mount('#stripe-container');
                document.getElementById('stripe-hidden').style.visibility = 'inherit';

                const button = document.getElementById('pay-button');
                const errors = document.getElementById('confirm-errors');

                button.addEventListener('click', async () => {
                    await elements.submit();
                    // Clear any validation errors
                    errors.textContent = '';

                    const {pmErr, paymentMethod} = await stripe.createPaymentMethod({
                        elements,
                        params: {}
                    })

                    if (pmErr) {
                        console.log(pmErr);
                        return;
                    }

                    await fetch('{!! route('api.stripe.checkout.secret.attach') !!}', {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({secret: keys.intent, paymentMethod: paymentMethod.id}),
                    });

                    let {error} = await stripe.confirmPayment({
                        elements,
                        confirmParams: {
                            // TODO: Update
                            return_url: window.location.href,
                        }
                    });
                    if (error.type === "card_error" || error.type === "validation_error") {
                        console.log(error.message);
                    } else {
                        console.log("An unexpected error occurred.");
                    }
                });
            }
        });
    </script>
    {!! setting('booking.head.tracking.code') !!}

    @livewireStyles

    <style>
        :root {
            --primary-color: #F35B15;
            --white: #FFFFFF;
            --text-light-dark: #383232;
            --text-dark: #000000;
            --sub-text-color: #808080;
            --border-bottom-color: #D1D5DB;
            --include-cta-color: #C0C0C8;
            --include-text: #E2E2E2;
            --error-label-color: #FF0000;
            --error-message-color: #9F0A1A;
            --footer-color: #000000;
            --secondary-color: #FEEFE8;
            --text-ligh-grey: #7A7A7A;
        }

        body { background-color: transparent; margin: 0; } 
        .container { max-width: 1410px; width: 90%; position: relative; margin: auto; } 
        p, h6, h5, h4 { font-family: "PP Neue Montreal Medium"; font-weight: 500; } 
        @font-face { font-family: "PP Editorial New"; src: url("{{ asset('fonts/PPEditorialNew-Ultralight.ttf') }}"); } 
        @font-face { font-family: "PP Neue Montreal Medium"; src: url("{{ asset('fonts/PPNeueMontreal-Medium.ttf') }}"); } 
        @font-face { font-family: "PP Neue Montreal Bold"; src: url("{{ asset('fonts/ppneuemontreal-bold.otf') }}"); } 
        @font-face { font-family: "PlayfairDisplay-Regular"; src: url("{{ asset('fonts/PlayfairDisplay-Regular.ttf') }}"); } 
        @font-face { font-family: "Inter-Medium"; src: url("{{ asset('fonts/Inter-Medium.ttf') }}"); } 
        header { background-color: var(--primary-color); } 
        header .container { display: flex; justify-content: space-between; align-items: center; } 
        header .column { display: flex; } 
        header .column.right { display: flex; align-items: center; column-gap: 16px; } 
        header .column.right p { font-family: "PP Neue Montreal Medium"; font-weight: 500; font-size: 14px; line-height: 20px; color: var(--white); text-transform: uppercase; letter-spacing: 2.24px; margin: 0; } 
        header .column.right a { background-color: var(--white); padding: 12px 16px; border-radius: 40px; font-family: "PP Neue Montreal Medium"; font-weight: 500; color: var(--primary-color); display: flex; column-gap: 8px; align-items: center; text-decoration: unset; font-size: 14px; line-height: 18px; letter-spacing: 2.24px; } 
        header .container { padding: 15px 0px; }
        body main { padding-bottom: 100px; } 
        footer { width: 100%; background-color: rgba(59, 59, 59, 0.97); position: fixed; bottom: 0; z-index: 9; } 
        footer .container { display: flex; justify-content: space-between; align-items: center; padding: 15px 0px; }
        .Go-back{display:inline-block;padding:11.5px 56px;border:1.5px solid var(--white);border-radius:999px;font-family:"PP Neue Montreal Medium";font-weight:500;font-size:14px;line-height:20px;color:var(--white);cursor:pointer}
        .Go-next{display:inline-block;padding:11.5px 56px;border:1.5px solid #f35b15;border-radius:999px;font-family:"PP Neue Montreal Medium";font-weight:500;font-size:14px;line-height:20px;color:var(--white);cursor:pointer;background:var(--primary-color)}
        footer .value{display:flex;column-gap:16px;align-items:center}
        footer .value h6{font-family:"PP Neue Montreal Bold";font-weight:700;color:var(--white);font-size:18px;line-height:24px;margin:0}
        footer .value p{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:18px;line-height:24px;color:var(--white);margin:0}
        footer .value span{display:inline-block;width:1px;height:40px;background:var(--white)}@media only screen and (min-width:768px) and (max-width:1024px){.Go-back,.Go-next{padding:11.5px 38px}}
        header .column img{width:100%;height:100%;object-fit:contain}
        .view-details{display:none}
        @media only screen and (max-width:767.98px){
            .container{width:94%}
            header .column:first-child{max-width:139px}
            header .column.right p{display:none}
            .view-details{display:block}
            footer .container{flex-wrap:wrap;row-gap:18px}
            footer .value p{font-size:11px;line-height:18px}
            footer .value{column-gap:6px}
            .Go-back{order:3}
            footer .value{order:1}
            .view-details{order:2;font-size:12px;color:#ff8f1c;font-family:"PP Neue Montreal Medium";text-decoration:underline;line-height:12px}
            .Go-next{order:4}
            .timeline a{z-index:1}
        }

        .secure-booking{padding:24px 0;background:#f9f4ee}
        h1{font-family:PlayfairDisplay-Regular;text-align:center;font-size:40px;margin:0;line-height:40px;color:var(--primary-color);font-weight:400;text-transform:uppercase;margin-bottom:24px}
        .breadcrumbs{display:flex;align-items:center;column-gap:8px}
        .breadcrumbs span:first-child{height:16px}
        .breadcrumbs span{font-family:Inter-Medium;font-weight:500;font-size:14px;line-height:20px;letter-spacing:2.24px}
        :root{--circle-size:32px;--line-gap:8px}
        .step .circle{width:var(--circle-size);height:var(--circle-size)}
        .timeline{display:flex;justify-content:space-between;align-items:center;margin:0 auto;max-width:1410px;width:90%}
        .step{display:flex;flex-direction:column;align-items:center;position:relative;flex:1;text-align:center;color:var(--text-light-dark)}
        .step:not(:last-child)::after{content:'';position:absolute;top:16px;height:2px;background:var(--text-light-dark);z-index:0;left:calc(50% + calc(var(--circle-size)/ 2) + var(--line-gap));width:calc(100% - var(--circle-size) - calc(var(--line-gap) * 2))}
        .step.completed:not(:last-child)::after{background:var(--primary-color)}
        .step .circle{width:32px;height:32px;border-radius:50%;border:2px solid var(--text-light-dark);display:flex;align-items:center;justify-content:center;z-index:1;margin-bottom:10px;background:#f9f4ee}
        .step.completed .circle{background:var(--primary-color);border-color:var(--primary-color);color:var(--white)}
        .step.active .circle{border-color:var(--primary-color);color:var(--primary-color);font-weight:700}
        .step.completed .circle::before{content:'';width:24px;height:24px;background-image:url('/icons/Correct-vector.svg');background-repeat:no-repeat;position:relative;top:6px;left:4px}
        .step.active .circle::before{content:'';width:10px;height:10px;border-radius:50%;background:var(--primary-color);display:block}
        .step .label{text-align:center;font-family:"PP Neue Montreal";font-size:13px;font-style:normal;font-weight:500;line-height:normal}
        .step.active .label,.step.completed .label{color:var(--primary-color)}
        .step.active span,.step.completed span{display:none!important}
        .step:not(.completed):not(.active) span{display:inline!important}

        @media only screen and (max-width:767.98px){
            .step:not(.active) .label{display:none!important}
            .step:not(:last-child)::after{left:50%!important;width:100%!important;top:10px}
            .step{min-height:80px}
            .step:not(.active) span{display:inline!important}
            .step.active span,.step.completed span{display:none!important}
            .step:not(.completed):not(.active) span{display:inline!important}
            .step .circle{width:22px;height:22px;font-size:11px!important}
            .step.active .circle::before{width:8px;height:8px}
            .step.completed .circle{position:relative}
            .timeline .step.active .label{position:absolute;bottom:20px}
        }

        .timeline .step .circle span,.timeline .step .label{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:13px;line-height:16px;color:var(--text-light-dark)}
        .timeline .step.active .circle span,.timeline .step.active .label,.timeline .step.completed .circle span,.timeline .step.completed .label{font-family:Inter-Medium;font-weight:500;color:var(--primary-color)}

        @media only screen and (min-width: 768px) and (max-width: 980px) {
            .timeline .step > .label {height: 43px;}
            .hotel-zoom-overlay .hotel-zoom-slider,.default-zoom-overlay .default-zoom-slider{height: auto;}
            .default-zoom-overlay .default-zoom-slider .slick-slide > div > div,
            .hotel-zoom-overlay .hotel-zoom-slider .slick-slide > div > div{height: 600px;}
            .default-hotel-image-popup-block .slick-slide img,
            .hotel-zoom-overlay .hotel-zoom-slider img, .default-zoom-overlay .default-zoom-slider img{height: 100%; object-fit: cover;}
        }

        /* timeline bar css end */
        /* Guest */
        .package-container .container{display:flex}
        .package-container .container>.left{width:57.5%;margin-right:2%}
        .package-container .container>.right{width:40.5%}
        .package-container{padding:60px 0}
        .sub-heading-2{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:36px;line-height:42px;color:var(--primary-color);letter-spacing:2.24px;margin:0 0 16px 0;text-transform:uppercase}
        .sub-heading-3{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:32px;line-height:32px;color:var(--text-dark);margin:0 0 16px 0}
        .location-dollar-value{display:flex;column-gap:8px;align-items:center;padding-bottom:40px;border-bottom:1px solid var(--border-bottom-color)}
        .location-dollar-value p{font-size:16px;line-height:24px;padding-left:24px;position:relative;margin:0}
        .location-dollar-value p:before{content:"";background:url('/icons/Location.svg') no-repeat;background-repeat:no-repeat;display:inline-block;width:16px;height:16px;position:absolute;top:4px;left:0}
        .hotel-listing .single-hotel .hotel-block .room-type>p.breakfast-note{color:var(--sub-text-color)}
        .location-dollar-value p.dollar:before{background:url('/icons/Dollar.svg') no-repeat}
        .location-dollar-value>span{display:inline-block;width:2px;height:24px;background:var(--border-bottom-color)}
        .no-of-travellers{display:flex;align-items:center;justify-content:space-between;margin:40px 0}
        .quantity{width:153px;display:flex;column-gap:8px;align-items:center;height:52px;background-color:#f9f4ee;border-radius:99px;justify-content:center;font-family:"PP Neue Montreal Medium";font-weight:500;font-size:14px;color:var(--primary-color)}
        .quantity span.value{width:32px;text-align:center;color:var(--text-dark)}
        .quantity .minus,.quantity .plus{cursor:pointer;display:flex}
        .sub-heading-4{font-size:24px;line-height:32px;margin:0;color:var(--text-dark)}
        .contact-block{background:#f9f4ee;padding:24px;border-radius:16px}
        .contact-block p{font-size:16px;line-height:19px;color:var(--text-dark);position:relative}
        .contact-block p a{color:var(--primary-color);text-decoration:none}
        .contact-block p:before{content:"";position:absolute;display:inline-block}
        .contact-block p.description:before{background:url('/icons/Round-circle.svg') no-repeat;width:18px;height:18px;top:0;left:0}
        .contact-block p.phone:before{background:url('/icons/Phone.svg') no-repeat;width:16px;height:16px;top:0;left:27px}
        .contact-block p.email:before{background:url('/icons/Email.svg') no-repeat;width:16px;height:16px;top:0;left:27px}
        .contact-block p.email,.contact-block p.phone{padding-left:53px}
        .contact-block p.description{padding-left:26px}
        .contact-block p{padding-bottom:16px;margin:0}
        .contact-block p.phone{padding-bottom:8px}
        .contact-block p.email{padding-bottom:0}
        .package-container.first .container>.left>div{max-width:590px}
        .package-container.first .container>.left .additional-inclusions-module,.package-container.first .container>.left .hotel,.package-container.first .container>.left .room-selection,.package-container.first .container>.left .tickets{max-width:100%}
        .breadcrumbs{width:69px;cursor:pointer}
        .package-details{background-color:var(--white);border-radius:40px;box-shadow:0 0 34px 0 #00000026;padding:32px}
        .package-details h4{margin:0 0 32px 0}
        .more-package-block{max-height:563px!important;overflow-y:auto;scrollbar-width:none}
        .popup-tour-description ul li{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:16px;line-height:32px;color:var(--text-ligh-grey)}
        .popup-package-details .image-block{height:293px;width:100%;margin:10px 0 10px 0}
        .package-details .contain .image-block{height:250px;width:100%;margin-bottom:32px}
        .package-details .contain .image-block img,.popup-package-details .image-block img{width:100%;height:100%;object-fit:cover;border-radius:16px}
        .base-package{background-color:#f9f4ee;padding:16px;border-radius:16px;display:block;margin-bottom:32px}
        .base-package h6{font-size:14px;line-height:16px;color:var(--primary-color);letter-spacing:2.24px;margin:0 0 14px 0}
        .base-package h2{font-family:PlayfairDisplay-Regular;font-size:30px;line-height:40px;font-weight:400;color:var(--primary-color);text-transform:uppercase;margin-top:10px}
        .popup-base-package h2{font-family:PlayfairDisplay-Regular;font-size:25px;line-height:30px;font-weight:350;color:var(--primary-color);text-transform:uppercase;margin-top:5px}
        .popup-base-package h6{font-family:"PP Neue Montreal Medium";font-size:15px;line-height:30px;font-weight:350;color:var(--primary-color);text-transform:uppercase;margin-top:5px}
        .popup-base-package p{font-size:16px;line-height:24px;color:var(--text-dark);position:relative;margin-bottom:12px}
        .base-package ul,.popup-base-package ul{padding-left:30px;margin:0}
        .base-package ul li,.popup-base-package ul li{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:16px;line-height:32px;color:var(--text-dark);list-style-type:square}
        .additional-inclusions>div{border-top:1px solid rgba(243,91,21,.2);border-bottom:1px solid rgba(243,91,21,.2);padding:32px 0}
        .additional-inclusions>div .single{display:flex;justify-content:space-between}
        .additional-inclusions>div .single p{font-size:16px;line-height:24px;color:var(--sub-text-color);margin:0}
        .additional-inclusions h6{margin:0 0 32px 0;font-size:14px;letter-spacing:2.24px;color:var(--primary-color)}
        .additional-inclusions>div h5{font-family:"PP Neue Montreal Bold";font-weight:700;font-size:16px;line-height:24px;color:var(--text-dark);margin:0 0 8px 0;letter-spacing:0}
        .select-currency .single:first-child>p{font-size:16px;line-height:24px;color:var(--text-dark)}
        .select-currency .single:first-child>select{font-size:16px;line-height:24px;padding:4px 12px;border:1px solid var(--primary-color);border-radius:999px;width:82px;font-family:"PP Neue Montreal Medium";font-weight:500;outline:0}
        .select-currency .single:first-child>select{appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url(/icons/Chevron-down.svg);background-repeat:no-repeat;background-position:right 12px center;background-size:16px 16px;cursor:pointer}
        .select-currency .single:first-child{margin-bottom:12px}
        .select-currency .single:nth-child(2){margin-bottom:8px}
        .select-currency .single:nth-child(2) p{color:var(--text-dark)}
        .additional-inclusions>div .single p>span{display:block}
        .total .single:nth-child(2){margin-top:32px}
        .total .single:first-child p{font-family:"PP Neue Montreal Bold";font-weight:700;color:var(--text-dark);font-size:18px;line-height:24px}
        .option-wrapper{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:20px;column-gap:8px}
        .radio-group{display:flex;flex-direction:column;gap:8px}
        .radio-option{display:flex;align-items:center;gap:10px;cursor:pointer}
        .radio-option input[type=radio]{display:none}
        .custom-radio{width:24px;height:24px;border:2px solid var(--primary-color);border-radius:50%;position:relative;flex-shrink:0}
        .custom-radio::after{content:'';position:absolute;width:18px;height:18px;background-color:var(--primary-color);border-radius:50%;opacity:0;transition:.2s}

        .radio-option input[type=radio]:checked+.custom-radio::after{opacity:1}
        .option-title{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:16px;line-height:24px;color:var(--text-dark)}
        .option-subtext{font-size:16px;color:#000;line-height:1.5;margin-left:38px;margin-top:8px}
        .option-subtext,.price{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:16px;line-height:24px;max-width:346px}
        .option-subtext{font-size:17px;line-height:22px}
        .payment-method h6{font-family:"PP Neue Montreal Medium";font-weight:500;color:var(--primary-color);margin:32px 0 24px 0;font-size:18px;line-height:20px;letter-spacing:2.24px}
        .card-block{display:flex;justify-content:space-between;column-gap:14px}
        .card-block .card-type{border:2px solid var(--primary-color);width:48%;border-radius:16px;padding:16px}
        .card-block .card-type p{font-family:"PP Neue Montreal Medium";font-weight:500;color:var(--text-dark);font-size:16px;line-height:20px;margin:0}
        .card-block .card-type.active{background-color:#feefe8}
        .payable-now .single{margin-top:32px;display:flex;justify-content:space-between}
        .payable-now p{font-size:16px;line-height:24px;margin:0;color:var(--text-dark)}
        .payable-now>p{margin-top:8px;color:var(--sub-text-color)}
        .payment-method .email-quote h6{margin:32px 0 24px 0;text-transform:uppercase;text-align:center;text-decoration:underline;font-size:16px;line-height:20px;cursor:pointer}
        .email-quote label{font-size:16px;line-height:24px;margin:0;color:var(--text-dark);width:100%;display:block;margin-bottom:12px;font-family:"PP Neue Montreal Medium";font-weight:500}
        .top-form-contain .email-quote input{width:82%;height:46px;border:2px solid #f35b15;outline:0;font-size:14px;line-height:14px;padding-block:0px;padding-inline:0px;border-radius:50px;padding:0 24px;margin-bottom:12px}
        .top-form-contain .email-quote{display:flex;gap:24px;justify-content:space-between}
        .top-form-contain .email-quote p{width:50%;margin-block-start:0px;margin-block-end:24px}
        .top-form-contain .mdle_nme{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:12px;line-hight:14px;color:#7f7f7f;display:block}
        .top-form-contain{padding-top:40px}
        .top-form-contain .email-quote .text-danger{display:block}
        .next-button{margin-top:24px;width:100%;height:67px;background:var(--primary-color);border:0;border-radius:99px;cursor:pointer}
        .next-button>span{display:flex;align-items:center;justify-content:center;column-gap:8px}
        .next-button>span>span{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:16px;line-height:20px;letter-spacing:2.24px;color:var(--white)}
        @media only screen and (min-width:768px) and (max-width:980px){
            .package-container .container{flex-wrap:wrap;row-gap:60px}
            .package-container .container>.left,.package-container .container>.right{width:100%;margin-right:0}
        }
        .hide-package-detail{display:none}
        @media only screen and (max-width:767.98px){
            .package-container .container{flex-wrap:wrap;row-gap:60px}
            .package-container .container>.left,.package-container .container>.right{width:100%;margin-right:0}
            h1{margin-top:12px;font-size:32px;line-height:40px}
            .sub-heading-2{font-size:24px;line-height:32px}
            .sub-heading-3{font-size:20px;line-height:32px}
            .package-container .container>.left .location-dollar-value{display:block}
            .location-dollar-value>span{display:none}
            .location-dollar-value p.location{margin-bottom:10px}
            .sub-heading-4{font-size:20px;line-height:28px}
            .right .sub-heading-4{font-size:24px;line-height:32px}
            .package-details{padding:24px;border-radius:16px}
            .package-details .contain .image-block{height:209px;margin-bottom:24px}
            .base-package h2{font-size:24px;line-height:32px}
            .package-details .top-module{display:flex;justify-content:space-between;align-items:center;position:sticky;top:-26px;background:#fff;padding:10px 0 10px 0;margin-bottom:22px}
            .package-details h4{margin-bottom:0}
            .hide-package-detail{display:block;font-family:"PP Neue Montreal Medium";font-weight:500;color:var(--primary-color);font-size:12px;line-height:16px;text-decoration:underline}
            .package-details{border-radius:16px;max-height:475px;min-height:475px;overflow:auto;padding-bottom:150px}
            .package-container .container .column.right{position:fixed;width:100%;height:100%;background:rgba(0,0,0,.6);top:0;left:0;padding-top:120px;z-index:99}
            .package-container .container .column.right{display:none}
            .email-quote input{width:100%}
            .email-quote{flex-flow:column}
            .top-form-contain .email-quote p{width:60%}
        }
        .sub-heading-2-p{font-family:PlayfairDisplay-Regular;font-weight:400;font-size:40px;line-height:54px;color:var(--text-light-dark);margin:40px 0 24px 0;text-transform:uppercase}
        .accommodation-detail>p{font-size:20px;line-height:28px;color:#605b5b;margin:0 0 40px 0}
        .sub-heading-6{font-family:"PP Neue Montreal Medium";font-size:14px;line-height:18px;color:var(--primary-color);letter-spacing:2.24px;margin:0 0 24px 0}
        .accommodation-detail .locate{display:flex;border-radius:24px;border:2px solid var(--primary-color);max-width:384px}
        .accommodation-detail .image{width:125px;display:flex}
        .accommodation-detail .image img{width:100%;height:100%;object-fit:cover;border-top-left-radius:24px;border-bottom-left-radius:24px}
        .accommodation-detail .locate h6{margin:0 0 6px 0;font-family:"PP Neue Montreal Bold";font-weight:700;font-size:14px;line-height:18px;color:var(--primary-color)}
        .accommodation-detail .text-block{padding:24px}
        .accommodation-detail .locate p{margin:0;font-size:12px;line-height:22px;color:var(--primary-color)}
        .check-in-check-out{border:1.5px solid var(--primary-color);display:flex;max-width:369px;border-radius:999px;padding:8px 24px;justify-content:space-between;align-items:center;color:var(--primary-color);font-size:20px}
        .check-in-check-out .first,.check-in-check-out .second{display:flex;align-items:center;width:134px;justify-content:space-between}
        .check-in-check-out .second{flex-direction:row-reverse}
        .check-in-check-out .first .image-module,.check-in-check-out .second .image-module{position:relative;width:20px;height:20px;cursor:pointer}
        .check-in-check-out .image-module input{width:20px;height:20px;position:absolute;left:0;opacity:0}
        .check-in-check-out .text-block p:first-child{font-size:12px;line-height:18px;color:var(--primary-color);margin:0;text-align:center}
        .check-in-check-out .text-block p:last-child{font-size:16px;line-height:20px;margin:0;color:var(--sub-text-color)}
        .booking-dates{border-bottom:1px solid var(--border-bottom-color);border-top:1px solid var(--border-bottom-color);padding:40px 0;margin:40px 0}
        .booking-dates p{margin:0 0 24px 0;font-size:16px;line-height:24px;color:var(--text-ligh-grey)}
        .booking-dates>p:last-child{margin:0 0 12px 0;color:var(--text-dark)}
        .booking-dates p.mod{font-family:Inter-Medium;font-weight:500;margin:0 0 12px 0;color:var(--text-dark)}
        .room-configuration .no-of-travellers{max-width:420px;margin-top:24px}
        .room-configuration .no-of-travellers p{margin:0;font-size:16px;line-height:24px;color:#000}
        .room-selection{border-bottom:1px solid var(--border-bottom-color);border-top:1px solid var(--border-bottom-color);padding:40px 0;margin:40px 0}
        .room-selection>p{margin:0 0 16px 0;font-size:16px;line-height:24px;color:var(--text-ligh-grey)}
        .room-selection .showcase{display:flex;column-gap:24px;margin-bottom:40px}
        .room-selection .showcase .single{display:flex;align-items:center;column-gap:16px}
        .room-selection .showcase .single>div:first-child{display:flex;column-gap:4px}
        .showcase p{font-size:16px;margin:0}
        .room-listing-module{display:flex;flex-flow:wrap;gap:16px;width:100%}
        .room-listing-module .single-room{width:31%}
        .room-listing-module .single-room:nth-child(3n){margin-right:0}
        .room-listing-module .single-room{border:1px solid var(--primary-color);padding:24px;border-radius:24px;box-sizing:border-box}
        .room-listing-module .single-room h6{font-family:"PP Neue Montreal Bold";font-weight:700;font-size:14px;line-height:18px;color:var(--primary-color);margin:0 0 4px 0}
        .room-listing-module .single-room p{margin:0 0 4px 0;font-size:12px;line-height:22px;color:var(--text-light-dark)}
        .room-listing-module .single-room ul{margin:4px 0;padding-left:20px}
        .room-listing-module .single-room ul li{color:var(--sub-text-color);font-size:12px;line-height:22px;font-family:"PP Neue Montreal Medium";font-weight:500}
        .guest-module{width:100%;display:flex;justify-content:space-between;border:1px solid var(--primary-color);height:32px;align-items:center;border-radius:999px;margin-bottom:4px;overflow:hidden}
        .guest-module>div{width:33.3%;height:32px;display:flex;align-items:center;justify-content:center;font-family:"PP Neue Montreal Medium";font-weight:500;font-size:14px;line-height:18px;cursor:pointer}
        .guest-module>div.active{background:var(--primary-color);color:#fff}
        .bed-configuration{display:flex;border:1.5px solid var(--primary-color);border-radius:999px;height:32px;margin-bottom:8px;align-items:center;cursor:pointer}
        .bed-configuration .bed-icon{width:50%;text-align:center;border-right:1px solid var(--primary-color);display:flex;justify-content:center}
        .bed-configuration .twin{width:50%;text-align:center;font-family:"PP Neue Montreal Medium";font-weight:500;font-size:12px;line-height:14px;color:var(--primary-color)}
        .bed-configuration .bed-icon img.hover,.bed-configuration.active .bed-icon img.default{display:none}
        .bed-configuration.active{background:var(--primary-color)}
        .bed-configuration.active .bed-icon{border-right:1px solid var(--white)}
        .bed-configuration.active .twin{color:var(--white)}
        .bed-configuration.active .bed-icon img.hover{display:block}
        .include-button{margin-top:16px;width:100%;height:36px;background-color:var(--include-cta-color);border:0;border-radius:999px;cursor:pointer;color:var(--include-text);letter-spacing:2.24px;text-transform:uppercase}
        @media only screen and (max-width:359px){
            footer .container>div{justify-content:center;text-align:center}
            footer .container{flex-direction:column}
        }
        .hotel>p{font-size:16px;margin:24px 0;line-height:24px;color:var(--text-light-grey)}
        .hotel-listing{width:100%;display:flex;flex-flow:wrap;gap:16px}
        .hotel-listing .single-hotel{width:31%;border:2px solid #e2e2e2;box-shadow:0 0 12px 0 #0000001A;border-radius:24px}
        .hotel-listing .single-hotel:nth-child(3n){margin-right:0}
        .hotel-listing .single-hotel .hotel-image-block>div,.hotel-listing .single-hotel .hotel-image-block>div>div>div,.hotel-listing .single-hotel .hotel-image-block>div>div>div>div,.hotel-listing .single-hotel .hotel-image-block>div>div>div>div>div{width:100%;height:200px}
        .hotel-more-info-popup .hotel-more-info-contain .hotel-image-popup-block>div,.hotel-more-info-popup .hotel-more-info-contain .hotel-image-popup-block>div>div>div,.hotel-more-info-popup .hotel-more-info-contain .hotel-image-popup-block>div>div>div>div,.hotel-more-info-popup .hotel-more-info-contain .hotel-image-popup-block>div>div>div>div>div{width:100%;height:320px}
        .default-hotel-more-info-popup .default-hotel-more-contain .default-hotel-image-popup-block>div,.default-hotel-more-info-popup .default-hotel-more-contain .default-hotel-image-popup-block>div>div>div,.default-hotel-more-info-popup .default-hotel-more-contain .default-hotel-image-popup-block>div>div>div>div,.default-hotel-more-info-popup .default-hotel-more-contain .default-hotel-image-popup-block>div>div>div>div>div{width:100%;height:320px}
        .hotel-listing .single-hotel .hotel-image-block>div img{width:100%;height:100%;object-fit:cover;border-top-left-radius:22px;border-top-right-radius:22px}
        .hotel-more-info-popup .hotel-more-info-contain .amenities-container,.hotel-more-info-popup .hotel-more-info-contain .hotel-image-block{margin-top:20px}
        .hotel-more-info-popup .hotel-more-info-contain .hotel-image-block>div img{width:100%;height:100%;object-fit:cover;border-top-left-radius:0;border-top-right-radius:0}
        .hotel-listing .single-hotel .hotel-block{padding:24px}
        .hotel-listing .single-hotel .hotel-block>h6{font-family:"PP Neue Montreal Bold";font-weight:700;font-size:14px;line-height:17px;color:var(--primary-color);margin:0 0 6px 0}
        .hotel-listing .single-hotel .hotel-block>p{font-size:12px;line-height:22px;color:var(--primary-color);margin:0 0 4px 0}
        .hotel-listing .single-hotel .hotel-block .room-type{margin-top:6px}
        .hotel-listing .single-hotel .hotel-block .room-type>p{font-size:12px;line-height:22px;color:var(---text-light-dark);margin:0 0 6px 0}
        .hotel-listing .single-hotel .hotel-block .room-type select,.inclusion-single select{appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url(/icons/Chevron-down.svg);background-repeat:no-repeat;background-position:right 10px center;background-size:16px 16px;cursor:pointer;font-size:12px;line-height:14px;padding:4px 22px;border:1px solid var(--primary-color);border-radius:999px;width:100%;font-family:"PP Neue Montreal Medium";font-weight:500;outline:0;color:var(--primary-color);margin-bottom:11px;text-wrap:wrap}
        .hotel-listing .single-hotel .hotel-block .room-type span{font-size:8px;line-height:10px;color:var(--error-label-color);font-family:"PP Neue Montreal Medium";font-weight:500;margin-top:16px;text-align:center;width:100%;display:block}
        .include-button.active{background-color:var(--primary-color)}
        .hotel-listing .single-hotel:hover{border:2px solid var(--primary-color)}
        .single-hotel .slick-dots{display:flex;justify-content:center;column-gap:3px;bottom:16px}
        .single-hotel .slick-dots li{padding:0;width:8px;height:8px;margin:0}
        .single-hotel .slick-dots button{padding:0;width:8px;height:8px;margin:0;border:1px solid var(--white);border-radius:50%}
        .hotel-listing .single-hotel .hotel-image-block .slick-dots li button:before{opacity:1!important;color:rgba(255,255,255,.5);width:8px;height:8px;font-size:8px;border-radius:50%;line-height:10px}
        .hotel-listing .single-hotel .hotel-image-block .slick-dots li.slick-active button{border:1px solid var(--primary-color)}
        .hotel-listing .single-hotel .hotel-image-block .slick-dots li.slick-active button:before{color:var(--primary-color)}
        .hotel-listing .single-hotel .hotel-image-block{margin-bottom:0!important}
        .hotel-listing .single-hotel .hotel-image-block .slick-prev{left:20px;z-index:9}
        .hotel-listing .single-hotel .hotel-image-block .slick-next{right:20px;z-index:9}
        .hotel-listing .single-hotel .hotel-image-block .slick-prev:before{content:"";display:inline-block;width:12px;height:20px;background:url(/icons/Left-Arrow-Slick.svg) no-repeat center center/cover;position:relative;cursor:pointer;opacity:1}
        .hotel-listing .single-hotel .hotel-image-block .slick-next:before{content:"";display:inline-block;width:12px;height:20px;background:url(/icons/Slick-Right-Arrow.svg) no-repeat center center/cover;position:relative;cursor:pointer;opacity:1}
        .accomodation-travel-date-error{display:flex;margin-top:32px;column-gap:4px}
        .accomodation-travel-date-error span{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:13px;line-height:17px;color:var(--error-message-color)}
        @media only screen and (min-width:981px) and (max-width:1279.98px){
        .hotel-listing,.room-listing-module{flex-wrap:wrap;row-gap:20px}
        .hotel-listing .single-hotel,.room-listing-module .single-room{width:47%;margin-right:3%!important}
        .hotel-listing .single-hotel:nth-child(2n),.room-listing-module .single-room:nth-child(2n){margin-right:0!important}
        .default-zoom-overlay .default-zoom-slider .slick-slide>div>div,.hotel-zoom-overlay .hotel-zoom-slider .slick-slide>div>div{height:600px}
        .default-hotel-image-popup-block .slick-slide img,.default-zoom-overlay .default-zoom-slider img,.hotel-zoom-overlay .hotel-zoom-slider img{height:100%;object-fit:cover}
        }
        @media only screen and (max-width:767.98px){
        .hotel-listing,.room-listing-module{flex-wrap:wrap;row-gap:20px}
        .hotel-listing .single-hotel,.room-listing-module .single-room{width:100%;margin-right:0!important}
        .room-selection .showcase{row-gap:20px;flex-wrap:wrap}
        .sub-heading-2-p{font-size:36px;line-height:48px}
        }
        .display-none{display:none!important}
        .flatpickr-calendar{min-width:368px;padding:22px 15px;z-index:9!important}
        .flatpickr-calendar .flatpickr-months{width:170px;margin:auto;position:relative;height:25px;margin-bottom:16px;align-items:center;padding-top:15px}
        .flatpickr-calendar .flatpickr-months>span{top:unset;padding:0}
        .flatpickr-current-month{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:14px;left:0;text-align:center;width:100%;padding:0;color:var(--text-dark)}
        .flatpickr-current-month span.cur-month{margin:0;font-weight:unset}
        .flatpickr-current-month .numInputWrapper .arrowDown,.flatpickr-current-month .numInputWrapper .arrowUp{display:none}
        .flatpickr-calendar .numInputWrapper{width:4.5ch}
        .flatpickr-calendar span.flatpickr-weekday{font-family:"PP Neue Montreal Medium";font-weight:500;color:var(--text-dark);font-size:12px;line-height:16px}
        .flatpickr-calendar .flatpickr-innerContainer{justify-content:center}
        .flatpickr-calendar .flatpickr-innerContainer .flatpickr-rContainer .flatpickr-days .dayContainer span{font-family:"PP Neue Montreal Medium";font-weight:500;color:var(--text-dark);font-size:12px;line-height:16px;display:flex;align-items:center;justify-content:center}
        .flatpickr-calendar .flatpickr-innerContainer .flatpickr-rContainer .flatpickr-days .dayContainer span.flatpickr-disabled{color:var(--include-cta-color)}
        .rangeMode .flatpickr-day{margin-top:0;margin-bottom:8px}
        .flatpickr-day.endRange.endRange,.flatpickr-day.endRange.startRange,.flatpickr-day.selected.endRange,.flatpickr-day.selected.startRange,.flatpickr-day.startRange.endRange,.flatpickr-day.startRange.startRange{background:var(--primary-color);z-index:9;color:var(--white)!important;border:0;border-radius:50%!important}
        .flatpickr-day.inRange{-webkit-box-shadow:-18px 0 0 var(--secondary-color),18px 0 0 var(--secondary-color);box-shadow:-18px 0 0 var(--secondary-color),18px 0 0 var(--secondary-color);color:var(--text-dark)!important}
        .flatpickr-day.endRange.startRange+.endRange:not(:nth-child(7n+1)),.flatpickr-day.inRange,.flatpickr-day.nextMonthDay.inRange,.flatpickr-day.nextMonthDay.today.inRange,.flatpickr-day.nextMonthDay:focus,.flatpickr-day.nextMonthDay:hover,.flatpickr-day.prevMonthDay.inRange,.flatpickr-day.prevMonthDay.today.inRange,.flatpickr-day.prevMonthDay:focus,.flatpickr-day.prevMonthDay:hover,.flatpickr-day.selected.startRange+.endRange:not(:nth-child(7n+1)),.flatpickr-day.startRange.startRange+.endRange:not(:nth-child(7n+1)),.flatpickr-day.today.inRange,.flatpickr-day:focus,.flatpickr-day:hover{background:var(--secondary-color);border:0}
        .flatpickr-calendar .flatpickr-innerContainer .flatpickr-rContainer .flatpickr-days .dayContainer span.today{border:0}
        .flatpickr-calendar .flatpickr-innerContainer .flatpickr-rContainer .flatpickr-days .dayContainer span.today:hover{background:var(--secondary-color)}
        .flatpickr-day.endRange.startRange+.endRange:not(:nth-child(7n+1)),.flatpickr-day.selected.startRange+.endRange:not(:nth-child(7n+1)),.flatpickr-day.startRange.startRange+.endRange:not(:nth-child(7n+1)){-webkit-box-shadow:-18px 0 0 var(--secondary-color),0 0 0 var(--secondary-color);box-shadow:-18px 0 0 var(--secondary-color),0 0 0 var(--secondary-color);z-index:0;background:var(--primary-color)}
        .flatpickr-calendar.arrowTop:after,.flatpickr-calendar:before{display:none}
        .tickets-listing{display:flex;flex-wrap:wrap;width:100%;row-gap:24px}
        .single-block{width:32%;margin-right:2%;padding:24px;border-radius:24px;box-sizing:border-box;box-shadow:0 0 12px 0 #0000001A}
        .single-block:nth-child(3n){margin-right:0}
        .single-block .quantity-show{display:flex;align-items:end;column-gap:5px;margin-bottom:8px}
        .single-block .quantity-show span:first-child{display:flex}
        .single-block .quantity-show span:last-child{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:16px;line-height:20px;color:var(--text-dark)}
        .single-block .ticket-heading-module{display:flex;justify-content:space-between}
        .single-block .ticket-heading-module .content-module{width:100%}
        .single-block .ticket-heading-module .ic-block,.single-block .ticket-heading-module .ic-block>div{width:24px;height:24px;cursor:pointer}
        .single-block .ticket-heading-module .ic-block>div{cursor:pointer}
        .single-block .ticket-heading-module .ic-block>div{display:flex;align-items:center;justify-content:center;background-color:var(--secondary-color);border:1px solid var(--light-round-border-color);border-radius:50%}
        .single-block .ticket-heading-module h6{margin:0 0 4px 0}
        .single-block .ticket-heading-module h6 p{font-family:"PP Neue Montreal Bold";font-weight:700;font-size:14px;line-height:18px;margin:0 0 4px 0;color:var(--primary-color)}
        .single-block .content-module p,.tour_sales_price{margin:0 0 8px 0;font-size:12px;line-height:14px;color:var(--sub-text-color)}
        .single-block .individual-module p{margin:0 0 8px 0;font-size:12px;line-height:22px;color:var(--text-light-dark)}
        .single-block select{appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url(/icons/Chevron-down.svg);background-repeat:no-repeat;background-position:right 16px center;background-size:16px 16px;cursor:pointer;font-size:12px;line-height:16px;padding:4px 22px;border:1px solid var(--primary-color);border-radius:999px;width:100%;font-family:"PP Neue Montreal Medium";font-weight:500;outline:0;color:var(--primary-color);margin-bottom:8px;text-wrap:wrap}
        .session-block{display:flex;column-gap:16px;margin-bottom:8px}
        .session-block .radio-option{gap:8px}
        .session-block .option-title{font-size:14px;line-height:20px;color:var(--text-dark)}
        .radio-option .custom-radio{width:20px;height:20px;display:flex;justify-content:center;align-items:center}
        .radio-option .custom-radio::after{width:12px;height:12px}
        .radio-option input[type=radio]:checked+.custom-radio{border:2px solid var(--primary-color);background:0 0}
        .package-container .tickets{padding-bottom:40px;border-bottom:1px solid var(--border-bottom-color)}
        .package-container .tickets:last-child{padding-bottom:0;border-bottom:0 solid var(--border-bottom-color)}
        .ticket-pop-up-modal{position:fixed;top:0;left:0;right:0;bottom:0;background-color:#000000BF;width:100%;height:100%;padding:0;align-items:center;justify-content:center;z-index:1111!important;display:none}
        .ticket-pop-up-modal .ticket-contain-module{display:flex;align-items:center;justify-content:center;height:100%;width:100%}
        .ticket-pop-up-modal .ticket-contain-module .ticket-block{position:relative;max-width:1266px;width:90%;display:flex;justify-content:center}
        .ticket-pop-up-modal .ticket-contain-module .ticket-block img{width:100%;height:100%;object-fit:contain}
        .ticket-pop-up-modal .ticket-contain-module .close-button{position:absolute;top:24px;right:24px;cursor:pointer}
        .add-inclusion-block{width:100%;display:flex;flex-wrap:wrap;row-gap:24px}
        .inclusion-single{width:32%;margin-right:2%;border:2px solid #e2e2e2;box-shadow:0 0 12px 0 #0000001A;border-radius:24px;box-sizing:border-box}
        .inclusion-single:nth-child(3n){margin-right:0}
        .inclusion-single:hover{border:2px solid var(--primary-color)}
        .inclusion-single .inclusion-image{width:100%;height:200px}
        .inclusion-single .inclusion-image img{width:100%;height:100%;object-fit:cover;border-top-left-radius:22px;border-top-right-radius:22px}
        .inclusion-single .content-block{padding:24px}
        .inclusion-single .content-block p{font-size:12px;line-height:14px;margin:0 0 4px 0;color:var(--sub-text-color)}
        .inclusion-single .content-block h6{font-family:"PP Neue Montreal Bold";font-weight:700;color:var(--primary-color);font-size:14px;line-height:17px;margin:0 0 4px 0}

        .inclusion-single .content-block>p{font-size:12px;line-height:24px;color:var(--text-light-dark);margin:0}
        .inclusion-single .content-block>p.no-of-guests{color:var(--text-dark)}
        .inclusion-single .content-block>p.guest-value{margin:0;color:var(--primary-color);font-size:12px;line-height:22px}
        .inclusion-single .quantity{margin-top:8px}
        .inclusion-single .content-block>a{margin-top:16px;display:inline-block;font-size:12px;line-height:24px;color:var(--primary-color);text-decoration:underline;cursor:pointer;font-family:"PP Neue Montreal Medium";font-weight:500}
        .default-hotel-more-info-popup,.more-package-info-popup{position:fixed;top:0;left:0;right:0;bottom:0;background-color:#000000BF;width:100%;height:100%;padding:0;display:flex;align-items:center;justify-content:center;z-index:1111!important;display:none}
        .more-package-info-popup .more-package-contain .more-package-block{max-width:563px;width:86%;padding:58px 32px;background:var(--white);position:relative;box-sizing:border-box;border-radius:16px}
        .additional-inclusion-popup,.hotel-more-info-popup{position:fixed;top:0;left:0;right:0;bottom:0;background-color:#000000BF;width:100%;height:100%;padding:0;display:flex;align-items:center;justify-content:center;z-index:1111!important}
        .additional-inclusion-popup .additional-contain,.default-hotel-more-info-popup .default-hotel-more-contain,.hotel-more-info-popup .hotel-more-info-contain,.more-package-info-popup .more-package-contain{display:flex;align-items:center;justify-content:center;height:100%;width:100%;position:relative}
        .default-info-body,.info-body{max-height:600px!important;overflow-y:auto;overflow-x:hidden;padding-top:20px}
        .default-info-body::-webkit-scrollbar,.info-body::-webkit-scrollbar{width:8px}
        .default-info-body::-webkit-scrollbar-track,.info-body::-webkit-scrollbar-track{border-radius:16px}
        .default-info-body::-webkit-scrollbar-thumb,.info-body::-webkit-scrollbar-thumb{background:#fae8d3;border-radius:10px}
        .default-hotel-more-contain .more-default-hotel-block,.hotel-more-info-popup .hotel-more-info-contain .hotel-more-info-block{max-width:740px;width:86%;padding:58px 32px;background:var(--white);position:relative;box-sizing:border-box;border-radius:16px}
        .additional-inclusion-popup .additional-contain .additional-block{max-width:640px;width:86%;padding:58px 32px;background:var(--white);position:relative;box-sizing:border-box;border-radius:16px}
        .additional-inclusion-popup h4,.default-hotel-more-contain h4,.hotel-more-info-contain h4{font-family:Begum-Medium;text-transform:uppercase;font-weight:500;font-size:32px;line-height:44px;color:var(--primary-color);margin:0 0 20px 0}
        .default-hotel-more-contain h4,.hotel-more-info-contain h4{line-height:34px}
        .additional-inclusion-popup .additional-contain .additional-block p{font-family:Inter-Regular;font-weight:400;font-size:16px;line-height:24px;padding-bottom:10px;color:var(--text-dark);margin:0}
        .additional-inclusion-popup .add-cta{margin-top:30px;display:flex;justify-content:end;column-gap:16px}
        .additional-inclusion-popup .cancel{font-family:Inter-Medium;padding:14px 58px;background-color:transparent;border-radius:72px;border:1px solid var(--primary-color);color:var(--primary-color);font-size:16px;line-height:19px;cursor:pointer}
        .additional-inclusion-popup .Proceed{font-family:Inter-Medium;padding:14px 58px;background-color:var(--primary-color);border-radius:72px;border:1px solid var(--primary-color);color:var(--white);font-size:16px;line-height:19px;cursor:pointer}
        .additional-inclusion-popup .add-close-button,.default-hotel-more-info-popup .default-hotel-close-button,.hotel-more-info-popup .hotel-close-button{position:absolute;top:25px;right:25px;cursor:pointer}
        .more-package-info-popup .info-close-button{position:sticky;top:-44px;right:0;cursor:pointer;display:flex;justify-content:flex-end;margin-top:-47px;margin-right:-10px}
        @media only screen and (min-width:981px) and (max-width:1279px){
        .tickets-listing{flex-wrap:wrap;row-gap:20px}
        .inclusion-single,.tickets-listing .single-block{width:47%;margin-right:3%!important}
        .inclusion-single:nth-child(2n),.tickets-listing .single-block:nth-child(2n){margin-right:0!important}
        }
        @media only screen and (max-width:767.98px){
        .tickets-listing{flex-wrap:wrap;row-gap:20px}
        .inclusion-single,.tickets-listing .single-block{width:100%;margin-right:0!important}
        .sub-heading-2-p{font-size:36px;line-height:48px}
        .ticket-pop-up-modal .ticket-contain-module .close-button{top:12px!important;right:12px!important}
        .additional-inclusion-popup .additional-contain .additional-block{padding:16px;max-height:520px;overflow:auto}
        .additional-inclusion-popup .add-close-button{top:10px;right:10px}
        .additional-inclusion-popup .add-cta{flex-direction:column;row-gap:10px}
        }
        .additional-inclusion-popup,.default-hotel-more-info-popup,.hotel-more-info-popup{visibility:hidden;display:flex}
        img.default-hotel-trigger-popup{cursor:pointer}
        .details-form-module>h6{text-transform:uppercase;letter-spacing:2.24px;font-size:14px;line-height:18px;color:var(--primary-color);margin:0 0 8px 0}
        .details-form-module>p,.single-details-module small{color:var(--sub-text-color);font-size:12px;margin:0;line-height:14px}
        .single-details-module{display:flex;flex-wrap:wrap;width:100%;row-gap:24px;padding-bottom:40px;border-bottom:1px solid var(--border-bottom-color);margin:24px 0 40px 0;justify-content:space-between}
        .single-details-module>div{width:48.5%;position:relative}
        .single-details-module>div img{position:absolute;right:26px;bottom:14px}
        .single-details-module>div.full-width{width:100%}
        .single-details-module>div label{display:block;font-family:"PP Neue Montreal Medium";font-weight:500;margin-bottom:12px;font-size:16px;line-height:24px;color:var(--text-dark)}
        .single-details-module>div>input{width:100%;height:46px;border-radius:999px;padding-block:0px;padding-inline:0px;box-sizing:border-box;padding:16px 24px;border:2px solid var(--primary-color)!important;font-family:"PP Neue Montreal Medium";font-weight:500;font-size:14px;line-height:14px;outline:0;background:0 0!important}
        .single-details-module small{font-family:"PP Neue Montreal Medium";font-weight:500;margin-top:12px;display:block}
        .single-details-module>div select{appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:url(/images/Chevron-down.svg);background-repeat:no-repeat;background-position:right 16px center;background-size:16px 16px;cursor:pointer;font-size:14px;line-height:14px;padding:4px 12px;border:2px solid var(--primary-color);border-radius:999px;width:100%;font-family:"PP Neue Montreal Medium";font-weight:500;outline:0;color:var(--text-dark);margin-bottom:0;height:48px}
        .single-details-module>div label.radio-option{display:flex;margin-bottom:0}
        .single-details-module .radio-group{flex-direction:unset;column-gap:16px}
        .single-details-module .radio-option .custom-radio{width:16px;height:16px}
        .single-details-module .radio-option .custom-radio::after{width:8px;height:8px}
        .single-details-module .radio-option input[type=radio]:checked+.custom-radio{border:2px solid var(--primary-color);background:0 0}
        .single-details-module ::placeholder{color:var(--sub-text-color);opacity:1}
        .pika-single .pika-lendar{margin:16px;background-color:var(--white)}
        .pika-single .pika-lendar .pika-title{width:170px;margin:auto}
        .pika-single .pika-lendar .pika-title .pika-label{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:15px;line-height:24px;color:var(--text-dark)}
        .pika-table abbr{border-bottom:none;cursor:help;font-family:"PP Neue Montreal Medium";font-weight:500;font-size:15px;line-height:24px;color:var(--text-dark);text-decoration:none;width:40px;height:40px;display:flex;justify-content:center;align-items:center;cursor:pointer}
        .pika-row .pika-button{border-bottom:none;cursor:help;font-family:"PP Neue Montreal Medium";font-weight:500;font-size:15px;line-height:24px;color:var(--text-dark);text-decoration:none;width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:0 0;box-shadow:unset;cursor:pointer}
        .pika-lendar{width:360px}
        .pika-table td,.pika-table th{width:40px!important;height:40px!important}
        .pika-table td button:hover{background:var(--secondary-color);border-radius:50%}
        .pika-table td.is-selected button{background-color:var(--primary-color);color:var(--white);border-radius:50%}
        .single-details-module .calendar{position:relative}
        .single-details-module .calendar:before{content:"";background:url('/images/checkin.svg') no-repeat;display:inline-block;width:18px;height:18px;position:absolute;top:5px;right:10px}
        .full-width .note-editor.note-frame.panel.panel-default{margin:0!important;border:2px solid var(--primary-color);padding:0;display:flex;flex-direction:column;height:111px;border-radius:12px}
        .full-width .panel-heading.note-toolbar{padding:0;order:2;width:100%;bottom:16px;height:16px;display:flex;justify-content:end;background:0 0;border-color:transparent;border:0!important;position:relative;right:16px}
        .note-editor .note-toolbar>.note-btn-group button{background:0 0!important;border:transparent!important;padding:0;box-shadow:unset!important;color:var(--primary-color)!important;margin-right:8px;outline:0}
        .note-editor.note-airframe .note-statusbar,.note-editor.note-frame .note-statusbar{background-color:hsla(0,0%,50.2%,.11);border-bottom-left-radius:0;border-bottom-right-radius:0;border-top:0 solid rgba(0,0,0,.2);opacity:0}
        .note-editor .note-toolbar>.note-btn-group,.note-popover .popover-content>.note-btn-group{margin-top:0;margin-left:0;margin-right:0}
        .note-editor .btn-default{color:var(--primary-color);width:16px;height:16px;margin-right:8px}
        .note-editor .note-toolbar .note-para .note-dropdown-menu,.note-popover .popover-content .note-para .note-dropdown-menu{min-width:152px;padding:0 0 5px 5px}
        .note-editor .btn-group>.btn-group{margin-right:8px}
        .single-details-module.payment{row-gap:24px;padding-bottom:0;border-bottom:0 solid var(--border-bottom-color);margin:24px 0 50px 0}
        .single-details-module.payment>div{width:100%}
        @media only screen and (max-width:767.98px){
        .single-details-module>div{width:100%}
        .step.completed .circle::before{left:0;width:16px;height:16px;top:2px}
        .pika-lendar{width:280px}
        .card-block{flex-wrap:wrap;row-gap:20px}
        .card-block .card-type{width:100%}
        .default-zoom-overlay .default-zoom-slider .slick-slide>div>div,.hotel-zoom-overlay .hotel-zoom-slider .slick-slide>div>div{height:400px}
        .default-hotel-image-popup-block .slick-slide img,.default-zoom-overlay .default-zoom-slider img,.hotel-zoom-overlay .hotel-zoom-slider img{height:100%;object-fit:cover}
        }
        @media only screen and (max-width:1024px){
        .default-zoom-overlay .default-zoom-slider,.hotel-zoom-overlay .hotel-zoom-slider{height:auto}
        .default-zoom-overlay .default-zoom-slider .slick-next:before,.default-zoom-overlay .default-zoom-slider .slick-prev:before,.hotel-zoom-overlay .hotel-zoom-slider .slick-next:before,.hotel-zoom-overlay .hotel-zoom-slider .slick-prev:before{font-size:30px}
        }
        .single-details-module>div.full-width img{position:unset;right:unset;bottom:unset}
        .note-editor .note-toolbar>.note-btn-group i{border:0;outline:0}
        .note-editor .note-toolbar>.note-btn-group i.emoji-picker:before{font-size:16px;top:2px;position:relative}
        @media only screen and (max-width:980px){
        .emoji-menu{left:unset!important;right:0!important}
        }
        .guest-email>div{width:100%}
        .guest-email{margin-bottom:0;padding-bottom:0;border-bottom:0}
        .guest-email>div input{border-radius:0;color:var(--text-dark)}
        .email-error-field{text-align:center;width:100%;display:block;font-family:"PP Neue Montreal Medium";font-weight:500;color:var(--error-label-color);font-size:16px;line-height:20px}
        .single-details-module.guest{padding-bottom:8px;border-bottom:0 solid var(--border-bottom-color)}
        .guest-module input[type=radio]{display:none}
        .guest-module label{height:32px;display:flex;align-items:center;justify-content:center;font-family:"PP Neue Montreal Medium";font-weight:500;font-size:14px;line-height:18px;cursor:pointer;border-right:1.5px solid var(--primary-color);margin:0;flex-grow:1}
        .guest-module input[type=radio]:checked+label{background:var(--primary-color);color:var(--white)}
        .guest-module label:last-child{border-right:0}
        .bed-configuration-module{display:flex;flex-direction:column}
        .bed-configuration-module input[type=radio]{display:none}
        .bed-configuration{display:flex;align-items:center;justify-content:center;padding:15px 20px;border:1.5px solid var(--primary-color);border-radius:50px;background:var(--white);color:var(--primary-color);cursor:pointer;gap:15px;position:relative;transition:all .3s ease;height:32px}
        .bed-configuration .separator{width:1px;height:24px;background:var(--primary-color)}
        .text-danger .bed-configuration .bed-text{width:50%;text-align:center;font-family:"PP Neue Montreal Medium";font-weight:500;font-size:12px;line-height:14px;color:var(--primary-color)}
        input[type=radio]:checked+.bed-configuration{background:var(--primary-color);color:var(--white)}
        input[type=radio]:checked+.bed-configuration .separator{background:var(--white)}
        input[type=radio]:checked+.bed-configuration .bed-text{color:var(--white)}
        input[type=radio]:checked+.bed-configuration img.default{display:none}
        input[type=radio]:checked+.bed-configuration img.hover{display:block}
        .element-disabled{pointer-events:none}
        .individual-module .out-of-stock{color:#9f0a1a}
        .out-of-stock,.text-danger{color:#9f0a1a}
        .in-stock{color:#4caf50}
        .room-selection .room-listing-module .bed-configuration-h{display:flex;justify-content:space-around;align-items:center;border:1.5px solid #f35b15;border-radius:50px;padding:8px;margin-bottom:8px;cursor:pointer;transition:border .3s;width:209px}
        .room-selection .room-listing-module .bed-configuration-h .radio_txt{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:12px;line-height:14px;color:#f35b15;margin-block-start:0px;margin-block-end:0px;width:54px;text-align:center}
        .bed-configuration-h input[type=radio]:checked+.custom-radio{background-color:#f35b15}
        .bed-configuration-h input[type=radio]:checked+.custom-radio p{color:#fff}
        .bed-configuration-h .midle_bar{display:inline-block;width:2px;height:16px;background-color:#f35b15}
        .selected-bed .midle_bar{background-color:#fff}
        .bed-configuration-h .bed_imgs{width:55px;display:flex;justify-content:center;align-items:center}
        .bed-configuration-h .bed-radio{display:none}
        .selected-bed{background:var(--primary-color);color:var(--white)}
        .room-selection .room-listing-module .selected-bed .radio_txt{color:#fff}
        .customer_profile{text-align:center}
        .additional-inclusions>div.txt-org h5{color:var(--primary-color)}
        .date-align{display:flex;align-items:center;gap:8px;font-weight:600;font-size:16px}
        .date-align img{height:20px}
        .breadcrumbs img{vertical-align:top}
        .hotel-listing .single-hotel .hotel-block .room-type .tour_sales_price,.upgrade_tour_sales_price{color:var(--primary-color)}
        .acc-tp-cond{display:flex;gap:10px;align-items:center;margin-top:20px}
        .acc-tp-cond input[type=checkbox]{appearance:none;-webkit-appearance:none;background-color:#fff;border:2px solid var(--primary-color);width:16px;height:16px;cursor:pointer;position:relative;border-radius:4px;outline-offset:unset;outline:unset;margin:0}
        .acc-tp-cond input[type=checkbox]:checked::after{content:"";position:absolute;left:6px;top:-4px;width:6px;height:13px;border:solid var(--primary-color);border-width:0 2px 2px 0;transform:rotate(45deg)}
        .acc-tp-cond .fnal-txt{font-family:"PP Neue Montreal Medium";font-weight:500;font-size:17px;line-height:15px;color:#000}
        .acc-tp-cond .fnal-txt a{color:var(--primary-color)}
        .acc-tp-cond .contain-v{margin-bottom:0}
        .booking-dates a,.default-hotel-more-info a,.hotel-more-info a,.more-package-info a,.timeline a{color:#f35b15}
        .disable-next{display:inline-block;padding:11.5px 56px;border:1.5px solid #c0c0c8;border-radius:999px;font-family:"PP Neue Montreal Medium";font-weight:500;font-size:14px;line-height:20px;color:var(--white);background-color:var(--include-cta-color)}
        .reselect_config{color:red;padding:10px;margin-top:20px;text-align:left}
        .notice-message{font-weight:700}
        .more-package-info{margin:10px 0 0 1px}
        .per-person-price{display:flex;margin-bottom:5px}
        .hotel-more-info{cursor:pointer}
        .amenity-icon{display:flex;gap:8px;align-items:center}
        .default-info-body h5,.default-info-body p,.default-info-body ul li,.info-body h5,.info-body p,.info-body ul li{font-family:"PP Neue Montreal Medium";font-weight:500;color:#000;font-size:16px;line-height:24px}
        .default-hotel-image-popup-block .slick-dots button,.single-hotel .hotel-image-popup-block .slick-dots button{border:none}
        .default-hotel-image-popup-block .slick-next,.single-hotel .hotel-image-popup-block .slick-next{right:20px;z-index:9}
        .default-hotel-image-popup-block .slick-next:before,.single-hotel .hotel-image-popup-block .slick-next:before{content:"";display:inline-block;width:12px;height:20px;background:url(/icons/Slick-Right-Arrow.svg) no-repeat center center/cover;position:relative;cursor:pointer;opacity:1}
        .default-hotel-image-popup-block .slick-prev,.single-hotel .hotel-image-popup-block .slick-prev{left:20px;z-index:9}
        .default-hotel-image-popup-block .slick-prev:before,.single-hotel .hotel-image-popup-block .slick-prev:before{content:"";display:inline-block;width:12px;height:20px;background:url(/icons/Left-Arrow-Slick.svg) no-repeat center center/cover;position:relative;cursor:pointer;opacity:1}
        .default-hotel-image-popup-block .slick-slide img,.hotel-image-popup-block .slick-slide img{width:100%;height:100%;object-fit:cover}
        .default-zoom-overlay,.hotel-zoom-overlay{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.9);z-index:9999;justify-content:center;align-items:center}
        .zoom__img_icon{cursor:pointer}
        .default-zoom-slider,.hotel-zoom-slider{width:80%;height:100%}
        .default-zoom-slider .slick-list,.hotel-zoom-slider .slick-list{max-height:96vh}
        .default-zoom-slider .slick-list .slick-track,.hotel-zoom-slider .slick-list .slick-track{top:25px}
        .default-zoom-overlay img,.hotel-zoom-overlay img{width:100%;height:auto;object-fit:contain}
        .default-zoom-slider .slick-next:before,.default-zoom-slider .slick-prev:before,.hotel-zoom-slider .slick-next:before,.hotel-zoom-slider .slick-prev:before{font-size:50px}
        .default-zoom-slider .slick-prev,.hotel-zoom-slider .slick-prev{left:-50px}
        .default-close-zoom,.hotel-close-zoom{position:absolute;top:20px;right:10px;font-size:30px;color:var(--primary-color)!important;cursor:pointer;font-size:60px!important;width:50px!important}
        header .container:after,header .container:before{content:unset}
        .default-hotel-more-info{position:relative;top:12px}
        .default-hotel-more-info a:after{content:url('/icons/arrow-left-org.svg');display:block;position:absolute;right:-16px;top:-4px;transform:rotate(180deg)}
        .default-hotel-more-info a{position:relative}
        .default-hotel-image-popup-block .slick-slide .zoom__img_icon img,.hotel-more-info-popup .slick-slide .zoom__img_icon img{width:40px;height:40px}
        .zoom__img_icon{width:50px;height:50px;position:absolute;bottom:4px;right:0}
        .ticket-pop-up-modal .ticket-contain-module .ticket-block .map-close-button img{width:44px;height:44px;position:relative;top:-40px}
        .seating-map-wrapper{width:28px;height:28px;cursor:pointer}
        .seating-map-wrapper-img-block{display:flex;align-items:center;justify-content:center;background-color:var(--secondary-color);border:1px solid #d9d9d9;border-radius:50%;width:28px;height:28px}
        .seating-map-wrapper-img-block img{width:18px}
        .grecaptcha-badge { bottom:400px !important;}
        .select-currency .single:first-child>.custom-currency-display {text-align: center; appearance: none;  -webkit-appearance: none; -moz-appearance: none; background-repeat: no-repeat; background-position: right 12px center; background-size: 16px 16px; cursor: default; font-size: 16px; line-height: 24px; padding: 4px 12px; border: 1px solid var(--primary-color); border-radius: 999px; width: 90px; font-family: "PP Neue Montreal Medium"; font-weight: 500; outline: 0;}
        .cart_sub_title {font-family: "PP Neue Montreal Medium"; font-weight: 400; font-size:13px;line-height:24px; color: #5a5555ff;display: flex;justify-content: space-between; padding-top: 5px; }
        .cart_sub_title_color {color: #000;}
        .font-weight-bold {font-weight: 700;}

        .event-additional-description {border-radius: 24px;border: 2px solid #143e34; padding: 20px; margin-bottom: 20px;padding-top: 15px;background: linear-gradient(to right, #143e34 50%, #143e34 75%);color: white; font-family: "PP Neue Montreal Medium"; font-weight: 500; font-size: 16px;}
        .event-additional-description p { display: flex;line-height: 25px; }
        .event-additional-description ol, .event-additional-description ul { list-style: none; padding-inline-start: 0px; padding:5px 0px 5px 0px;}
        .event-additional-description ol li, .event-additional-description ul li {position: relative;  margin-bottom: 10px; padding-left: 30px; }
        .event-additional-description ol li::before, .event-additional-description ul li::before {content: '✓'; position: absolute; left: 0; top: 0; width: 20px; height: 20px; border: 2px solid #143e34; border-radius: 50%; color: #143e34; text-align: center; line-height: 18px;  font-weight: bold; background-color: white;}

    </style>
</head>
<body>
    {!! setting('booking.body.tracking.code') !!}
    @yield('content')
</body>
<footer>
    <script type="text/javascript">
        flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            minDate: "today",
            onClose: function (selectedDates, dateStr, instance) {
                const firstDate = selectedDates[0];
                const secondDate = selectedDates[1];

                if (firstDate && secondDate) {
                    const options = {day: '2-digit', month: 'short', year: '2-digit'};
                    const formattedFirst = firstDate.toLocaleDateString('en-GB', options).replace(',', '');
                    const formattedSecond = secondDate.toLocaleDateString('en-GB', options).replace(',', '');

                    jQuery('.check-in-check-out .first .text-block p').eq(1).text(formattedFirst);

                    jQuery('.check-in-check-out .second .text-block p').eq(1).text(formattedSecond);
                }


            },
            monthSelectorType: 'static' // Ensures the arrows always show
        });
        jQuery(document).on('click', '.check-in-check-out .second .image-module', function (params) {
            jQuery('.check-in-check-out .image-module .flatpickr-input').click();
        })

        document.addEventListener('DOMContentLoaded', function () {
            const moreInfoLink = document.querySelector('.moreinfo-href');
            const popup = document.querySelector('.more-package-info-popup');
            const closeBtn = document.querySelector('.info-close-button');
            moreInfoLink?.addEventListener('click', function (e) {
                e.preventDefault();
                popup.style.display = 'flex';
            });

            closeBtn?.addEventListener('click', function () {
                popup.style.display = 'none';
            });

            // Optional: click outside the popup to close
            popup?.addEventListener('click', function (e) {
                if (e.target === popup) {
                    popup.style.display = 'none';
                }
            });
        });
    </script>
    @livewireScripts
</footer>
</html>
