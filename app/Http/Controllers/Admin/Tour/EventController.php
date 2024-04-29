<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $request->validate(Event::getValidationRules());
        $event = Event::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'starts_at' => $request->input('starts_at'),
            'ends_at' => $request->input('ends_at'),
            'booking_url' => $request->input('booking_url'),
            'tax_bracket_id' => $request->input('tax_bracket_id'),
            'notes' => $request->input('notes'),
        ]);

        if ($request->has('image') && $request->file('image') != null) {
            $event->image_url = $request->file('image')->storePublicly('uploads/images');
        }
        $event->save();
        return redirect()->route('events.view', ['event' => $event,]);
    }

    public function view(Event $event)
    {
        return view('pages.admin.event.view', ['event' => $event,]);
    }

    public function edit(Event $event)
    {
        return view('pages.admin.event.form', ['event' => $event,]);
    }

    public function update(Request $request, Event $event)
    {
        $request->validate(Event::getValidationRules());
        $event->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'starts_at' => $request->input('starts_at'),
            'ends_at' => $request->input('ends_at'),
            'booking_url' => $request->input('booking_url'),
            'tax_bracket_id' => $request->input('tax_bracket_id'),
            'notes' => $request->input('notes'),
        ]);

        if ($request->has('image') && $request->file('image') != null) {
            if (isset($event->image_url)) {
                File::delete(public_path($event->image_url));
            }
            $event->image_url = $request->file('image')->storePublicly('uploads/images');
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
