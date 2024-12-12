<?php

namespace App\Http\Controllers;

use App\Events\RegisterEvent;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Notification;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventRegistrationController extends Controller
{
    public function __construct() {}

    public function registerEvent($eventId)
    {

        $user = Auth::user();
        $role = $user->role;
        $userDetail = $user->userDetail;

        $event = Event::findOrFail($eventId);
        $tickets = Ticket::where('event_id', $eventId)->get();

        return view('user.event.register_event', compact('user', 'role', 'userDetail', 'event', 'tickets'));
    }

    public function processRegistration(Request $request, $eventId)
    {
        $user = Auth::user();

        Log::info('User attempting to register for event', ['user_id' => $user->id, 'event_id' => $eventId]);

        if (!$user) {
            Log::error('User not authenticated', ['event_id' => $eventId]);
            return response()->json(['status' => 'error', 'message' => 'You must be logged in to register.'], 401);
        }

        $ticketId = $request->input('ticket_id');
        $ticket = Ticket::find($ticketId);

        Log::info('Ticket selected', ['ticket_id' => $ticketId, 'ticket_event_id' => $ticket ? $ticket->event_id : null, 'event_id' => $eventId]);

        if (!$ticket) {
            return response()->json(['status' => 'error', 'message' => 'Invalid ticket selection.'], 400);
        }

        if (intval($ticket->event_id) !== intval($eventId)) {
            return response()->json(['status' => 'error', 'message' => 'This ticket is not valid for the selected event.'], 400);
        }

        $existingRegistration = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->where('status', 'registered')
            ->first();

        if ($existingRegistration) {
            return response()->json(['status' => 'error', 'message' => 'You have already registered for this event.'], 400);
        }

        $registration = new EventRegistration();
        $registration->user_id = $user->id;
        $registration->event_id = $eventId;
        $registration->ticket_id = $ticketId;
        $registration->status = 'registered';
        $registration->save();
        event(new RegisterEvent($user, $registration->event, $registration->created_at));

        $notification = new Notification();
        $notification->user_id = $user->id;
        $notification->event_id = $eventId;
        $notification->message = $user->userDetail->full_name . ' has registered for ' . $registration->event->name;
        $notification->save();

        return response()->json(['status' => 'success', 'message' => 'You have successfully registered for the event!']);
    }

    public function cancel($eventId)
    {
        $user = Auth::user();

        $registration = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->where('status', 'registered')
            ->first();

        if (!$registration) {
            return response()->json(['status' => 'error', 'message' => 'You are not registered for this event.'], 400);
        }

        $registration->status = 'cancelled';
        $registration->save();

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully cancelled your registration for the event.',
            'event_id' => $eventId
        ]);
    }

    public function formEventRegisterd()
    {
        $user = Auth::user();
        $role = $user->role;
        $userDetail = $user->userDetail;

        return view('user.event.get_event_registered', compact('user', 'role', 'userDetail'));
    }

    public function getAllEventRegisterd(Request $request)
    {
        $user = Auth::user();

        $registrations = EventRegistration::with('event')
            ->where('user_id', $user->id)
            ->where('status', 'registered')
            ->when($request->search, function ($query) use ($request) {
                return $query->whereHas('event', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'events' => $registrations->items(),
            'pagination' => [
                'total' => $registrations->total(),
                'current_page' => $registrations->currentPage(),
                'last_page' => $registrations->lastPage(),
            ],
        ]);
    }
}
