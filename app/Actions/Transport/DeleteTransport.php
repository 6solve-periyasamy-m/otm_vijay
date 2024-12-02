<?php /** @noinspection ProperNullCoalescingOperatorUsageInspection */

namespace App\Actions\Transport;

use App\Exceptions\CannotDeleteException;
use App\Models\Order\Component\OrderTransport;
use App\Models\Quote\Component\QuoteTransport;
use App\Models\Transport\Transport;
use App\Models\Transport\TransportInventoryTour;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class DeleteTransport
{
    use AsAction, AsController;

    /**
     * Verify an transport can be deleted, then delete it.
     * If the transport has dependants, then throw CannotDeleteException
     *
     * @param Transport $transport
     * @return void
     * @throws CannotDeleteException
     */
    public function handle(Transport $transport): void
    {
        if ($this->getTourCount($transport) > 0) {
            throw new CannotDeleteException('Component has dependant tours');
        }
        if ($this->getQuoteCount($transport) > 0) {
            throw new CannotDeleteException('Component has dependant quotes');
        }
        if ($this->getOrderCount($transport) > 0) {
            throw new CannotDeleteException('Component has dependant orders');
        }
        $transport->forceDelete();
    }

    public function asController(ActionRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $this->handle($this->getTransport($request->id));
        } catch (CannotDeleteException $e) {
            if ($request->expectsJson()) {
                return response()->json(['msg' => $e->getMessage()], 422);
            }
            return back()->withErrors(['msg' => $e->getMessage()]);
        }
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('transports.all');
    }

    public function authorize(ActionRequest $request): bool
    {
        // If it doesn't exist, then check if they can delete any, so it throws a 403, rather than a 404
        return $request->user()->can('delete', ($this->getTransport($request->id) ?? Transport::class));
    }

    public function rules(): array
    {
        return ['id' => 'required|integer|exists:transports,id'];
    }

    private function getTransport(int|null $id): Transport|null
    {
        return Transport::withTrashed()->find($id);
    }

    private function getTourCount(Transport $transport): int
    {
        $query = TransportInventoryTour::query()
            ->withTrashed()
            ->leftJoin('transport_inventories', 'transport_inventories.id', '=', 'transport_inventory_tours.transport_inventory_id')
            ->where('transport_inventories.transport_id', '=', $transport->id);
        return $query->count();
    }

    private function getQuoteCount(Transport $transport): int
    {
        $query = QuoteTransport::query()
            ->withTrashed()
            ->leftJoin('transport_inventories', 'transport_inventories.id', '=', 'quote_transports.transport_inventory_id')
            ->where('transport_inventories.transport_id', '=', $transport->id);
        return $query->count();
    }

    private function getOrderCount(Transport $transport): int
    {
        $query = OrderTransport::query()
                ->withTrashed()
                ->leftJoin('transport_inventory_tours', 'transport_inventory_tours.id', '=', 'order_transports.transport_inventory_tour_id')
                ->leftJoin('transport_inventories', 'transport_inventories.id', '=', 'transport_inventory_tours.transport_inventory_id')
                ->where('transport_inventories.transport_id', '=', $transport->id);
        return $query->count();
    }
}
