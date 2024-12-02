<?php /** @noinspection ProperNullCoalescingOperatorUsageInspection */

namespace App\Actions\Activity;

use App\Exceptions\CannotDeleteException;
use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Order\Component\OrderActivity;
use App\Models\Quote\Component\QuoteActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class DeleteActivity
{
    use AsAction, AsController;

    /**
     * Verify an activity can be deleted, then delete it.
     * If the activity has dependants, then throw CannotDeleteException
     *
     * @param Activity $activity
     * @return void
     * @throws CannotDeleteException
     */
    public function handle(Activity $activity): void
    {
        if ($this->getTourCount($activity) > 0) {
            throw new CannotDeleteException('Component has dependant tours');
        }
        if ($this->getQuoteCount($activity) > 0) {
            throw new CannotDeleteException('Component has dependant quotes');
        }
        if ($this->getOrderCount($activity) > 0) {
            throw new CannotDeleteException('Component has dependant orders');
        }
        $activity->forceDelete();
    }

    public function asController(ActionRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $this->handle($this->getActivity($request->id));
        } catch (CannotDeleteException $e) {
            if ($request->expectsJson()) {
                return response()->json(['msg' => $e->getMessage()], 422);
            }
            return back()->withErrors(['msg' => $e->getMessage()]);
        }
        if ($request->expectsJson()) {
            return response()->json(null, 204);
        }
        return redirect()->route('activities.all');
    }

    public function authorize(ActionRequest $request): bool
    {
        // If it doesn't exist, then check if they can delete any, so it throws a 403, rather than a 404
        return $request->user()->can('delete', ($this->getActivity($request->id) ?? Activity::class));
    }

    public function rules(): array
    {
        return ['id' => 'required|integer|exists:activities,id'];
    }

    private function getActivity(int|null $id): Activity|null
    {
        return Activity::withTrashed()->find($id);
    }

    private function getTourCount(Activity $activity): int
    {
        $query = ActivityInventoryTour::query()
            ->withTrashed()
            ->leftJoin('activity_inventories', 'activity_inventories.id', '=', 'activity_inventory_tours.activity_inventory_id')
            ->where('activity_inventories.activity_id', '=', $activity->id);
        return $query->count();
    }

    private function getQuoteCount(Activity $activity): int
    {
        $query = QuoteActivity::query()
            ->withTrashed()
            ->leftJoin('activity_inventories', 'activity_inventories.id', '=', 'quote_activities.activity_inventory_id')
            ->where('activity_inventories.activity_id', '=', $activity->id);
        return $query->count();
    }

    private function getOrderCount(Activity $activity): int
    {
        $query = OrderActivity::query()
                ->withTrashed()
                ->leftJoin('activity_inventory_tours', 'activity_inventory_tours.id', '=', 'order_activities.activity_inventory_tour_id')
                ->leftJoin('activity_inventories', 'activity_inventories.id', '=', 'activity_inventory_tours.activity_inventory_id')
                ->where('activity_inventories.activity_id', '=', $activity->id);
        return $query->count();
    }
}
