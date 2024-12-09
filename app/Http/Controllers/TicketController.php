<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTicketRequest;
use App\Http\Requests\TicketRequest;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function __construct() {}

    public function getAllTicket()
    {
        $tickets = Ticket::with(['event'])
            ->paginate(10);

        return response()->json([
            'tickets' => $tickets->items(),
            'pagination' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'total' => $tickets->total(),
            ]
        ]);
    }

    public function createTicket(CreateTicketRequest $request)
    {
        try {
            $validated = $request->validated();

            $ticket = new Ticket();
            $ticket->event_id = $validated['event_id'];
            $ticket->ticket_type = $validated['ticket_type'];
            $ticket->price = $validated['price'];
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully!',
                'ticket' => $ticket
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error creating ticket: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ticket creation failed! Please try again.'
            ], 500);
        }
    }

    public function updateTicket($id, TicketRequest $request)
    {
        try {
            $ticket = Ticket::findOrFail($id);

            $validated = $request->validated();

            $ticket->ticket_type = $validated['ticket_type'];
            $ticket->price = $validated['price'];
            $ticket->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Event created successfully!',
                'event' => $ticket,
            ], 200);
        } catch (\Exception $e) {
            return redirect()->route('admin.ticket')->with('error', 'Failed to update ticket');
        }
    }

    public function deleteTicket($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);

            $ticket->delete();
            return response()->json(['res' => 'Ticket deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error deleting ticket: ' . $e->getMessage());
            return redirect()->route('admin.ticket')->with('error', 'Delete failed.');
        }
    }

    public function search(Request $request)
    {
        $search = $request->get('search', '');
        $tickets = Ticket::with(['event'])
            ->whereHas('event', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . "%");
            })
            ->orWhere('price', 'like', '%' . $search . '%')
            ->orWhere('ticket_type', 'like', '%' . $search . '%')
            ->paginate(10);

        return response()->json([
            'tickets' => $tickets->items(),
            'pagination' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'total' => $tickets->total(),
            ]
        ]);
    }

    public function formTicketList(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $userDetail = $user->userDetail;
        $tickets = Ticket::with(['event'])
            ->paginate(10);
        return view('admin.ticket.index', compact('user', 'userDetail', 'tickets', 'role'));
    }

    public function formCreateTicket()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        $events = Event::all();

        return view('admin.ticket.createTicket', compact('user', 'userDetail', 'role', 'events'));
    }

    public function formEditTicket($id)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        $ticket = Ticket::findOrFail($id);

        return view('admin.ticket.editTicket', compact('user', 'userDetail', 'role', 'ticket'));
    }
}
