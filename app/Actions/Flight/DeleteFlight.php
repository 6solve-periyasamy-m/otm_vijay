<?php /** @noinspection ProperNullCoalescingOperatorUsageInspection */

namespace App\Actions\Flight;

use App\Exceptions\CannotDeleteException;
use App\Models\Flight\Flight;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Order\Component\OrderFlight;
use App\Models\Quote\Component\QuoteFlight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class DeleteFlight
{
    use AsAction, AsController;

    /**
     * Verify an flight can be deleted, then delete it.
     * If the flight has dependants, then throw CannotDeleteException
     *
     * @param Flight $flight
     * @return void
     * @throws CannotDeleteException
     */
    public function handle(Flight $flight): void
    {
        if ($this->getTourCount($flight) > 0) {
            throw new CannotDeleteException('Component has dependant tours');
        }
        if ($this->getQuoteCount($flight) > 0) {
            throw new CannotDeleteException('Component has dependant quotes');
        }
        if ($this->getOrderCount($flight) > 0) {
            throw new CannotDeleteException('Component has dependant orders');
        }
        $flight->forceDelete();
    }

    public function asController(ActionRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $this->handle($this->getFlight($request->id));
        } catch (CannotDeleteException $e) {
            if ($request->expectsJson()) {
                return response()->json(['msg' => $e->getMessage()], 422);
            }
            return back()->withErrors(['msg' => $e->getMessage()]);
        }
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('flights.all');
    }

    public function authorize(ActionRequest $request): bool
    {
        // If it doesn't exist, then check if they can delete any, so it throws a 403, rather than a 404
        return $request->user()->can('delete', ($this->getFlight($request->id) ?? Flight::class));
    }

    public function rules(): array
    {
        return ['id' => 'required|integer|exists:flights,id'];
    }

    private function getFlight(int|null $id): Flight|null
    {
        return Flight::withTrashed()->find($id);
    }

    private function getTourCount(Flight $flight): int
    {
        $query = FlightInventoryTour::query()
            ->withTrashed()
            ->leftJoin('flight_inventories', 'flight_inventories.id', '=', 'flight_inventory_tours.flight_inventory_id')
            ->where('flight_inventories.flight_id', '=', $flight->id);
        return $query->count();
    }

    private function getQuoteCount(Flight $flight): int
    {
        $query = QuoteFlight::query()
            ->withTrashed()
            ->leftJoin('flight_inventories', 'flight_inventories.id', '=', 'quote_flights.flight_inventory_id')
            ->where('flight_inventories.flight_id', '=', $flight->id);
        return $query->count();
    }

    private function getOrderCount(Flight $flight): int
    {
        $query = OrderFlight::query()
                ->withTrashed()
                ->leftJoin('flight_inventory_tours', 'flight_inventory_tours.id', '=', 'order_flights.flight_inventory_tour_id')
                ->leftJoin('flight_inventories', 'flight_inventories.id', '=', 'flight_inventory_tours.flight_inventory_id')
                ->where('flight_inventories.flight_id', '=', $flight->id);
        return $query->count();
    }
}
