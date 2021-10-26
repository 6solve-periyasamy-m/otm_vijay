<div class="col-12 col-md-3 col-xl-2 p-0  otm-sidebar collapse py-3">
    <ul class="nav flex-column mb-auto">        
        <li>            
            <a href="{{ route('dash') }}" class="nav-link">
                <i class="icon-list"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('tours.all') }}" class="nav-link active">
                <i class="icon-globe"></i>
                <span>Tours</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('accommodations.all') }}" class="nav-link">
                <i class="icon-home"></i>
                <span>Accommodation</span>
            </a>
        </li>
        <li>
            <a href="{{ route('activities.all') }}" class="nav-link">
                <i class="icon-settings"></i>
                <span>Activities</span>
            </a>
        </li>
        <li>
            <a href="{{ route('flights.all') }}" class="nav-link">
                <i class="icon-plane"></i>
                <span>Flights</span>
            </a>
        </li>
        <li>
            <a href="{{ route('transports.all') }}" class="nav-link">
                <i class="icon-directions"></i>
                <span>Transports</span>
            </a>
        </li>
        <li>
            <a href="{{ route('orders.all') }}" class="nav-link">
                <i class="icon-credit-card"></i>
                <span>Orders</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customers.all') }}" class="nav-link ">
                <i class="icon-user"></i>
                <span>Customers</span>
            </a>
        </li>
    </ul>    
</div>
