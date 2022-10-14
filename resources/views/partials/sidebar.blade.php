<div class="col-12 col-md-3 col-xl-2 p-0  otm-sidebar d-block py-3">
    <ul class="nav flex-column mb-auto">
        <li>
            @if(strpos(Request::path(), 'dash') !== false)
                <a href="{{ route('dash') }}" class="nav-link active">
            @else
                <a href="{{ route('dash') }}" class="nav-link">
            @endif
                <i class="icon-list"></i>
                <span>Dashboard</span>
                @if(strpos(Request::path(), 'dash') !== false)
                    <span class="selected"></span>
                @endif
            </a>
        </li>
        @can('read', 'App\Models\Tour\Tour')
        <li>
            @if(strpos(Request::path(), 'tours') !== false)
            <a href="{{ route('tours.all') }}" class="nav-link active">
            @else
            <a href="{{ route('tours.all') }}" class="nav-link">
            @endif
                <i class="icon-globe"></i>
                <span>Tours</span>
                @if(strpos(Request::path(), 'tours') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        @endcan
        @can('read', 'App\Models\Accommodation\Accommodation')
        <li>
            @if(strpos(Request::path(), 'accommodation') !== false)
            <a href="{{ route('accommodations.all') }}" class="nav-link active">
            @else
            <a href="{{ route('accommodations.all') }}" class="nav-link">
            @endif
                <i class="icon-home"></i>
                <span>Accommodation</span>
                @if(strpos(Request::path(), 'accommodation') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
            @endcan
        @can('read', 'App\Models\Activity\Activity')
        <li>
            @if(strpos(Request::path(), 'activities') !== false)
            <a href="{{ route('activities.all') }}" class="nav-link active">
            @else
            <a href="{{ route('activities.all') }}" class="nav-link">
            @endif
                <i class="icon-game-controller"></i>
                <span>Activities</span>
                @if(strpos(Request::path(), 'activities') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
                @endcan
        @can('read', 'App\Models\Flight\Flight')
        <li>
            @if(strpos(Request::path(), 'flights') !== false)
            <a href="{{ route('flights.all') }}" class="nav-link active">
            @else
            <a href="{{ route('flights.all') }}" class="nav-link">
            @endif
                <i class="icon-plane"></i>
                <span>Flights</span>
                @if(strpos(Request::path(), 'flights') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
                @endcan
        @can('read', 'App\Models\Transport\Transport')
        <li>
            @if(strpos(Request::path(), 'transports') !== false)
            <a href="{{ route('transports.all') }}" class="nav-link active">
            @else
            <a href="{{ route('transports.all') }}" class="nav-link">
            @endif
                <i class="icon-directions"></i>
                <span>Transport</span>
                @if(strpos(Request::path(), 'transports') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        @endcan
        @can('read', 'App\Models\Merchandise\Merchandise')
        <li>
            @if(strpos(Request::path(), 'merchandise') !== false)
            <a href="{{ route('merchandise.all') }}" class="nav-link active">
            @else
            <a href="{{ route('merchandise.all') }}" class="nav-link">
            @endif
                <i class="icon-badge"></i>
                <span>Merchandise</span>
                @if(strpos(Request::path(), 'merchandise') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        @endcan
        @can('read', 'App\Models\Location\Address')
        <li>
            @if(strpos(Request::path(), 'addresses') !== false)
            <a href="{{ route('addresses.all') }}" class="nav-link active">
            @else
            <a href="{{ route('addresses.all') }}" class="nav-link">
            @endif
                <i class="icon-envelope-letter"></i>
                <span>Addresses</span>
                @if(strpos(Request::path(), 'addresses') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        @endcan
        @can('read', 'App\Models\Order\Order')
        <li>
            @if(strpos(Request::path(), 'orders') !== false)
            <a href="{{ route('orders.all') }}" class="nav-link active">
            @else
            <a href="{{ route('orders.all') }}" class="nav-link">
            @endif
                <i class="icon-credit-card"></i>
                <span>Orders</span>
                @if(strpos(Request::path(), 'orders') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        @endcan
        @can('read', 'App\Models\Quote\Quote')
        <li>
            @if(strpos(Request::path(), 'quotes') !== false)
            <a href="{{ route('quotes.all') }}" class="nav-link active">
            @else
            <a href="{{ route('quotes.all') }}" class="nav-link">
            @endif
                <i class="icon-wallet"></i>
                <span>Quotes</span>
                @if(strpos(Request::path(), 'quotes') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        @endcan
        @can('read', 'App\Models\Customer\Customer')
        <li>
            @if(strpos(Request::path(), 'customers') !== false)
            <a href="{{ route('customers.all') }}" class="nav-link active">
            @else
            <a href="{{ route('customers.all') }}" class="nav-link ">
            @endif
                <i class="icon-user"></i>
                <span>Customers</span>
                @if(strpos(Request::path(), 'customers') !== false)
                <span class="selected"></span>
                @endif
            </a>
        </li>
        @endcan
        @can('update', 'App\Models\System\Setting')
            <li>
                @if(strpos(Request::path(), 'settings') !== false)
                    <a href="{{ route('settings.edit') }}" class="nav-link active">
                        @else
                            <a href="{{ route('settings.edit') }}" class="nav-link ">
                                @endif
                                <i class="icon-settings"></i>
                                <span>Settings</span>
                                @if(strpos(Request::path(), 'settings') !== false)
                                    <span class="selected"></span>
                                @endif
                            </a>
            </li>
        @endcan
        <li>
            @if(strpos(Request::path(), 'attributes') !== false)
                <a href="{{ route('attributes.edit') }}" class="nav-link active">
            @else
                <a href="{{ route('attributes.edit') }}" class="nav-link ">
            @endif
                    <i class="icon-flag"></i>
                    <span>Attributes Manager</span>
                    @if(strpos(Request::path(), 'attributes') !== false)
                        <span class="selected"></span>
                    @endif
                </a>
        </li>
        @can('read', 'App\Models\User')
            <li>
                @if(strpos(Request::path(), 'users') !== false)
                <a href="{{ route('users.all') }}" class="nav-link active">
                @else
                <a href="{{ route('users.all') }}" class="nav-link ">
                @endif
                    <i class="icon-people"></i>
                    <span>Users</span>
                    @if(strpos(Request::path(), 'users') !== false)
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            <li>
                @if(strpos(Request::path(), 'roles') !== false)
                <a href="{{ route('roles.all') }}" class="nav-link active">
                @else
                <a href="{{ route('roles.all') }}" class="nav-link ">
                @endif
                    <i class="icon-organization"></i>
                    <span>Roles</span>
                    @if(strpos(Request::path(), 'roles') !== false)
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
        @endcan
        @can('read', \App\Models\System\Report::class)
            <li>
                @if(strpos(Request::path(), 'reports') !== false)
                    <a href="{{ route('reports.bespoke.all') }}" class="nav-link active">
                @else
                    <a href="{{ route('reports.bespoke.all') }}" class="nav-link ">
                @endif
                    <i class="icon-list"></i>
                    <span>Reports</span>
                @if(strpos(Request::path(), 'reports') !== false)
                    <span class="selected"></span>
                @endif
                </a>
            </li>
        @endcan
        @if(Auth::user() !== null && Auth::user()->getHighestRoleLevel() === 999)
            <li>
                <a href="{{ url('/system/logs') }}" class="nav-link">
                    <i class="icon-layers"></i>
                    <span>Log Viewer</span>
                </a>
            </li>
        @endif
    </ul>
</div>
