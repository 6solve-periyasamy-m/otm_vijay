<?php /** @noinspection ProperNullCoalescingOperatorUsageInspection */

namespace App\Actions\Accommodation;

use App\Exceptions\CannotDeleteException;
use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Quote\Component\QuoteAccommodation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class DeleteAccommodation
{
    use AsAction, AsController;

    /**
     * Verify an accommodation can be deleted, then delete it.
     * If the accommodation has dependants, then throw CannotDeleteException
     *
     * @param Accommodation $accommodation
     * @return void
     * @throws CannotDeleteException
     */
    public function handle(Accommodation $accommodation): void
    {
        if ($this->getTourCount($accommodation) > 0) {
            throw new CannotDeleteException('Component has dependant tours');
        }
        if ($this->getQuoteCount($accommodation) > 0) {
            throw new CannotDeleteException('Component has dependant quotes');
        }
        if ($this->getOrderCount($accommodation) > 0) {
            throw new CannotDeleteException('Component has dependant orders');
        }
        $accommodation->forceDelete();
    }

    public function asController(ActionRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $this->handle($this->getAccommodation($request->id));
        } catch (CannotDeleteException $e) {
            if ($request->expectsJson()) {
                return response()->json(['msg' => $e->getMessage()], 422);
            }
            return back()->withErrors(['msg' => $e->getMessage()]);
        }
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('accommodations.all');
    }

    public function authorize(ActionRequest $request): bool
    {
        // If it doesn't exist, then check if they can delete any, so it throws a 403, rather than a 404
        return $request->user()->can('delete', ($this->getAccommodation($request->id) ?? Accommodation::class));
    }

    public function rules(): array
    {
        return ['id' => 'required|integer|exists:accommodations,id'];
    }

    private function getAccommodation(int|null $id): Accommodation|null
    {
        return Accommodation::withTrashed()->find($id);
    }

    private function getTourCount(Accommodation $accommodation): int
    {
        $query = AccommodationInventoryTour::query()
            ->withTrashed()
            ->leftJoin('accommodation_inventories', 'accommodation_inventories.id', '=', 'accommodation_inventory_tours.accommodation_inventory_id')
            ->where('accommodation_inventories.accommodation_id', '=', $accommodation->id);
        return $query->count();
    }

    private function getQuoteCount(Accommodation $accommodation): int
    {
        $query = QuoteAccommodation::query()
            ->withTrashed()
            ->leftJoin('accommodation_inventories', 'accommodation_inventories.id', '=', 'quote_accommodations.accommodation_inventory_id')
            ->where('accommodation_inventories.accommodation_id', '=', $accommodation->id);
        return $query->count();
    }

    private function getOrderCount(Accommodation $accommodation): int
    {
        $query = OrderAccommodation::query()
                ->withTrashed()
                ->leftJoin('accommodation_inventory_tours', 'accommodation_inventory_tours.id', '=', 'order_accommodations.accommodation_inventory_tour_id')
                ->leftJoin('accommodation_inventories', 'accommodation_inventories.id', '=', 'accommodation_inventory_tours.accommodation_inventory_id')
                ->where('accommodation_inventories.accommodation_id', '=', $accommodation->id);
        return $query->count();
    }
}
