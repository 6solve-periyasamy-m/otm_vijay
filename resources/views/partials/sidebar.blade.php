<div class="col-12 col-md-3 col-xl-2 p-0  otm-sidebar collapse py-3">
    <ul class="nav flex-column mb-auto">        
        <li>            
            <a href="{{ route('dash') }}" class="nav-link">
                <i class="icon-list"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            @if(strpos(Request::url(), 'tours') !== false)
            <a href="{{ route('tours.all') }}" class="nav-link active">
            @else
            <a href="{{ route('tours.all') }}" class="nav-link">
            @endif
                <i class="icon-globe"></i>
                <span>Tours</span>
                @if(strpos(Request::url(), 'tours') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        <li>
            @if(strpos(Request::url(), 'accommodation') !== false)
            <a href="{{ route('accommodations.all') }}" class="nav-link active">
            @else
            <a href="{{ route('accommodations.all') }}" class="nav-link">
            @endif
                <i class="icon-home"></i>
                <span>Accommodation</span>
                @if(strpos(Request::url(), 'accommodation') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('activities.all') }}" class="nav-link">
                <i class="icon-settings"></i>
                <span>Activities</span>
            </a>
        </li>
        <li>
            @if(strpos(Request::url(), 'flights') !== false)
            <a href="{{ route('flights.all') }}" class="nav-link active">
            @else
            <a href="{{ route('flights.all') }}" class="nav-link">
            @endif
                <i class="icon-plane"></i>
                <span>Flights</span>
                @if(strpos(Request::url(), 'flights') !== false)
                <span class="selected"></span>
                @endif
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
            <a href="#" class="nav-link ">
                <i class="icon-user"></i>
                <span>Customers</span>
            </a>
        </li>
    </ul>    
</div>
