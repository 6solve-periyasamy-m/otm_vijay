<?php 
namespace App\Http\Controllers;

use App\Models\Tour\Event;
use App\Models\Booking\Booking;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with the latest event and booking count.
     */

    public function dashboard()
    {
        $event = Event::where('starts_at', '>=', now())
            ->orderBy('starts_at', 'asc')
            ->first();

        $bookCount = Booking::count();

        $booking_monthsarr = Booking::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                            ->groupBy(DB::raw('MONTH(created_at)'))
                            ->orderBy(DB::raw('MONTH(created_at)'))
                            ->get();

        $orderCount = Order::count();
        $order_monthsarr = Order::select(DB::raw('MONTH(ordered_on) as month'), DB::raw('COUNT(*) as count'))
        ->groupBy(DB::raw('MONTH(ordered_on)'))
        ->orderBy(DB::raw('MONTH(ordered_on)'))
        ->get();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year; 
        $monthsData = [];

        for ($month = 1; $month <= $currentMonth; $month++) {
            
            $currentMonthCount = Booking::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->count();
                
            if ($month > 1) {
                $previousMonthCount = Booking::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month - 1)
                    ->count();
            } else {
                $previousMonthCount = 0;
            }

            if ($previousMonthCount > 0) {
                $percentageChange = (($currentMonthCount - $previousMonthCount) / $previousMonthCount) * 100;
            } else {
                $percentageChange = $currentMonthCount > 0 ? 100 : 0;
            }

            $percentageChange = round($percentageChange, 2);

            $monthName = Carbon::create()->month($month)->format('M');

            $monthsData[] = [
                'month' => $monthName,
                'count' => $currentMonthCount,
                'percentageChange' => $percentageChange
            ];
        }
        return view('pages.dash', [
            'event' => $event,
            'orderCount' => $orderCount,
            'order_monthsarr' => $order_monthsarr,
            'monthsData' => $monthsData,
            'bookCount' => $bookCount,
            'booking_monthsarr'=>$booking_monthsarr
        ]);
    }
}


?>