<?php

namespace App\View\Components\Admin;

use App\Models\Accommodation\Accommodation;
use App\Models\Activity\Activity;
use App\Models\Customer\Customer;
use App\Models\Flight\Flight;
use App\Models\Location\Address;
use App\Models\Merchandise\Merchandise;
use App\Models\Order\Order;
use App\Models\Quote\Quote;
use App\Models\System\Report;
use App\Models\System\Setting;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use App\Models\Transport\Transport;
use App\Models\User;
use Bouncer;
use Closure;
use Icon;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarLink extends Component
{
    private string $name;
    private string $url;
    private View|string|Closure $icon;
    private ?string $search;
    private bool $permitted;

    public function __construct(string $name, string $url, View|string|Closure $icon, ?string $search = null, ?string $class = null, ?string $permission = null)
    {
        $this->name = $name;
        $this->url = $url;
        $this->icon = $icon;
        $this->search = $search;
        $this->permitted = (!isset($class) || !isset($permission)) || Bouncer::can($permission, $class);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.admin.sidebar-link', [
            'name' => $this->name,
            'url' => $this->url,
            'icon' => $this->icon,
            'search' => $this->search,
            'permitted' => $this->permitted,
        ]);
    }

    /**
     * @return SidebarLink[]
     */
    public static function getSidebarLinks(): array
    {
        return [
            new SidebarLink('Dashboard', route('dash'), Icon::dashboard()),
            new SidebarLink('Events', route('events.all'), Icon::event(), 'events', Event::class, 'read'),
            new SidebarLink('Tours', route('tours.all'), Icon::tour(), 'tours', Tour::class, 'read'),
            new SidebarLink('Accommodation', route('accommodations.all'), Icon::accommodation(), 'accommodation', Accommodation::class, 'read'),
            new SidebarLink('Activities', route('activities.all'), Icon::activity(), 'activities', Activity::class, 'read'),
            new SidebarLink('Flights', route('flights.all'), Icon::flight(), 'flights', Flight::class, 'read'),
            new SidebarLink('Transport', route('transports.all'), Icon::transport(), 'transport', Transport::class, 'read'),
            new SidebarLink('Merchandise', route('merchandise.all'), Icon::merchandise(), 'merchandise', Merchandise::class, 'read'),
            new SidebarLink('Addresses', route('addresses.all'), Icon::address(), 'addresses', Address::class, 'read'),
            new SidebarLink('Vouchers', route('vouchers.index'), Icon::voucher(), 'vouchers'),
            new SidebarLink('Orders', route('orders.all'), Icon::order(), 'orders', Order::class, 'read'),
            new SidebarLink('Quotes', route('quotes.all'), Icon::quote(), 'quotes', Quote::class, 'read'),
            new SidebarLink('Customers', route('customers.all'), Icon::customer(), 'customers', Customer::class, 'read'),
            new SidebarLink('Organizations', route('organizations.all'), Icon::organization(), 'organization', Customer::class, 'read'),
            new SidebarLink('Settings', route('settings.edit'), Icon::setting(), 'settings', Setting::class, 'update'),
            new SidebarLink('Attributes Manager', route('attributes.edit'), Icon::attribute(), 'attributes'),
            new SidebarLink('Users', route('users.all'), Icon::user(), 'users', User::class, 'read'),
            new SidebarLink('Roles', route('roles.all'), Icon::role(), 'roles', User::class, 'read'),
            new SidebarLink('Reports', route('reports.all'), Icon::report(), 'reports', Report::class, 'read'),
        ];
    }

    public static function getLogsURL(): SidebarLink
    {
        return new SidebarLink('Log Viewer', url('/system/logs'), Icon::logs());
    }
}
