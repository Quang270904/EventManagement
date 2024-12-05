<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function __construct() {}

    public function getAllTicket(Request $request)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        $tickets = Ticket::with('event')
            ->when($request->input('search'), function ($query) use ($request) {
                $query->where('ticket_type', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('price', 'like', '%' . $request->input('search') . '%');
            })
            ->paginate(10);

        return view('admin.ticket.index', compact('tickets', 'userDetail', 'role','user'));
    }

    public function formCreateTicket()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        $events = Event::all();

        return view('admin.ticket.createTicket', compact('userDetail', 'role', 'events'));
    }

    public function createTicket(Request $request)
    {
        try {
            $validated = $request->validate([
                'event_id' => 'required|exists:events,id',
                'ticket_type' => 'required|string|max:255',
                'price' => 'required|numeric',
            ]);

            $ticket = Ticket::create([
                'event_id' => $validated['event_id'],
                'ticket_type' => $validated['ticket_type'],
                'price' => $validated['price'],
            ]);

            return redirect()->route('admin.ticket')->with('success', 'Ticket created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating ticket: ' . $e->getMessage());
            return redirect()->route('admin.ticket.create')->with('error', 'Ticket creation failed!');
        }
    }

    public function formEditTicket($id)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        $ticket = Ticket::findOrFail($id);

        return view('admin.ticket.editTicket', compact('userDetail', 'role', 'ticket'));
    }

    public function updateTicket($id, Request $request)
    {
        $validated = $request->validate([
            'ticket_type' => 'required|string|in:regular,vip,discounted',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            $ticket = Ticket::findOrFail($id);

            $ticket->update([
                'ticket_type' => $request->input('ticket_type'),
                'price' => $request->input('price'),
            ]);

            return redirect()->route('admin.ticket')->with('success', 'Ticket updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.ticket')->with('error', 'Failed to update ticket');
        }
    }

    public function deleteTicket($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);

            $ticket->delete();

            return redirect()->route('admin.ticket')->with('success', 'Ticket deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting ticket: ' . $e->getMessage());
            return redirect()->route('admin.ticket')->with('error', 'Delete failed.');
        }
    }
}
