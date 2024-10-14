<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tour\EventRequest;
use App\Http\Requests\Admin\TableRequest;
use App\Models\Tour\Event;
use Illuminate\Http\Request;

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

    public function view(TableRequest $request, Event $event)
    {
        return view('pages.admin.event.view', ['event' => $event, 'hideNoCategory' => $request->hideNoCategory ?? false,]);
    }

    public function edit(Event $event)
    {
        return view('pages.admin.event.form', ['event' => $event,]);
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

    public function destroy(Event $event)
    {
        if ($event->tours()->count() > 0) {
            return back()->withErrors(trans('custom.used-elsewhere', ['model' => 'Event', 'parent' => 'Tour']));
        }
        $event->delete();
        return redirect()->route('events.all');
    }
}
