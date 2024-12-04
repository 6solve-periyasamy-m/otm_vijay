<?php /** @noinspection ProperNullCoalescingOperatorUsageInspection */

namespace App\Actions\Merchandise;

use App\Exceptions\CannotDeleteException;
use App\Models\Merchandise\Merchandise;
use App\Models\Merchandise\MerchandiseInventoryTour;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Quote\Component\QuoteMerchandise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class DeleteMerchandise
{
    use AsAction, AsController;

    /**
     * Verify an merchandise can be deleted, then delete it.
     * If the merchandise has dependants, then throw CannotDeleteException
     *
     * @param Merchandise $merchandise
     * @return void
     * @throws CannotDeleteException
     */
    public function handle(Merchandise $merchandise): void
    {
        if ($this->getTourCount($merchandise) > 0) {
            throw new CannotDeleteException('Component has dependant tours');
        }
        if ($this->getQuoteCount($merchandise) > 0) {
            throw new CannotDeleteException('Component has dependant quotes');
        }
        if ($this->getOrderCount($merchandise) > 0) {
            throw new CannotDeleteException('Component has dependant orders');
        }
        $merchandise->forceDelete();
    }

    public function asController(ActionRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $this->handle($this->getMerchandise($request->id));
        } catch (CannotDeleteException $e) {
            if ($request->expectsJson()) {
                return response()->json(['msg' => $e->getMessage()], 422);
            }
            return back()->withErrors(['msg' => $e->getMessage()]);
        }
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('merchandise.all');
    }

    public function authorize(ActionRequest $request): bool
    {
        // If it doesn't exist, then check if they can delete any, so it throws a 403, rather than a 404
        return $request->user()->can('delete', ($this->getMerchandise($request->id) ?? Merchandise::class));
    }

    public function rules(): array
    {
        return ['id' => 'required|integer|exists:merchandises,id'];
    }

    private function getMerchandise(int|null $id): Merchandise|null
    {
        return Merchandise::withTrashed()->find($id);
    }

    private function getTourCount(Merchandise $merchandise): int
    {
        $query = MerchandiseInventoryTour::query()
            ->withTrashed()
            ->leftJoin('merchandise_inventories', 'merchandise_inventories.id', '=', 'merchandise_inventory_tours.merchandise_inventory_id')
            ->where('merchandise_inventories.merchandise_id', '=', $merchandise->id);
        return $query->count();
    }

    private function getQuoteCount(Merchandise $merchandise): int
    {
        $query = QuoteMerchandise::query()
            ->withTrashed()
            ->leftJoin('merchandise_inventories', 'merchandise_inventories.id', '=', 'quote_merchandises.merchandise_inventory_id')
            ->where('merchandise_inventories.merchandise_id', '=', $merchandise->id);
        return $query->count();
    }

    private function getOrderCount(Merchandise $merchandise): int
    {
        $query = OrderMerchandise::query()
                ->withTrashed()
                ->leftJoin('merchandise_inventory_tours', 'merchandise_inventory_tours.id', '=', 'order_merchandises.merchandise_inventory_tour_id')
                ->leftJoin('merchandise_inventories', 'merchandise_inventories.id', '=', 'merchandise_inventory_tours.merchandise_inventory_id')
                ->where('merchandise_inventories.merchandise_id', '=', $merchandise->id);
        return $query->count();
    }
}
