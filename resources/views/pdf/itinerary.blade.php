@php
/**
 * @var \App\Models\Order\OrderCustomer $orderCustomer
 */
@endphp
<html lang="en">
<head>
    <title>Itinerary - {{$orderCustomer->order->booking_reference}} - {{$orderCustomer->customer_name}}</title>

    <style>
        <?php include(public_path().'/css/itinerary.css') ?>
    </style>
</head>
<body>
<div class="card">
    <h1 class="mb-0" style="width: 100%; text-align: center;">{{$orderCustomer->order->tour->name}} - {{$orderCustomer->order->booking_reference}} - {{$orderCustomer->customer_name}}</h1>
</div>
@foreach($orderCustomer->repository->getComponentsForItinerary() as $day => $components)
    <div class="card">
        <h2 class="mb-0" style="width: 100%; text-align: center;">{{ \Carbon\Carbon::createFromTimestamp($day)->format('l jS F Y') }}</h2>
    </div>
    @foreach($components as $component)
        @include('partials.pdf.customer.itinerary', ['orderComponent' => $component,])
    @endforeach
@endforeach
</body>
</html>
