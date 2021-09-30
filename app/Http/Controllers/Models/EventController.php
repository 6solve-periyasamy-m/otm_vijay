<?php

namespace App\Http\Controllers\Models;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller {

  public function index() {
    return view('pages.models.events.table', ['events' => Event::all(),]);
  }

  public function create() {
    return view('pages.models.events.create');
  }

  public function store(Request $request) {
    $event = Event::create([
      'event_title' => $request->input('event_title'),
      'event_description' => $request->input('event_description'),
      'event_start_date' => $request->input('event_start_date'),
      'event_end_date' => $request->input('event_end_date'),
      'booking_url' => $request->input('booking_url'),
      'notes' => $request->input('notes'),
    ]);
    return redirect()->route('events.view', ['event' => $event, ]);
  }

  public function view(Event $event) {
    return view('pages.models.events.view', ['event' => $event, ]);
  }

  public function edit(Event $event) {
    return view('pages.models.events.update', ['event' => $event, ]);
  }

  public function update(Request $request, Event $event) {
    $event->update([
      'event_title' => $request->input('event_title'),
      'event_description' => $request->input('event_description'),
      'event_start_date' => $request->input('event_start_date'),
      'event_end_date' => $request->input('event_end_date'),
      'booking_url' => $request->input('booking_url'),
      'notes' => $request->input('notes'),
    ]);
    return redirect()->route('events.view', ['event' => $event, ]);
  }

  public function destroy(Event $event) {
    $event->delete();
    return redirect()->route('events.all');
  }
}
