<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="#" class="nav-link active" aria-current="page">
                Home
            </a>
        </li>
        <li>
            <a href="{{ route('dash') }}" class="nav-link text-white">
                Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('tours.all') }}" class="nav-link text-white">Tours</a>
        </li>
        <li>
            <a href="{{ route('accommodations.all') }}" class="nav-link text-white">Accommodation</a>
        </li>
        <li>
            <a href="{{ route('activities.all') }}" class="nav-link text-white">Activities</a>
        </li>
        <li>
            <a href="{{ route('flights.all') }}" class="nav-link text-white">Flights</a>
        </li>
        <li>
            <a href="{{ route('transports.all') }}" class="nav-link text-white">Transports</a>
        </li>
        <li>
            <a href="{{ route('orders.all') }}" class="nav-link text-white">
                Orders
            </a>
        </li>
        <li>
            <a href="#" class="nav-link text-white">
                Customers
            </a>
        </li>
    </ul>
    <hr>
</div>
