<div class="col-12 col-md-3 col-xl-2 p-0  otm-sidebar collapse py-3">
    <ul class="nav flex-column mb-auto">
        <li>
            @if(strpos(Request::url(), 'dash') !== false)
                <a href="{{ route('dash') }}" class="nav-link active">
            @else
                <a href="{{ route('dash') }}" class="nav-link">
            @endif
                <i class="icon-list"></i>
                <span>Dashboard</span>
                @if(strpos(Request::url(), 'dash') !== false)
                    <span class="selected"></span>
                @endif
            </a>
        </li>
        @can('read', 'App\Models\Tour')
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
        @endcan
        @can('read', 'App\Models\Accommodation')
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
            @endcan
        @can('read', 'App\Models\Activity')
        <li>
            @if(strpos(Request::url(), 'activities') !== false)
            <a href="{{ route('activities.all') }}" class="nav-link active">
            @else
            <a href="{{ route('activities.all') }}" class="nav-link">
            @endif
                <i class="icon-game-controller"></i>
                <span>Activities</span>
                @if(strpos(Request::url(), 'activities') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
                @endcan
        @can('read', 'App\Models\Flight')
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
                @endcan
        @can('read', 'App\Models\Transport')
        <li>
            @if(strpos(Request::url(), 'transports') !== false)
            <a href="{{ route('transports.all') }}" class="nav-link active">
            @else
            <a href="{{ route('transports.all') }}" class="nav-link">
            @endif
                <i class="icon-directions"></i>
                <span>Transports</span>
                @if(strpos(Request::url(), 'transports') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        @endcan
        @can('read', 'App\Models\Order')
        <li>
            @if(strpos(Request::url(), 'orders') !== false)
            <a href="{{ route('orders.all') }}" class="nav-link active">
            @else
            <a href="{{ route('orders.all') }}" class="nav-link">
            @endif
                <i class="icon-credit-card"></i>
                <span>Orders</span>
                @if(strpos(Request::url(), 'orders') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        @endcan
        @can('read', 'App\Models\Customer')
        <li>
            @if(strpos(Request::url(), 'customers') !== false)
            <a href="{{ route('customers.all') }}" class="nav-link active">
            @else
            <a href="{{ route('customers.all') }}" class="nav-link ">
            @endif
                <i class="icon-user"></i>
                <span>Customers</span>
                @if(strpos(Request::url(), 'customers') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        @endcan
        @can('update', 'App\Models\Setting')
            <li>
                @if(strpos(Request::url(), 'settings') !== false)
                    <a href="{{ route('settings.edit') }}" class="nav-link active">
                        @else
                            <a href="{{ route('settings.edit') }}" class="nav-link ">
                                @endif
                                <i class="icon-settings"></i>
                                <span>Settings</span>
                                @if(strpos(Request::url(), 'settings') !== false)
                                    <span class="selected"></span>
                                @endif
                            </a>
            </li>
        @endcan
        @can('read', 'App\Models\User')
            <li>
                @if(strpos(Request::url(), 'users') !== false)
                <a href="{{ route('users.all') }}" class="nav-link active">
                @else
                <a href="{{ route('users.all') }}" class="nav-link ">
                @endif
                    <i class="icon-people"></i>
                    <span>Users</span>
                    @if(strpos(Request::url(), 'users') !== false)
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            <li>
                @if(strpos(Request::url(), 'roles') !== false)
                <a href="{{ route('roles.all') }}" class="nav-link active">
                @else
                <a href="{{ route('roles.all') }}" class="nav-link ">
                @endif
                    <i class="icon-organization"></i>
                    <span>Roles</span>
                    @if(strpos(Request::url(), 'roles') !== false)
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
        @endcan
    </ul>
</div>
