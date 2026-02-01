<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarEventRequest;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class CalendarEventController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(CalendarEvent::query()
            ->where('user_id', $request->user()->id)
            ->where('is_school_event', false)
            ->get());
    }

    public function store(CalendarEventRequest $request)
    {
        $event = CalendarEvent::query()->create($request->validated() + [
            'user_id' => $request->user()->id,
            'is_school_event' => false,
        ]);

        return response()->json($event, 201);
    }

    public function show(CalendarEvent $calendarEvent)
    {
        return response()->json($calendarEvent);
    }

    public function update(CalendarEventRequest $request, CalendarEvent $calendarEvent)
    {
        $calendarEvent->update($request->validated());

        return response()->json($calendarEvent);
    }

    public function destroy(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();

        return response()->json(['message' => 'Deleted.']);
    }

    public function schoolEvents()
    {
        return response()->json(CalendarEvent::query()->where('is_school_event', true)->get());
    }
}
