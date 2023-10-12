@php
/**
 * @var \App\Models\Order\OrderCustomer $orderCustomer
 */
@endphp
<html lang="en">
<head>
    <title>Itinerary - {{$orderCustomer->order->booking_reference}} - {{$orderCustomer->customer_name}}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}"/>
</head>
<body>
<div class="card">
    <div class="card-body">
        <h1 class="mb-0" style="width: 100%; text-align: center;">{{$orderCustomer->order->tour->name}} - {{$orderCustomer->order->booking_reference}} - {{$orderCustomer->customer_name}}</h1>
    </div>
</div>
@foreach($orderCustomer->repository->getComponentsForItinerary() as $day => $components)
    <div class="card">
        <div class="card-body">
            <h2 class="mb-0" style="width: 100%; text-align: center;">{{ \Carbon\Carbon::createFromTimestamp($day)->format('l jS F Y') }}</h2>
        </div>
    </div>
    @foreach($components as $component)
        @include('partials.customer.itinerary', ['orderComponent' => $component,])
    @endforeach
@endforeach
</body>
</html>
