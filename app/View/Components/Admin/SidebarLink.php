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
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Silber\Bouncer\Database\Role;

class SidebarLink extends Component
{
    private string $name;
    private string $url;
    private string $icon;
    private ?string $search;
    private bool $permitted;

    public function __construct(string $name, string $url, string $icon, ?string $search = null, ?string $class = null, ?string $permission = null)
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
            new SidebarLink('Dashboard', route('dash'), 'list'),
            new SidebarLink('Events', route('events.all'), 'calendar', 'events', Event::class, 'read'),
            new SidebarLink('Tours', route('tours.all'), 'globe', 'tours', Tour::class, 'read'),
            new SidebarLink('Accommodation', route('accommodations.all'), 'home', 'accommodation', Accommodation::class, 'read'),
            new SidebarLink('Activities', route('activities.all'), 'game-controller', 'activities', Activity::class, 'read'),
            new SidebarLink('Flights', route('flights.all'), 'plane', 'flights', Flight::class, 'read'),
            new SidebarLink('Transport', route('transports.all'), 'directions', 'transport', Transport::class, 'read'),
            new SidebarLink('Merchandise', route('merchandise.all'), 'badge', 'merchandise', Merchandise::class, 'read'),
            new SidebarLink('Address', route('addresses.all'), 'envelope-letter', 'addresses', Address::class, 'read'),
            new SidebarLink('Orders', route('orders.all'), 'credit-card', 'orders', Order::class, 'read'),
            new SidebarLink('Quotes', route('quotes.all'), 'wallet', 'quotes', Quote::class, 'read'),
            new SidebarLink('Customers', route('customers.all'), 'user', 'customers', Customer::class, 'read'),
            new SidebarLink('Settings', route('settings.edit'), 'settings', 'settings', Setting::class, 'update'),
            new SidebarLink('Attributes Manager', route('attributes.edit'), 'flag', 'attributes'),
            new SidebarLink('Users', route('users.all'), 'people', 'users', User::class, 'read'),
            new SidebarLink('Roles', route('roles.all'), 'organization', 'roles', User::class, 'read'),
            new SidebarLink('Reports', route('reports.bespoke.all'), 'list', 'reports', Report::class, 'read'),
        ];
    }

    public static function getLogsURL(): SidebarLink
    {
        return new SidebarLink('Log Viewer', url('/system/logs'), 'layers');
    }
}
