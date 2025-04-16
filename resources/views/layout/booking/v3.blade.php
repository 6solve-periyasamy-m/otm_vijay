<!DOCTYPE html>
@php
/**
 * @var \App\Models\System\Brand $brand
 * @var int $stage
 */
$brand = $brand ?? \App\Models\System\Brand::getSystemBrand();
$stage = $stage ?? 2;
@endphp
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="{{ asset('css\booking\booking_new\js\script.js') }}"></script>

    <style>
        :root {
            --primary-color: #F35B15;
            --white: #FFFFFF;
            --text-light-dark: #383232;
            --text-dark: #000000;
            --sub-text-color: #808080;
            --border-bottom-color:#D1D5DB;
            --include-cta-color:#C0C0C8;
            --include-text:#E2E2E2;
            --error-label-color: #FF0000;
            --error-message-color: #9F0A1A;
            --footer-color: #000000;
            --secondary-color: #FEEFE8;
        }
        body {
            background-color: transparent;
            margin:0;
        }
        .container {
            max-width: 1410px;
            width:90%;
            position:relative;
            margin: auto;
        }
        p, h6, h5, h4{
            font-family: "PP Neue Montreal Medium";
            font-weight:500;
        }
        @font-face {
            font-family: "PP Editorial New";
            src: url("{{ asset('fonts/PPEditorialNew-Ultralight.ttf') }}");
        }
        @font-face {
            font-family: "PP Neue Montreal Medium";
            src: url("{{ asset('fonts/PPNeueMontreal-Medium.ttf') }}");
        }
        @font-face {
            font-family: "PP Neue Montreal Bold";
            src: url("{{ asset('fonts/ppneuemontreal-bold.otf') }}");
        }
        @font-face {
            font-family: "PlayfairDisplay-Regular";
            src: url("{{ asset('fonts/PlayfairDisplay-Regular.ttf') }}");
        }
        @font-face {
            font-family: "Inter-Medium";
            src: url("{{ asset('fonts/Inter-Medium.ttf') }}");
        }
        header {
            background-color:var(--primary-color);
        }
        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .column {display:flex;}
        header .column.right {
            display: flex;
            align-items: center;
            column-gap: 16px;
        }
        header .column.right p {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            color:var(--white);
            text-transform: uppercase;
            letter-spacing: 2.24px;
            margin:0;
        }
        header .column.right a {
            background-color: var(--white);
            padding: 12px 16px;
            border-radius: 40px;
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            color:var(--primary-color);
            display: flex;
            column-gap: 8px;
            align-items: center;
            text-decoration: unset;
            font-size: 14px;
            line-height: 18px;
            letter-spacing: 2.24px;
        }
        header .container {padding:24px 0px;}
        body main {padding-bottom: 100px;}
        footer {
            width: 100%;
            background-color: rgba(59, 59, 59, 0.97);
            position: fixed;
            bottom: 0;
            z-index: 9;
        }
        footer .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding:24px 0px;
        }
        .Go-back {
            display: inline-block;
            padding: 11.5px 56px;
            border: 1.5px solid var(--white);
            border-radius: 999px;
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            color: var(--white);
            cursor: pointer;
        }
        .Go-next {
            display: inline-block;
            padding: 11.5px 56px;
            border: 1.5px solid #FF8F1C;
            border-radius: 999px;
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            color: var(--white);
            cursor: pointer;
            background: #FF8F1C;
        }
        footer .value {
            display: flex;
            column-gap: 16px;
            align-items: center;
        }
        footer .value  h6 {
            font-family: "PP Neue Montreal Bold";
            font-weight: 700;
            color: var(--white);
            font-size: 18px;
            line-height: 24px;
            margin: 0;
        }
        footer .value  p {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 18px;
            line-height: 24px;
            color: var(--white);
            margin: 0;
        }
        footer .value span {
            display: inline-block;
            width: 1px;
            height: 40px;
            background: var(--white);
        }
        @media only screen and (min-width:768px) and (max-width:1024px) {
            .Go-next, .Go-back {
                padding: 11.5px 38px;
            }
        }
        header .column img {
            width:100%;
            height:100%;
            object-fit: contain;
        }
        .view-details {display:none;}
        @media only screen and (max-width:767px) {
            .container {
                width: 94%;
            }
            header .column:first-child {max-width: 139px;}
            header .column.right p {display:none;}
            .view-details {display:block;}
            footer .container {
                flex-wrap:wrap;
                row-gap: 18px;
            }
            footer .value p {font-size:11px;line-height: 18px;}
            footer .value {column-gap:6px;}
            .Go-back {order:3;}
            footer .value {order:1;}
            .view-details {
                order: 2;
                font-size: 12px;
                color: #FF8F1C;
                font-family: "PP Neue Montreal Medium";
                text-decoration: underline;
                line-height: 12px;
            }
            .Go-next {order:4;}
        }
        .secure-booking {
            padding: 24px 0px;
            background: #F9F4EE;
        }

        h1 {
            font-family: "PlayfairDisplay-Regular";
            text-align: center;
            font-size: 40px;
            margin: 0;
            line-height: 40px;
            color:var(--primary-color);
            font-weight: 400;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .breadcrumbs {
            display: flex;
            align-items: center;
            column-gap: 8px;
        }
        .breadcrumbs span:first-child {height:16px;}
        .breadcrumbs span {
            font-family: "Inter-Medium";
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 2.24px;
        }
        :root {
            --circle-size: 32px;
            --line-gap: 8px;
        }
        .step .circle {
            width: var(--circle-size);
            height: var(--circle-size);
        }
        .timeline {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0px auto;
            max-width: 1410px;
            width: 90%;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
            text-align: center;
            color:var(--text-light-dark);
        }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 19px;
            height: 2px;
            background:var(--text-light-dark);
            z-index: 0;
            /* width: 100%;
            right: -50%; */
            left: calc(50% + calc(var(--circle-size) / 2) + var(--line-gap));
            width: calc(100% - var(--circle-size) - calc(var(--line-gap) * 2));
        }
        .step.completed:not(:last-child)::after {
            background:var(--primary-color);
        }
        .step .circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid var(--text-light-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            margin-bottom: 10px;
            background: #F9F4EE;
        }
        .step.completed .circle {
            background:var(--primary-color) ;
            border-color: var(--primary-color);
            color: var(--white);
        }
        .step.active .circle {
            border-color:var(--primary-color) ;
            color: var(--primary-color);
            font-weight: bold;
        }
        .step.completed .circle::before {
            content: '';
            width: 24px;
            height: 24px;
            background-image: url('/icons/Correct-vector.svg');
            background-repeat: no-repeat;
            position: relative;
            top: 6px;
            left: 4px;
        }
        .step.active .circle::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary-color);
            display: block;
        }
        .step .label {
            text-align: center;
            font-family: "PP Neue Montreal";
            font-size: 13px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }
        .step.completed .label,
        .step.active .label {
            color: var(--primary-color);
        }
        .step.completed span,
        .step.active span {
            display: none !important;
        }
        .step:not(.completed):not(.active) span {
            display: inline !important;
        }
        @media only screen and (max-width: 767px) {
            .step:not(.active) .label {
                display: none !important;
            }
            .step:not(:last-child)::after {
                left: 50% !important;
                width: 100% !important;
                top: 13px;
            }
            .step {
                min-height: 80px;
            }
            .step:not(.active) span {
                display: inline !important;
            }
            .step.completed span,
            .step.active span {
                display: none !important;
            }
            .step:not(.completed):not(.active) span {
                display: inline !important;
            }
            .step .circle {
                width: 22px;
                height: 22px;
                font-size: 11px !important;
            }
            .step.active .circle::before {
                width: 8px;
                height: 8px;
            }
            .step.completed .circle {
                position: relative;
            }
            .timeline .step.active .label {
                position: absolute;
                bottom: 20px;
            }
        }
        .timeline .step .circle span,.timeline .step .label {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 13px;
            line-height: 16px;
            color:var(--text-light-dark);
        }
        .timeline .step.completed .circle span,.timeline .step.completed .label,.timeline .step.active .circle span,.timeline .step.active .label {
            font-family: "Inter-Medium";
            font-weight: 500;
            color:var(--primary-color);
        }
        @media only screen and (min-width:768px) and (max-width:980px) {
            .timeline .step > .label {
                height: 43px;
            }
        }
        /* timeline bar css end */
        /* Guest */
        .package-container .container {display:flex;}
        .package-container .container > .left {width: 57.5%;margin-right:2%;}
        .package-container .container > .right {width: 40.5%;}
        .package-container {padding:60px 0px;}
        .sub-heading-2 {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 36px;
            line-height: 42px;
            color: var(--primary-color);
            letter-spacing: 2.24px;
            margin: 0 0 16px 0px;
        }
        .sub-heading-3 {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 32px;
            line-height: 32px;
            color: var(--text-dark);
            margin: 0 0 16px 0px;
        }
        .location-dollar-value {
            display: flex;
            column-gap: 8px;
            align-items: center;
            padding-bottom:40px;
            border-bottom:1px solid var(--border-bottom-color);
        }
        .location-dollar-value p {
            font-size: 16px;
            line-height: 24px;
            padding-left: 24px;
            position: relative;
            margin: 0;
        }
        .location-dollar-value p:before {
            content: "";
            background: url('/icons/Location.svg') no-repeat;
            background-repeat: no-repeat;
            display: inline-block;
            width: 16px;
            height: 16px;
            position: absolute;
            top: 4px;
            left: 0px;
        }
        .hotel-listing .single-hotel .hotel-block .room-type > p.breakfast-note {
            color: var(--sub-text-color);
        }
        .location-dollar-value p.dollar:before {
            background: url('/icons/Dollar.svg') no-repeat;
        }
        .location-dollar-value > span {
            display: inline-block;
            width: 2px;
            height: 24px;
            background: var(--border-bottom-color);
        }
        .no-of-travellers {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin:40px 0px;
        }
        .quantity {
            width: 153px;
            display: flex;
            column-gap: 8px;
            align-items: center;
            height: 52px;
            background-color: #F9F4EE;
            border-radius: 99px;
            justify-content: center;
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 14px;
            color: var(--primary-color);
        }
        .quantity span.value {
            width: 32px;
            text-align: center;
            color:var(--text-dark);
        }
        .quantity .minus, .quantity .plus {cursor:pointer;}
        .sub-heading-4 {
            font-size: 24px;
            line-height: 32px;
            margin: 0;
            color:var(--text-dark);
        }
        .contact-block {
            background: #F9F4EE;
            padding: 24px;
            border-radius: 16px;
        }
        .contact-block p {
            font-size: 16px;
            line-height: 19px;
            color:var(--text-dark);
            position:relative;
        }
        .contact-block p a {
            color: var(--primary-color);
            text-decoration: none;
        }
        .contact-block p:before {
            content: "";
            position: absolute;
            display: inline-block;
        }
        .contact-block p.description:before {
            background: url('/icons/Round-circle.svg') no-repeat;
            width: 18px;
            height: 18px;
            top: 0px;
            left: 0px;
        }
        .contact-block p.phone:before {
            background: url('/icons/Phone.svg') no-repeat;
            width: 16px;
            height: 16px;
            top: 0px;
            left: 27px;
        }
        .contact-block p.email:before {
            background: url('/icons/Email.svg') no-repeat;
            width: 16px;
            height: 16px;
            top: 0px;
            left: 27px;
        }
        .contact-block p.phone, .contact-block p.email  {
            padding-left:53px;
        }
        .contact-block p.description{padding-left:26px;}
        .contact-block p{padding-bottom:16px;margin:0;}
        .contact-block p.phone {padding-bottom:8px;}
        .contact-block p.email {padding-bottom:0;}
        .package-container.first .container > .left > div {max-width:590px}
        .breadcrumbs {width: 69px;cursor:pointer;}
        .package-details {
            background-color: var(--white);
            border-radius: 40px;
            box-shadow: 0px 0px 34px 0px #00000026;
            padding: 32px;
        }
        .package-details h4 {
            margin: 0px 0px 32px 0px;
        }
        .package-details .contain .image-block {
            height: 293px;
            width: 100%;
            margin-bottom:32px;
        }
        .package-details .contain .image-block img {
            width:100%;
            height:100%;
            object-fit: cover;
            border-radius: 16px;
        }
        .base-package {
            background-color: #F9F4EE;
            padding: 16px;
            border-radius: 16px;
            display: block;
            margin-bottom:32px;
        }
        .base-package h6 {
            font-size: 14px;
            line-height: 18px;
            color: var(--primary-color);
            letter-spacing: 2.24px;
            margin: 0px 0px 24px 0px;
        }
        .base-package h2 {
            font-family: "PlayfairDisplay-Regular";
            font-size: 32px;
            line-height: 44px;
            font-weight: 400;
            color:var(--primary-color)
        }
        .base-package ul {
            padding-left: 30px;
            margin: 0;
        }
        .base-package ul li {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 16px;
            line-height: 32px;
            color:var(--text-dark);
        }
        .additional-inclusions > div {
            border-top:1px solid rgba(243, 91, 21, 0.2);
            border-bottom:1px solid rgba(243, 91, 21, 0.2);
            padding:32px 0px;
        }
        .additional-inclusions > div .single{
            display:flex;
            justify-content: space-between;
        }
        .additional-inclusions > div .single p {
            font-size:16px;
            line-height:24px;
            color: var(--sub-text-color);
            margin:0;
        }
        .additional-inclusions h6 {
            margin: 0px 0px 32px 0px;
            font-size: 14px;
            letter-spacing: 2.24px;
            color: var(--primary-color);
        }
        .additional-inclusions > div h5 {
            font-family: "PP Neue Montreal Bold";
            font-weight:700;
            font-size:16px;
            line-height:24px;
            color:var(--text-dark);
            margin:0 0 8px 0px;
            letter-spacing: 0;
        }
        .select-currency .single:first-child > p {
            font-size: 16px;
            line-height: 24px;
            color: var(--text-dark);
        }
        .select-currency .single:first-child > select {
            font-size: 16px;
            line-height: 24px;
            padding: 4px 12px;
            border: 1px solid var(--primary-color);
            border-radius: 999px;
            width: 82px;
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            outline: 0;
        }
        .select-currency .single:first-child > select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url(/icons/Chevron-down.svg);
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px 16px;
            cursor: pointer;
        }
        .select-currency .single:first-child {
            margin-bottom:12px;
        }
        .select-currency .single:nth-child(2) {
            margin-bottom:8px;
        }
        .select-currency .single:nth-child(2) p {color: var(--text-dark);}
        .additional-inclusions > div .single p > span {display:block;}
        .total .single:nth-child(2) {margin-top:32px;}
        .total .single:first-child p {
            font-family: "PP Neue Montreal Bold";
            font-weight: 700;
            color: var(--text-dark);
            font-size:18px;
            line-height:24px;
        }
        .option-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 50px;
            column-gap:8px;
        }
        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .radio-option {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        .radio-option input[type="radio"] {
            display: none;
        }
        .custom-radio {
            width: 24px;
            height: 24px;
            border: 2px solid var(--primary-color);
            border-radius: 50%;
            position: relative;
            flex-shrink: 0;
        }
        .custom-radio::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 18px;
            height: 18px;
            background-color: var(--primary-color);
            border-radius: 50%;
            opacity: 0;
            transition: 0.2s;
        }
        .radio-option input[type="radio"]:checked + .custom-radio::after {
            opacity: 1;
        }
        .option-title {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 16px;
            line-height: 24px;
        }
        .option-subtext {
            font-size: 14px;
            color: #000;
            line-height: 1.5;
            margin-left: 38px;
            margin-top: 8px;s
        }
        .price, .option-subtext {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 16px;
            line-height: 24px;
            max-width:346px;
        }
        .option-subtext {
            font-size: 14px;
            line-height: 18px;
        }
        .payment-method h6 {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            color: var(--primary-color);
            margin: 32px 0px 24px 0px;
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 2.24px;
        }
        .card-block {
            display: flex;
            justify-content: space-between;
            column-gap: 14px;
        }
        .card-block .card-type {
            border: 2px solid var(--primary-color);
            width: 48%;
            border-radius: 16px;
            padding: 16px;
        }
        .card-block .card-type p {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            color:var(--text-dark);
            font-size:16px;
            line-height:20px;
            margin:0;
        }
        .card-block .card-type.active {
            background-color:#FEEFE8;
        }
        .payable-now .single {
            margin-top: 32px;
            display: flex;
            justify-content: space-between;
        }
        .payable-now p{
            font-size: 16px;
            line-height: 24px;
            margin: 0;
            color:var(--text-dark);
        }
        .payable-now > p {
            margin-top:8px;
            color: var(--sub-text-color);
        }
        .payment-method .email-quote h6 {
            margin: 32px 0px 24px 0px;
            text-transform: uppercase;
            text-align: center;
            text-decoration: underline;
            font-size: 16px;
            line-height: 20px;
            cursor: pointer;
        }
        .email-quote label{
            font-size: 16px;
            line-height: 24px;
            margin: 0;
            color: var(--text-dark);
            width: 100%;
            display: block;
            margin-bottom: 12px;
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
        }
        .email-quote input {
            width: 100%;
            height: 46px;
            border: 2px solid #F35B15;
            outline: 0;
            font-size: 14px;
            line-height: 14px;
            padding-block: 0px;
            padding-inline: 0px;
        }
        .next-button {
            margin-top: 24px;
            width: 100%;
            height: 67px;
            background: var(--primary-color);
            border: 0px;
            border-radius: 99px;
            cursor: pointer;
        }
        .next-button > span {
            display: flex;
            align-items: center;
            justify-content: center;
            column-gap: 8px;
        }
        .next-button > span > span {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 16px;
            line-height: 20px;
            letter-spacing: 2.24px;
            color: var(--white);
        }
        @media only screen and (min-width:768px) and (max-width:980px) {
            .package-container .container {
                flex-wrap: wrap;
                row-gap: 60px;
            }
            .package-container .container > .left, .package-container .container > .right {width:100%;margin-right:0;}
        }
        .hide-package-detail {display:none;}
        @media only screen and (max-width:767px) {
            .package-container .container {flex-wrap:wrap;row-gap: 60px;}
            .package-container .container > .left, .package-container .container > .right {
                width:100%;
                margin-right:0%;
            }
            h1 {
                margin-top: 12px;
                font-size: 32px;
                line-height: 40px;
            }
            .sub-heading-2 {
                font-size: 24px;
                line-height: 32px;
            }
            .sub-heading-3 {
                font-size: 20px;
                line-height: 32px;
            }
            .package-container .container > .left .location-dollar-value {
                display:block;
            }
            .location-dollar-value > span {display:none;}
            .location-dollar-value p.location {margin-bottom:10px;}
            .sub-heading-4 {
                font-size: 20px;
                line-height: 28px;
            }
            .right .sub-heading-4  {
                font-size: 24px;
                line-height: 32px;
            }
            .package-details {
                padding: 24px;
                border-radius: 16px;
            }
            .package-details .contain .image-block {height:209px;margin-bottom: 24px;}
            .base-package h2 {
                font-size: 24px;
                line-height: 32px;
            }
            .package-details .top-module {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom:32px;
            }
            .package-details h4 {margin-bottom:0px;}
            .hide-package-detail {
                display:block;
                font-family: "PP Neue Montreal Medium";
                font-weight: 500;
                color: var(--primary-color);
                font-size: 12px;
                line-height: 16px;
                text-decoration: underline;
            }
            .package-details {
                border-radius: 16px;
                max-height: 475px;
                min-height: 475px;
                overflow: auto;
                padding-bottom: 150px;
            }
            .package-container .container .column.right {
                position: fixed;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                top: 0;
                left: 0;
                padding-top: 120px;
                z-index: 99;
            }
            .package-container .container .column.right {display:none;}
        }
        .sub-heading-2-p {
            font-family: "PlayfairDisplay-Regular";
            font-weight: 400;
            font-size: 40px;
            line-height: 54px;
            color: var(--text-light-dark);
            margin: 40px 0px 24px 0px;
        }
        .accommodation-detail > p {
            font-size: 20px;
            line-height: 28px;
            color: var(--text-light-dark);
            margin: 0px 0px 40px 0px;
        }
        .sub-heading-6 {
            font-family: "PP Neue Montreal Medium";
            font-size: 14px;
            line-height: 18px;
            color: var(--primary-color);
            letter-spacing: 2.24px;
            margin: 0px 0px 24px 0px;
        }
        .accommodation-detail .locate {
            display: flex;
            border-radius: 24px;
            border: 2px solid var(--primary-color);
            max-width: 384px;
        }
        .accommodation-detail .image {
            width: 125px;
            height: 118px;
            display: flex;
        }
        .accommodation-detail .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-top-left-radius: 24px;
            border-bottom-left-radius: 24px;
        }
        .accommodation-detail .locate h6 {
            margin: 0 0 6px 0px;
            font-family: "PP Neue Montreal Bold";
            font-weight: 700;
            font-size: 14px;
            line-height: 18px;
            color: var(--primary-color);
        }
        .accommodation-detail .text-block {padding: 24px;}
        .accommodation-detail .locate p {
            margin: 0;
            font-size: 12px;
            line-height: 22px;
            color: var(--primary-color);
        }
        .check-in-check-out {
            border: 1.5px solid var(--primary-color);
            display: flex;
            max-width: 369px;
            border-radius: 999px;
            padding: 8px 24px;
            justify-content: space-between;
            align-items: center;
            color: var(--primary-color);
            font-size: 20px;
        }
        .check-in-check-out .first, .check-in-check-out .second {
            display: flex;
            align-items: center;
            width: 134px;
            justify-content: space-between;
        }
        .check-in-check-out .second {
            flex-direction: row-reverse;
        }
        .check-in-check-out .first .image-module, .check-in-check-out .second .image-module {
            position: relative;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .check-in-check-out .image-module input {
            width: 20px;
            height: 20px;
            position: absolute;
            left: 0;
            opacity: 0;
        }
        .check-in-check-out .text-block p:first-child {
            font-size: 12px;
            line-height: 18px;
            color: var(--primary-color);
            margin: 0;
        }
        .check-in-check-out .text-block p:last-child {
            font-size: 16px;
            line-height: 20px;
            margin: 0;
            color: var(--sub-text-color);
        }
        .booking-dates {
            border-bottom: 1px solid var(--border-bottom-color);
            border-top: 1px solid var(--border-bottom-color);
            padding: 40px 0px;
            margin: 40px 0px;
        }
        .booking-dates p {
            margin: 0px 0px 24px 0px;
            font-size: 16px;
            line-height: 24px;
            color: var(--text-light-dark);
        }
        .booking-dates > p:last-child {
            margin: 0px 0px 12px 0px;
            color:var(--text-dark);
        }
        .booking-dates p.mod {
            font-family: "Inter-Medium";
            font-weight:500;
            margin:0 0 12px 0;
            color:var(--text-dark);
        }
        .room-configuration .no-of-travellers {
            max-width: 420px;
            margin-top: 24px;
        }
        .room-configuration .no-of-travellers p {margin: 0;}
        .room-selection {
            border-bottom: 1px solid var(--border-bottom-color);
            border-top: 1px solid var(--border-bottom-color);
            padding: 40px 0px;
            margin: 40px 0px;
        }
        .room-selection > p {
            margin: 0px 0px 16px 0px;
            font-size: 16px;
            line-height: 24px;
            color: var(--text-light-dark);
        }
        .room-selection .showcase {
            display: flex;
            column-gap: 24px;
            margin-bottom:40px;
        }
        .room-selection .showcase .single {
            display: flex;
            align-items: center;
            column-gap: 16px;
        }
        .room-selection .showcase .single > div:first-child {display:flex;column-gap:4px;}
        .showcase p {
            font-size: 16px;
            margin:0;
        }
        .room-listing-module {
            display: flex;
            width:100%;
        }
        .room-listing-module .single-room {
            width: 32%;
            margin-right:2%;
        }
        .room-listing-module .single-room:nth-child(3n) {
            margin-right:0%;
        }
        .room-listing-module .single-room {
            border: 1px solid var(--primary-color);
            padding: 24px;
            border-radius: 24px;
            box-sizing: border-box;
        }
        .room-listing-module .single-room h6 {
            font-family: "PP Neue Montreal Bold";
            font-weight: 700;
            font-size: 14px;
            line-height: 18px;
            color: var(--primary-color);
            margin: 0 0 4px 0;
        }
        .room-listing-module .single-room p {
            margin: 0 0 4px 0;
            font-size: 12px;
            line-height: 22px;
            color: var(--text-light-dark);
        }
        .room-listing-module .single-room ul {
            margin: 4px 0px;
            padding-left: 20px;
        }
        .room-listing-module .single-room ul li {
            color: var(--sub-text-color);
            font-size: 12px;
            line-height: 22px;
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
        }
        .guest-module {
            width: 100%;
            display: flex;
            justify-content: space-between;
            border: 1px solid var(--primary-color);
            height: 32px;
            align-items: center;
            border-radius: 999px;
            margin-bottom:4px;
            overflow:hidden;
        }
        .guest-module > div {
            width: 33.3%;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 14px;
            line-height: 18px;
            cursor: pointer;
        }
        .guest-module > div.active {
            background: var(--primary-color);
            color: #fff;
        }
        .bed-configuration {
            display: flex;
            border: 1.5px solid var(--primary-color);
            border-radius: 999px;
            height: 32px;
            margin-bottom: 8px;
            align-items: center;
            cursor:pointer;
        }
        .bed-configuration .bed-icon {
            width: 50%;
            text-align: center;
            border-right: 1px solid var(--primary-color);
            display: flex;
            justify-content: center;
        }
        .bed-configuration .twin {
            width: 50%;
            text-align: center;
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 12px;
            line-height: 14px;
            color: var(--primary-color);
        }
        .bed-configuration .bed-icon img.hover, .bed-configuration.active .bed-icon img.default {display:none;}
        .bed-configuration.active {background: var(--primary-color);}
        .bed-configuration.active .bed-icon {border-right: 1px solid var(--white);}
        .bed-configuration.active .twin {color:var(--white)}
        .bed-configuration.active .bed-icon img.hover {display: block;}
        .include-button {
            margin-top: 16px;
            width: 100%;
            height: 36px;
            background-color: var(--include-cta-color);
            border: 0;
            border-radius: 999px;
            cursor: pointer;
            color:var(--include-text);
            letter-spacing: 2.24px;
            text-transform: uppercase;
        }
        @media only screen and (max-width:359px) {
            footer .container > div {
                justify-content: center;
                text-align: center;
            }
            footer .container  {
                flex-direction: column;
            }
        }
        .hotel > p {
            font-size: 16px;
            margin: 24px 0px;
            line-height: 24px;
            color: var(--text-light-dark);
        }
        .hotel-listing {width:100%;display: flex;}
        .hotel-listing .single-hotel {
            width: 32%;
            margin-right: 2%;
            border: 2px solid #E2E2E2;
            box-shadow: 0px 0px 12px 0px #0000001A;
            border-radius: 24px;
        }
        .hotel-listing .single-hotel:nth-child(3n) {margin-right:0;}
        .hotel-listing .single-hotel .hotel-image-block > div, .hotel-listing .single-hotel .hotel-image-block > div > div > div,
        .hotel-listing .single-hotel .hotel-image-block > div > div > div > div, .hotel-listing .single-hotel .hotel-image-block > div > div > div > div > div {
            width: 100%;
            height: 200px;
        }
        .hotel-listing .single-hotel .hotel-image-block > div img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-top-left-radius: 22px;
            border-top-right-radius: 22px;
        }
        .hotel-listing .single-hotel .hotel-block {padding: 24px;}
        .hotel-listing .single-hotel .hotel-block > h6 {
            font-family: "PP Neue Montreal Bold";
            font-weight: 700;
            font-size: 14px;
            line-height: 17px;
            color: var(--primary-color);
            margin: 0px 0px 6px 0px;
        }
        .hotel-listing .single-hotel .hotel-block > p {
            font-size: 12px;
            line-height: 22px;
            color: var(--primary-color);
            margin: 0 0 4px 0;
        }
        .hotel-listing .single-hotel .hotel-block .room-type {margin-top:6px;}
        .hotel-listing .single-hotel .hotel-block .room-type > p {
            font-size: 12px;
            line-height: 22px;
            color: var(---text-light-dark);
            margin: 0 0 6px 0;
        }
        .hotel-listing .single-hotel .hotel-block .room-type select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url(/icons/Chevron-down.svg);
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px 16px;
            cursor: pointer;
            font-size: 12px;
            line-height: 14px;
            padding: 4px 12px;
            border: 1px solid var(--primary-color);
            border-radius: 999px;
            width: 100%;
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            outline: 0;
            color: var(--primary-color);
            margin-bottom:11px;
        }
        .hotel-listing .single-hotel .hotel-block .room-type span {
            font-size: 8px;
            line-height: 10px;
            color: var(--error-label-color);
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            margin-top: 16px;
            text-align: center;
            width: 100%;
            display: block;
        }
        .include-button.active {
            background-color: var(--primary-color);
        }
        .hotel-listing .single-hotel:hover {
            border:2px solid var(--primary-color)
        }
        .single-hotel .slick-dots {
            display: flex;
            justify-content: center;
            column-gap:3px;
            bottom: 16px;
        }
        .single-hotel .slick-dots li {
            padding: 0;
            width: 8px;
            height: 8px;
            margin: 0;
        }
        .single-hotel .slick-dots button {
            padding: 0;
            width: 8px;
            height: 8px;
            margin: 0;
            border: 1px solid var(--white);
            border-radius: 50%;
        }
        .hotel-listing .single-hotel .hotel-image-block .slick-dots li button:before {
            opacity: 1 !important;
            color: rgba(255, 255, 255, 0.5);
            width: 8px;
            height: 8px;
            font-size: 8px;
            border-radius: 50%;
            line-height: 10px;
        }
        .hotel-listing .single-hotel .hotel-image-block .slick-dots li.slick-active button {border: 1px solid var(--primary-color);}
        .hotel-listing .single-hotel .hotel-image-block .slick-dots li.slick-active button:before {
            color: var(--primary-color);
            left: -1px;
        }
        .hotel-listing .single-hotel .hotel-image-block {
            margin-bottom:0!important;
        }
        .hotel-listing .single-hotel .hotel-image-block  .slick-prev {left: 20px;z-index:9;}
        .hotel-listing .single-hotel .hotel-image-block  .slick-next{right:20px;z-index:9;}
        .hotel-listing .single-hotel .hotel-image-block  .slick-prev:before {
            content: "";
            display: inline-block;
            width: 12px;
            height: 20px;
            background: url(/icons/Left-Arrow-Slick.svg) no-repeat center center / cover;
            position: relative;
            cursor: pointer;
            opacity: 1;
        }
        .hotel-listing .single-hotel .hotel-image-block  .slick-next:before {
            content: "";
            display: inline-block;
            width: 12px;
            height: 20px;
            background: url(/icons/Slick-Right-Arrow.svg) no-repeat center center / cover;
            position: relative;
            cursor: pointer;
            opacity: 1;
        }
        .accomodation-travel-date-error {
            display: flex;
            margin-top: 32px;
            column-gap: 4px;
        }
        .accomodation-travel-date-error span {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 13px;
            line-height: 17px;
            color:var(--error-message-color);
        }
        @media only screen and (min-width:981px) and (max-width:1279px) {
            .room-listing-module, .hotel-listing {flex-wrap:wrap;row-gap:20px;}
            .room-listing-module .single-room, .hotel-listing  .single-hotel {width:47%;margin-right:3%!important;}
            .room-listing-module .single-room:nth-child(2n), .hotel-listing  .single-hotel:nth-child(2n) {margin-right:0%!important;}
        }
        @media only screen and (max-width:767px) {
            .room-listing-module, .hotel-listing {flex-wrap:wrap;row-gap:20px;}
            .room-listing-module .single-room, .hotel-listing  .single-hotel {width:100%;margin-right:0%!important;}
            .room-selection .showcase {
                row-gap: 20px;
                flex-wrap: wrap;
            }
            .sub-heading-2-p {
                font-size: 36px;
                line-height: 48px;
            }
        }
        .display-none{
            display: none !important;
        }
        .flatpickr-calendar {
            min-width: 368px;
            padding: 22px 15px;
            z-index: 9 !important;
        }
        .flatpickr-calendar .flatpickr-months {
            width: 170px;
            margin: auto;
            position: relative;
            height: 25px;
            margin-bottom: 16px;
            align-items: center;
            padding-top: 15px;
        }
        .flatpickr-calendar .flatpickr-months  > span {
            top: unset;
            padding: 0;
        }
        .flatpickr-current-month {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            font-size: 14px;
            left: 0;
            text-align: center;
            width: 100%;
            padding: 0;
            color:var(--text-dark);
        }
        .flatpickr-current-month span.cur-month {
            margin:0;
            font-weight:unset;
        }
        .flatpickr-current-month .numInputWrapper .arrowUp, .flatpickr-current-month .numInputWrapper .arrowDown {display:none;}
        .flatpickr-calendar .numInputWrapper {width: 4.5ch;}
        .flatpickr-calendar span.flatpickr-weekday{
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            color:var(--text-dark);
            font-size: 12px;
            line-height: 16px;
        }
        .flatpickr-calendar .flatpickr-innerContainer {justify-content: center;}
        .flatpickr-calendar .flatpickr-innerContainer .flatpickr-rContainer .flatpickr-days .dayContainer span {
            font-family: "PP Neue Montreal Medium";
            font-weight: 500;
            color: var(--text-dark);
            font-size: 12px;
            line-height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .flatpickr-calendar .flatpickr-innerContainer .flatpickr-rContainer .flatpickr-days .dayContainer span.flatpickr-disabled {
            color: var(--include-cta-color);
        }
        .rangeMode .flatpickr-day {
            margin-top: 0px;
            margin-bottom: 8px;
        }
        .flatpickr-day.selected.startRange, .flatpickr-day.startRange.startRange, .flatpickr-day.endRange.startRange, .flatpickr-day.selected.endRange, .flatpickr-day.startRange.endRange, .flatpickr-day.endRange.endRange {
            background: var(--primary-color);
            z-index: 9;
            color: var(--white)!important;
            border:0;
            border-radius: 50% !important;
        }
        .flatpickr-day.inRange {
            -webkit-box-shadow: -18px 0 0 var(--secondary-color), 18px 0 0 var(--secondary-color);
            box-shadow: -18px 0 0 var(--secondary-color), 18px 0 0 var(--secondary-color);
            color:var(--text-dark)!important;
        }
        .flatpickr-day.inRange,.flatpickr-day.inRange, .flatpickr-day.prevMonthDay.inRange, .flatpickr-day.nextMonthDay.inRange, .flatpickr-day.today.inRange, .flatpickr-day.prevMonthDay.today.inRange, .flatpickr-day.nextMonthDay.today.inRange, .flatpickr-day:hover, .flatpickr-day.prevMonthDay:hover, .flatpickr-day.nextMonthDay:hover, .flatpickr-day:focus, .flatpickr-day.prevMonthDay:focus, .flatpickr-day.nextMonthDay:focus,.flatpickr-day.selected.startRange + .endRange:not(:nth-child(7n+1)), .flatpickr-day.startRange.startRange + .endRange:not(:nth-child(7n+1)), .flatpickr-day.endRange.startRange + .endRange:not(:nth-child(7n+1)) {
            background: var(--secondary-color);
            border: 0;
        }
        .flatpickr-calendar .flatpickr-innerContainer .flatpickr-rContainer .flatpickr-days .dayContainer span.today {
            border:0;
        }
        .flatpickr-calendar .flatpickr-innerContainer .flatpickr-rContainer .flatpickr-days .dayContainer span.today:hover {background: var(--secondary-color);}
        .flatpickr-day.selected.startRange + .endRange:not(:nth-child(7n+1)), .flatpickr-day.startRange.startRange + .endRange:not(:nth-child(7n+1)), .flatpickr-day.endRange.startRange + .endRange:not(:nth-child(7n+1)){
            -webkit-box-shadow: -18px 0 0 var(--secondary-color), 0px 0 0 var(--secondary-color);
            box-shadow: -18px 0 0 var(--secondary-color), 0px 0 0 var(--secondary-color);
            z-index: 0;
            background: var(--primary-color);
        }
        .flatpickr-calendar:before, .flatpickr-calendar.arrowTop:after {display:none;}
    </style>
</head>
<body>
<header>
    <div class="container">
        <div class="column">
            <img src="{{ asset($brand->alt_logo ?? $brand->logo) }}" alt="logo">
        </div>
        <div class="column right">
            <p>Require assistance?</p>
            <a href="tel:{{$brand->phone}}"><span><img src="{{ asset('icons/Call-Icon.svg') }}" alt="logo"></span><span>{{$brand->phone}}</span></a>
        </div>
    </div>
</header>

<main>
    <section class="secure-booking">
        <div class="container">
            <div class="heading">
                <div class="breadcrumbs"><span><img src="{{ asset('icons/Arrow-left.svg') }}" alt="left-arrow"></span><span>BACK</span></div>
                <h1>Secure Booking</h1>
            </div>
            <div class="timeline">
                <div class="step @if($stage === 1) active @elseif($stage > 1) completed @endif">
                    <div class="circle">
                        <span>01</span>
                    </div>
                    <div class="label">Guests</div>
                </div>
                <div class="step @if($stage === 2) active @elseif($stage > 2) completed @endif">
                    <div class="circle">
                        <span>02</span>
                    </div>
                    <div class="label">Accommodation</div>
                </div>
                <div class="step @if($stage === 3) active @elseif($stage > 3) completed @endif">
                    <div class="circle">
                        <span>03</span>
                    </div>
                    <div class="label">Ticket(s)</div>
                </div>
                <div class="step @if($stage === 4) active @elseif($stage > 4) completed @endif">
                    <div class="circle">
                        <span>04</span>
                    </div>
                    <div class="label">Additional Inclusions</div>
                </div>
                <div class="step @if($stage === 5) active @elseif($stage > 5) completed @endif">
                    <div class="circle">
                        <span>05</span>
                    </div>
                    <div class="label">Details</div>
                </div>
                <div class="step @if($stage === 6) active @elseif($stage > 6) completed @endif">
                    <div class="circle">
                        <span>06</span>
                    </div>
                    <div class="label">Confirmation</div>
                </div>
            </div>
        </div>
    </section>

    <?php if($stage == 1):?>
    <section class="package-container first">
        <div class="container">
            <div class="column left">
                <h2 class="sub-heading-2">QUARTER FINALS PACKAGE</h2>
                <h3 class="sub-heading-3">Australian Open</h3>
                <div class="location-dollar-value">
                    <p class="location">Melbourne, Australia</p>
                    <span></span>
                    <p class="dollar">From A$2,995 / person twin share</p>
                </div>
                <div class="no-of-travellers">
                    <h4 class="sub-heading-4">Number of Travellers</h4>
                    <div class="quantity">
                        <span class="minus"><img src="{{ asset('icons/Minus.svg') }}" alt="minus"></span>
                        <span>|</span>
                        <span class="value">5</span>
                        <span>|</span>
                        <span class="plus"><img src="{{ asset('icons/Plus.svg') }}" alt="plus"></span>
                    </div>
                </div>
                <div class="contact-block">
                    <p class="description">If you are a concession card holder, or booking with children under 12, get in touch for a tailor-made package:</p>
                    <p class="phone">Domestic <a href="tel:1300 730 023">+1300 730 023</a></p>
                    <p class="phone">International <a href="tel:+61 2 7201 9353"> +61 2 7201 9353</a></p>
                    <p class="email">Email <a href="mailto:travel@kpt.com.au">travel@kpt.com.au</a></p>
                </div>
            </div>
            <div class="column right">
                <div class="package-details">
                    <div class="contain">
                        <div class="top-module">
                            <h4 class="sub-heading-4">Package details</h4>
                            <div class="hide-package-detail">Hide package details</div>
                        </div>
                        <div class="image-block">
                            <img src="{{ asset('images/sportEvent.png') }}" alt="package-details">
                        </div>
                        <div class="base-package">
                            <h6 class="sub-heading-6">BASE PACKAGE</h6>
                            <h2>QUARTER FINALS PACKAGE</h2>
                            <ul>
                                <li>21 Jan 25 - 24 Jan 25</li>
                                <li>Mens Semi Final Ticket</li>
                                <li>3 Nights, 5-Star Accommodation</li>
                                <li>Exclusive function & more</li>
                            </ul>
                        </div>
                        <div class="additional-inclusions">
                            <h6 class="sub-heading-6  display-none">ADDITIONAL INCLUSIONS</h6>
                            <div class="select-currency">
                                <div class="single">
                                    <p>Select-currency</p>
                                    <select>
                                        <option>AUD</option>
                                        <option>USD</option>
                                        <option>GBP</option>
                                    </select>
                                </div>
                                <div class="single">
                                    <p>Package price</p>
                                    <p>A$2,995</p>
                                </div>
                                <div class="single display-none">
                                    <p>Number of packages - 5</p>
                                    <p>A$14,975</p>
                                </div>
                            </div>
                            <div class="added-nights display-none">
                                <h5>Added nights</h5>
                                <div class="single">
                                    <p>
                                        <span>2x Additional nights</span>
                                        <span>20 Jan - 25 Jan 2025</span>
                                    </p>
                                    <p>A$1,500</p>
                                </div>
                            </div>
                            <div class="room-upgrades display-none">
                                <h5>Room upgrades</h5>
                                <div class="single">
                                    <p>Deluxe (Double)</p>
                                    <p>A$500</p>
                                </div>
                                <div class="single">
                                    <p>Deluxe (Twin)</p>
                                    <p>Price included</p>
                                </div>
                                <div class="single">
                                    <p>Deluxe (Double)</p>
                                    <p>Price included</p>
                                </div>
                            </div>
                            <div class="Hotel display-none">
                                <h5>Hotel</h5>
                                <div class="single">
                                    <p>Pan Pacific, Melbourne</p>
                                    <p>Price included</p>
                                </div>
                            </div>
                            <div class="ticket-upgrades display-none">
                                <h5>Ticket upgrades</h5>
                                <div class="single">
                                    <p>Ticket alterations</p>
                                    <p>A$500</p>
                                </div>
                                <div class="single">
                                    <p>Additional ticket/s</p>
                                    <p>A$500</p>
                                </div>
                            </div>
                            <div class="additional-upgrades display-none">
                                <h5>Additional upgrades</h5>
                                <div class="single">
                                    <p>Melbourne Foodie Walking Tour</p>
                                    <p>A$150</p>
                                </div>
                            </div>
                            <div class="total">
                                <div class="single">
                                    <p>Total</p>
                                    <p>A$17,125</p>
                                </div>
                                <div class="single display-none">
                                    <p>Starting package price</p>
                                    <p>A$17,125</p>
                                </div>
                                <div class="single display-none">
                                    <p>Customisation cost</p>
                                    <p>A$17,125</p>
                                </div>
                            </div>
                        </div>
                        <div class="payment-method ">
                            <h6 class="sub-heading-6 display-none">PAYMENT METHOD</h6>
                            <div class="option-wrapper display-none">
                                <label class="radio-option">
                                    <input type="radio" name="payment" checked>
                                    <span class="custom-radio"></span>
                                    <span class="option-title">Pay in full</span>
                                </label>
                                <div class="price">A$17,125</div>
                            </div>
                            <div class="option-wrapper display-none">
                                <div>
                                    <label class="radio-option">
                                        <input type="radio" name="payment">
                                        <span class="custom-radio"></span>
                                        <span class="option-title">Pay a 50% deposit now, and the rest later</span>
                                    </label>
                                    <div class="option-subtext">
                                        The remaining balance of A$8,563 will be automatically charged to the same payment method on 24 June 2024
                                    </div>
                                </div>
                                <div class="price">A$8,563</div>
                            </div>

                            <div class="card-block display-none">
                                <div class="card-type active">
                                    <img src="{{ asset('icons/card.svg') }}" alt="Debit card">
                                    <p>Credit / Debit card</p>
                                </div>
                                <div class="card-type">
                                    <img src="{{ asset('icons/document-text.svg') }}" alt="Direct Debit">
                                    <p>Invoice - Direct Debit</p>
                                </div>
                            </div>

                            <div class="payable-now">
                                <div class="single">
                                    <p>Payable now</p>
                                    <p>A$3,425</p>
                                </div>
                                <p>Balance A$13,700 payable by 14 Feb 2025</p>
                            </div>

                            <div class="email-quote">
                                <h6 class="sub-heading-6">EMAIL quote</h6>
                                <form style="display:none;">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email">
                                </form>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="next-button">
                      <span>
                        <span>NEXT</span>
                        <img src="{{ asset('icons/Right-arrow-mod.svg') }}" alt="right-arrow">
                      </span>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <?php endif;?>

    <?php if($stage == 2):?>
    <section class="package-container">
        <div class="container">
            <div class="column left">
                <h2 class="sub-heading-2">QUARTER FINALS PACKAGE</h2>
                <h3 class="sub-heading-3">Australian Open</h3>
                <div class="location-dollar-value">
                    <p class="location">Melbourne, Australia</p>
                    <span></span>
                    <p class="dollar">From A$2,995 / person twin share</p>
                </div>
                <div class="accommodation-detail ">
                    <h2 class="sub-heading-2-p">ACCOMMODATION DETAILS</h2>
                    <p>Review and customise your accommodation details. Selecting a different hotel or room type may impact the total cost.</p>
                    <h6 class="sub-heading-6">DEFAULT HOTEL INCLUDED IN THIS PACKAGE</h6>
                    <div class="locate">
                        <div class="image">
                            <img src="{{ asset('images/accommodation/hotel_1.jpg') }}" alt="melbourne">
                        </div>
                        <div class="text-block">
                            <h6>Pan Pacific, Melbourne</h6>
                            <p>3 star</p>
                            <p>+A$0</p>
                        </div>
                    </div>
                </div>
                <div class="booking-dates">
                    <h6 class="sub-heading-6">BOOKING DATES</h6>
                    <p>Change your check-in and check-out dates to extend your stay by adding extra nights before or after the included 3-night package.</p>

                    <p class="mod">Extend your stay</p>

                    <div class="check-in-check-out">
                        <div class="first">
                            <div class="image-module">
                                <img src="{{ asset('icons/checkin.svg') }}" alt="icon">
                                <input type="text" id="dateRange" placeholder="Select Date Range">
                            </div>
                            <div class="text-block">
                                <p>Check-in</p>
                                <p>21 Jan 25</p>
                            </div>
                        </div>
                        <div>
                            -
                        </div>
                        <div class="second">
                            <div class="image-module">
                                <img src="{{ asset('icons/checkin.svg') }}" alt="icon">
                                <!-- <input type="date"> -->
                            </div>
                            <div class="text-block">
                                <p>Check-out</p>
                                <p>24 Jan 25</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="room-configuration">

                    <h6 class="sub-heading-6">ROOM CONFIGURATION</h6>

                    <div class="no-of-travellers">
                        <p>Number of rooms you wish to book</p>
                        <div class="quantity">
                            <span class="minus"><img src="{{ asset('icons/Minus.svg') }}" alt="minus"></span>
                            <span>|</span>
                            <span class="value">5</span>
                            <span>|</span>
                            <span class="plus"><img src="{{ asset('icons/Plus.svg') }}" alt="plus"></span>
                        </div>
                    </div>
                </div>
                <div class="room-selection">
                    <h6 class="sub-heading-6">ROOM SELECTION</h6>
                    <p>If you would like to upgrade, select from the upgrade options below.</p>
                    <p>Then, choose your preferred bedding configuration for each room.</p>

                    <div class="showcase">
                        <div class="single">
                            <div><img src="{{ asset('icons/Bed.svg') }}" alt="bed"></div>
                            <p>Double</p>
                        </div>
                        <div class="single">
                            <div><img src="{{ asset('icons/Bed.svg') }}" alt="bed"><img src="{{ asset('icons/Bed.svg') }}" alt="bed"></div>
                            <p>Twin</p>
                        </div>
                        <div class="single">
                            <div><img src="{{ asset('icons/Bed.svg') }}" alt="bed"><img src="{{ asset('icons/Bed.svg') }}" alt="bed"><img src="{{ asset('icons/Bed.svg') }}" alt="bed"></div>
                            <p>Triple</p>
                        </div>
                    </div>

                    <div class="room-listing-module">
                        <div class="single-room">
                            <h6>Room 1</h6>
                            <p>Lorem Ipsum is simply dummy</p>
                            <ul>
                                <li>Size of room: 52 sq m</li>
                                <li>Size of bed: 1 king bed</li>
                            </ul>
                            <p>Number of guests</p>
                            <div class="guest-module">
                                <div>1</div>
                                <div class="active">2</div>
                                <div>3</div>
                            </div>
                            <p>Bed configuration</p>
                            <div class="bed-configuration active">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Double
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Twin-bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/twin-bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Twin
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Triple-Bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Triple-Bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Triple
                                </div>
                            </div>
                            <button type="button" class="include-button">INCLUDE</button>
                        </div>
                        <div class="single-room">
                            <h6>Room 2</h6>
                            <p>Lorem Ipsum is simply dummy</p>
                            <ul>
                                <li>Size of room: 52 sq m</li>
                                <li>Size of bed: 1 king bed</li>
                            </ul>
                            <p>Number of guests</p>
                            <div class="guest-module">
                                <div>1</div>
                                <div class="active">2</div>
                                <div>3</div>
                            </div>
                            <p>Bed configuration</p>
                            <div class="bed-configuration active">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Double
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Twin-bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/twin-bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Twin
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Triple-Bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Triple-Bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Triple
                                </div>
                            </div>
                            <button type="button" class="include-button">INCLUDE</button>
                        </div>
                        <div class="single-room">
                            <h6>Room 3</h6>
                            <p>Lorem Ipsum is simply dummy</p>
                            <ul>
                                <li>Size of room: 52 sq m</li>
                                <li>Size of bed: 1 king bed</li>
                            </ul>
                            <p>Number of guests</p>
                            <div class="guest-module">
                                <div class="active">1</div>
                                <div>2</div>
                                <div>3</div>
                            </div>
                            <p>Bed configuration</p>
                            <div class="bed-configuration active">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Bed-double.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Double
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Twin-bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/twin-bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Twin
                                </div>
                            </div>
                            <div class="bed-configuration">
                                <div class="bed-icon">
                                    <img src="{{ asset('icons/Triple-Bed.svg') }}" alt="bed-icon" class="default">
                                    <img src="{{ asset('icons/Triple-Bed-hover.svg') }}" alt="bed-icon" class="hover">
                                </div>
                                <div class="twin">
                                    Triple
                                </div>
                            </div>
                            <button type="button" class="include-button">INCLUDE</button>
                        </div>
                    </div>
                </div>
                <div class="hotel">
                    <h6 class="sub-heading-6">HOTEL</h6>
                    <p>Your package includes a 3-night stay at Pan Pacific Melbourne, a 5-star hotel.  If you’d like to upgrade, please select from one of the other options below.</p>

                    <div class="hotel-listing">
                        <div class="single-hotel">
                            <div class="hotel-image-block">
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_2.webp') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_3.jpg') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_4.jpg') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_5.jpg') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/hotel_1.jpg') }}" alt="hotel-images">
                                </div>
                            </div>
                            <div class="hotel-block">
                                <h6>Pan Pacific, Melbourne</h6>
                                <p>3 star</p>
                                <p>+A$0</p>

                                <div class="room-type">
                                    <p>Room type</p>

                                    <select>
                                        <option>Deluxe room</option>
                                        <option>Basic room</option>
                                        <option>Deluxe room</option>
                                    </select>

                                    <p class="breakfast-note">Breakfast included daily</p>

                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>

                                    <button type="button" class="include-button">INCLUDED</button>
                                </div>
                            </div>
                        </div>
                        <div class="single-hotel">
                            <div class="hotel-image-block">
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_2.webp') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_3.jpg') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_4.jpg') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_5.jpg') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/hotel_1.jpg') }}" alt="hotel-images">
                                </div>
                            </div>
                            <div class="hotel-block">
                                <h6>The Langham, Melbourne</h6>
                                <p>5 star</p>
                                <p>+ A$200</p>

                                <div class="room-type">
                                    <p>Room type</p>

                                    <select>
                                        <option>Deluxe room</option>
                                        <option>Basic room</option>
                                        <option>Deluxe room</option>
                                    </select>

                                    <p class="breakfast-note">Breakfast included daily</p>

                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>

                                    <button type="button" class="include-button">NOT AVAILABLE</button>

                                    <span class="not-available">Change travel dates above to check availability</span>

                                </div>
                            </div>
                        </div>
                        <div class="single-hotel">
                            <div class="hotel-image-block">
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_2.webp') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_3.jpg') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_4.jpg') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/accommodation_5.jpg') }}" alt="hotel-images">
                                </div>
                                <div>
                                    <img src="{{ asset('images/accommodation/hotel_1.jpg') }}" alt="hotel-images">
                                </div>
                            </div>
                            <div class="hotel-block">
                                <h6>The Westin, Melbourne</h6>
                                <p>5 star</p>
                                <p>+ A$300</p>

                                <div class="room-type">
                                    <p>Room type</p>

                                    <select>
                                        <option>Deluxe room</option>
                                        <option>Basic room</option>
                                        <option>Deluxe room</option>
                                    </select>

                                    <p class="breakfast-note">Breakfast included daily</p>

                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>

                                    <button type="button" class="include-button active">Upgrade</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="column right">
                <div class="package-details">
                    <div class="contain">
                        <div class="top-module">
                            <h4 class="sub-heading-4">Package details</h4>
                            <div class="hide-package-detail">Hide package details</div>
                        </div>
                        <div class="image-block">
                            <img src="{{ asset('images/sportEvent.png') }}" alt="package-details">
                        </div>
                        <div class="base-package">
                            <h6 class="sub-heading-6">BASE PACKAGE</h6>
                            <h2>QUARTER FINALS PACKAGE</h2>
                            <ul>
                                <li>21 Jan 25 - 24 Jan 25</li>
                                <li>Mens Semi Final Ticket</li>
                                <li>3 Nights, 5-Star Accommodation</li>
                                <li>Exclusive function & more</li>
                            </ul>
                        </div>
                        <div class="additional-inclusions">
                            <h6 class="sub-heading-6">ADDITIONAL INCLUSIONS</h6>
                            <div class="select-currency">
                                <div class="single">
                                    <p>Select-currency</p>
                                    <select>
                                        <option>AUD</option>
                                        <option>GBP</option>
                                        <option>USD</option>
                                    </select>
                                </div>
                                <div class="single">
                                    <p>Package price</p>
                                    <p>A$2,995</p>
                                </div>
                                <div class="single">
                                    <p>Number of packages - 5</p>
                                    <p>A$14,975</p>
                                </div>
                            </div>
                            <div class="added-nights">
                                <h5>Added nights</h5>
                                <div class="single">
                                    <p>
                                        <span>2 x Additional nights</span>
                                        <span>20 Jan - 25 Jan 2025</span>
                                    </p>
                                    <p>A$1,500</p>
                                </div>
                            </div>
                            <div class="room-upgrades">
                                <h5>Room upgrades</h5>
                                <div class="single">
                                    <p>Deluxe (Double)</p>
                                    <p>A$500</p>
                                </div>
                                <div class="single">
                                    <p>Deluxe (Twin)</p>
                                    <p>Price included</p>
                                </div>
                                <div class="single">
                                    <p>Deluxe (Double)</p>
                                    <p>Price included</p>
                                </div>
                            </div>
                            <div class="Hotel">
                                <h5>Hotel</h5>
                                <div class="single">
                                    <p>Pan Pacific, Melbourne</p>
                                    <p>Price included</p>
                                </div>
                            </div>
                            <div class="ticket-upgrades display-none">
                                <h5>Ticket upgrades</h5>
                                <div class="single">
                                    <p>Ticket alterations</p>
                                    <p>A$500</p>
                                </div>
                                <div class="single">
                                    <p>Additional ticket/s</p>
                                    <p>A$500</p>
                                </div>
                            </div>
                            <div class="additional-upgrades display-none">
                                <h5>Additional upgrades</h5>
                                <div class="single">
                                    <p>Melbourne Foodie Walking Tour</p>
                                    <p>A$150</p>
                                </div>
                            </div>
                            <div class="total">
                                <div class="single">
                                    <p>Total</p>
                                    <p>A$17,125</p>
                                </div>
                                <div class="single">
                                    <p>Starting package price</p>
                                    <p>$14,975</p>
                                </div>
                                <div class="single">
                                    <p>Customisation cost</p>
                                    <p>$500</p>
                                </div>
                            </div>
                        </div>
                        <div class="payment-method ">
                            <h6 class="sub-heading-6 display-none">PAYMENT METHOD</h6>
                            <div class="option-wrapper display-none">
                                <label class="radio-option">
                                    <input type="radio" name="payment" checked>
                                    <span class="custom-radio"></span>
                                    <span class="option-title">Pay in full</span>
                                </label>
                                <div class="price">A$17,125</div>
                            </div>
                            <div class="option-wrapper display-none">
                                <div>
                                    <label class="radio-option">
                                        <input type="radio" name="payment">
                                        <span class="custom-radio"></span>
                                        <span class="option-title">Pay a 50% deposit now, and the rest later</span>
                                    </label>
                                    <div class="option-subtext">
                                        The remaining balance of A$8,563 will be automatically charged to the same payment method on 24 June 2024
                                    </div>
                                </div>
                                <div class="price">A$8,563</div>
                            </div>

                            <div class="card-block display-none">
                                <div class="card-type active">
                                    <img src="{{ asset('icons/card.svg') }}" alt="Debit card">
                                    <p>Credit / Debit card</p>
                                </div>
                                <div class="card-type">
                                    <img src="{{ asset('icons/document-text.svg.svg') }}" alt="Direct Debit">
                                    <p>Invoice - Direct Debit</p>
                                </div>
                            </div>

                            <div class="payable-now">
                                <div class="single">
                                    <p>Payable now</p>
                                    <p>A$3,425</p>
                                </div>
                                <p>Balance A$13,700 payable by 14 Feb 2025</p>
                            </div>

                            <div class="email-quote">
                                <h6 class="sub-heading-6">EMAIL quote</h6>
                                <form style="display:none;">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email">
                                </form>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="next-button">
              <span>
                <span>NEXT</span>
                <img src="{{ asset('icons/Right-arrow-mod.svg') }}" alt="right-arrow">
              </span>
                    </button>
                    <span class="accomodation-travel-date-error">
                        <span>
                          <img src="{{ asset('icons/Noti-Icon.svg') }}" alt="icon">
                        </span>
                        <span>
                        Total number of travellers vs. the number of guests you have selected for rooms does not match - please update your room selection to proceed
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <?php endif;?>

</main>


    <footer>
        <div class="container">
            <div class="Go-back">
                BACK
            </div>
            <div class="value">
                <div>
                    <h6>A$2,995</h6>
                    <p>Per person, twin share</p>
                </div>
                <span></span>
                <div>
                    <h6>A$2,995</h6>
                    <p>Per person, twin share</p>
                </div>
            </div>
            <div class="view-details">
                View package details
            </div>
            <div class="Go-next">
                NEXT
            </div>
        </div>
    </footer>

    <script>
        flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            minDate: "today",
            onClose: function(selectedDates, dateStr, instance) {
                const firstDate = selectedDates[0];
                const secondDate = selectedDates[1];

                if(firstDate && secondDate){
                    const options = { day: '2-digit', month: 'short', year: '2-digit' };
                    const formattedFirst = firstDate.toLocaleDateString('en-GB', options).replace(',', '');
                    const formattedSecond = secondDate.toLocaleDateString('en-GB', options).replace(',', '');

                    jQuery('.check-in-check-out .first .text-block p').eq(1).text(formattedFirst);

                    jQuery('.check-in-check-out .second .text-block p').eq(1).text(formattedSecond);
                }


            },
            monthSelectorType: 'static' // Ensures the arrows always show
        });
        jQuery(document).on('click','.check-in-check-out .second .image-module',function (params) {
            jQuery('.check-in-check-out .image-module .flatpickr-input').click();
        })
    </script>
</body>
</html>












