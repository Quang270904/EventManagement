<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct() {}


    public function getAllTickets($eventId)
    {
        $event = Event::findOrFail($eventId);

        $tickets = Ticket::where('event_id', $eventId)->get();

        return view('admin.ticket', compact('event', 'tickets'));
    }

    public function formCreateTicket($eventId)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        $event = Event::findOrFail($eventId);
        return view('admin.ticket.createTicket', compact('event', 'userDetail', 'role', 'event'));
    }

    public function createTicket(TicketRequest $request, $eventId)
    {
        $validated = $request->validated(); 

        $event = Event::findOrFail($eventId);

        $ticket = Ticket::create([
            'event_id' => $eventId,
            'ticket_type' => $validated['ticket_type'],
            'price' => $validated['price'],
        ]);

        return redirect()->route('admin.event.show', $eventId)->with('success', 'Ticket created successfully!');
    }
}
