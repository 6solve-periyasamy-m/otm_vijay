@extends('layout.customer')

@section('title', 'Checkout Purchase')

@section('content')
    <div id="dropIn">

    </div>
@endsection

@push('footer-stack')
    <script>
        $(document).ready(function () {
            Airwallex.init({
                env: '{{ config('app.gateways.airwallex.live', false) ? 'prod' : 'demo' }}',
                origin: window.location.origin,
            });
            const element = Airwallex.createElement('dropIn', {
                intent_id: '{{ $intent['id'] }}',
                client_secret: '{{ $intent['secret'] }}',
                currency: '{{setting('system.currency', config('cashier.currency', 'gbp'))}}',
            })
            element.mount('dropIn');
            const mount = mount('dropIn');
            mount.addEventListener('onSuccess', (event) => { alert('success'); });
            mount.addEventListener('onError', (event) => { alert('error'); });
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
