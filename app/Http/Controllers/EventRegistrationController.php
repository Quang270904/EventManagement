<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
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
            // Log::error('Ticket not found', ['ticket_id' => $ticketId, 'event_id' => $eventId]);
            return response()->json(['status' => 'error', 'message' => 'Invalid ticket selection.'], 400);
        }

        if (intval($ticket->event_id) !== intval($eventId)) {
            // Log::error('Invalid ticket selection', ['ticket_id' => $ticketId, 'ticket_event_id' => $ticket->event_id, 'event_id' => $eventId]);
            return response()->json(['status' => 'error', 'message' => 'This ticket is not valid for the selected event.'], 400);
        }

        $registration = new EventRegistration();
        $registration->user_id = $user->id;
        $registration->event_id = $eventId;
        $registration->ticket_id = $ticketId;
        $registration->status = 'registered';
        $registration->save();

        // Trả về phản hồi thành công
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

        $event = Event::find($eventId);
        $event->is_registered = false; 
    
        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully cancelled your registration for the event.',
            'event' => $event
        ]);
    }
}
