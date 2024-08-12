@extends('layout.customer')

@section('title', 'Checkout Purchase')

@section('content')
    <button onclick="checkout()">Checkout</button>
@endsection

@push('footer-stack')
    <script>
        $(document).ready(function () {
            Airwallex.init({
                env: '{{ config('app.gateways.airwallex.live', false) ? 'prod' : 'demo' }}',
                origin: window.location.origin,
            });
        });

        function checkout() {
            Airwallex.redirectToCheckout({
                env: '{{ config('app.gateways.airwallex.live', false) ? 'prod' : 'demo' }}',
                intent_id: '{{ $intent['id'] }}',
                client_secret: '{{ $intent['secret'] }}',
                currency: '{{setting('system.currency', config('cashier.currency', 'gbp'))}}',
            });
        }
    </script>
@endpush
