<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function __construct() {}

    // with role admin
    public function getAllEvent()
    {
        $events = Event::with(['user', 'user.userDetail'])
            ->paginate(10);

        return response()->json([
            'events' => $events->items(),
            'pagination' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'total' => $events->total(),
            ]
        ]);
    }

    public function eventDetail($id)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        $event = Event::findOrFail($id);

        return view('admin.event.event_detail', compact('event', 'user', 'userDetail', 'role'));
    }

    public function creatEvent(EventRequest $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validated();

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                Log::info('Image uploaded with MIME type: ' . $image->getClientMimeType());
                Log::info('Image uploaded with file name: ' . $image->getClientOriginalName());

                $imagePath = $image->store('images', 'public');
                Log::info('Image stored at path: ' . $imagePath);
            }

            $event = new Event();
            $event->user_id = $user->id;
            $event->name = $validated['name'];
            $event->description = $validated['description'];
            $event->location = $validated['location'];
            $event->start_time = Carbon::parse($validated['start_time']);
            $event->end_time = Carbon::parse($validated['end_time']);
            $event->status = $validated['status'];
            $event->image = $imagePath;

            $event->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Event created successfully!',
                'event' => $event,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create event. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateEvent($id, EventRequest $request)
    {
        try {
            $event = Event::findOrFail($id);

            $validated = $request->validated();

            $imagePath = $event->image;

            if ($request->hasFile('image')) {
                if ($event->image && file_exists(public_path('storage/' . $event->image))) {
                    unlink(public_path('storage/' . $event->image));
                }

                $image = $request->file('image');
                $imagePath = $image->store('images', 'public');
            }

            $event->name = $validated['name'];
            $event->description = $validated['description'];
            $event->location = $validated['location'];
            $event->start_time = Carbon::parse($validated['start_time']);
            $event->end_time = Carbon::parse($validated['end_time']);
            $event->status = $validated['status'];
            $event->image = $imagePath;

            $event->save();

            return response()->json(['res' => 'Event updated successfully!', 'event' => $event]);
        } catch (\Exception $e) {
            Log::error('Error updating event: ' . $e->getMessage());
            return redirect()->route('admin.event.edit', ['id' => $id])->with('error', 'Update failed.');
        }
    }

    public function deleteEvent($id)
    {
        try {
            $event = Event::findOrFail($id);

            $event->delete();
            return response()->json(['res' => 'Event deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage());
            return redirect()->route('admin.event')->with('error', 'Delete failed.');
        }
    }

    public function search(Request $request)
    {
        $search = $request->get('search', '');

        $events = Event::with(['user', 'user.userDetail'])
            ->whereHas('user.userDetail', function ($query) use ($search) {
                $query->where('full_name', 'like', '%' . $search . '%');
            })
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->orWhere('location', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%')
            ->paginate(10);

        return response()->json([
            'events' => $events->items(),
            'pagination' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'total' => $events->total(),
            ]
        ]);
    }

    public function formEventList(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $userDetail = $user->userDetail;
        $events = Event::with(['user', 'user.userDetail'])
            ->paginate(10);
        return view('admin.event.index', compact('user', 'userDetail', 'events', 'role'));
    }

    public function formCreateEvent()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        return view('admin.event.createEvent', compact('user', 'userDetail', 'role'));
    }

    public function formEditEvent($id)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        $event = Event::findOrFail($id);


        return view('admin.event.editEvent', compact('user', 'userDetail', 'role', 'event'));
    }


    //with role user

    public function searchEvent(Request $request)
    {
        $search = $request->get('search', '');
        $user = Auth::user();

        $events = Event::with(['user', 'user.userDetail'])
            ->where('status', 'approved')
            ->where(function ($query) use ($search) {
                $query->whereHas('user.userDetail', function ($query) use ($search) {
                    $query->where('full_name', 'like', '%' . $search . '%');
                })
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        $registeredEvents = EventRegistration::where('user_id', $user->id)
            ->where('status', 'registered')
            ->pluck('event_id')
            ->toArray();

        $eventsItems = $events->items();
        $eventsItem = collect($eventsItems)->map(function ($event) use ($registeredEvents) {
            $event->status_registration = in_array($event->id, $registeredEvents) ? 'registered' : 'canceled';
            return $event;
        });

        return response()->json([
            'events' => $eventsItem,
            'pagination' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'total' => $events->total(),
            ]
        ]);
    }

    public function getAllEventOfUser()
    {
        $events = Event::where('status', 'approved')
            ->with(['user', 'user.userDetail'])
            ->paginate(10);

        $eventsItems = $events->items();

        return response()->json([
            'events' => $eventsItems,
            'pagination' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'total' => $events->total(),
            ]
        ]);
    }

    public function formEventListOfUser(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $userDetail = $user->userDetail;

        $events = Event::where('status', 'approved')
            ->with(['user', 'user.userDetail'])
            ->paginate(10);

        // dd($events);
        return view('user.event.index', compact('user', 'userDetail', 'events', 'role'));
    }

    public function eventDetailOfUser($id)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        $event = Event::findOrFail($id);

        $isRegistered = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('status', 'registered')
            ->exists();

        return view('user.event.event_detail', compact('event', 'user', 'userDetail', 'role', 'isRegistered'));
    }
}
