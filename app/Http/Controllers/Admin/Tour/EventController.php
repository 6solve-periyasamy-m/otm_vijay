<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Exports\BulkReminderExport;
use App\Helpers\ActivitySortFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TableRequest;
use App\Http\Requests\Admin\Tour\EventRequest;
use App\Models\Tour\Event;
use App\Repository\Reporting\Manifest\OrderManifestRepository;
use Excel;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EventController extends Controller
{

    public function index()
    {
        return view('pages.admin.event.table', ['events' => Event::all(),]);
    }

    public function create()
    {
        return view('pages.admin.event.form');
    }

    public function store(EventRequest $request)
    {
        $parent = Event::find($request->parent_id);
        if ($parent?->parent_event_id !== null) {
            return back()->withErrors(['msg' => 'Cannot use a parent that is a child of another event',]);
        }
        $event = Event::create($request->getData());

        if ($request->image !== null) {
            $event->image_url = store_file($request->image);
        }
        $event->save();
        return redirect()->route('events.view', ['event' => $event,]);
    }

    public function bulkRemind(Event $event)
    {
        return view('pages.admin.order.reminder.bulk', ['orders' => $event->orders, 'export' => route('events.reminder.bulk.export', ['event' => $event, 'extension' => 'xlsx'])]);
    }

    public function bulkRemindExport(Event $event, string $extension = 'xslx'): BinaryFileResponse
    {
        $filename = 'bulk-reminders-' . sanitize(strtolower($event->name)) . '.' . $extension;
        return Excel::download(new BulkReminderExport($event->orders->all()), $filename);
    }

    public function view(TableRequest $request, Event $event)
    {
        return view('pages.admin.event.view', ['event' => $event, 'activityFilter' => $request->activityFilter ?? ActivitySortFilter::MAIN_ACTIVITY, 'hideNoCategory' => $request->hideNoCategory ?? flag('tour.category.hide', false),]);
    }

    public function edit(Event $event)
    {
        return view('pages.admin.event.form', ['event' => $event,]);
    }

    public function hotelReport(Event $event)
    {
        return view('pages.admin.event.accommodation', ['event' => $event,]);
    }

    public function orderManifest(Event $event)
    {
        return (new OrderManifestRepository($event->repository))->view('events.manifest.order.export', ['event' => $event]);
    }

    public function exportOrderManifest(Event $event, string $extension = 'xslx'): BinaryFileResponse
    {
        return (new OrderManifestRepository($event->repository))->export($extension);
    }

    public function update(EventRequest $request, Event $event)
    {
        $parent = Event::find($request->parent_id);
        if ($parent?->parent_event_id !== null) {
            return back()->withErrors(['msg' => 'Cannot use a parent that is a child of another event',]);
        }
        $event->update($request->getData());

        if ($request->image !== null) {
            $event->image_url = store_file($request->image, $event->image_url);
        }
        $event->save();
        return redirect()->route('events.view', ['event' => $event,]);
    }

    public function duplicate(Event $event): RedirectResponse
    {
        $event = $event->repository->duplicate();
        return redirect()->route('events.view', ['event' => $event,]);
    }

    public function destroy(Event $event)
    {
        if ($event->tours()->count() > 0) {
            return back()->withErrors(trans('custom.used-elsewhere', ['model' => 'Event', 'parent' => 'Tour']));
        }
        $event->delete();
        return redirect()->route('events.all');
    }
}
