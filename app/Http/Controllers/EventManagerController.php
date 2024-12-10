<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventOfManagerRequest;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventManagerController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        return view('event_managers.dashboard.home.layout', compact('user', 'userDetail', 'role'));
    }

    public function eventDetail($id)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        $event = Event::findOrFail($id);

        return view('event_managers.event.event_detail', compact('event', 'user', 'userDetail', 'role'));
    }

    public function searchEventOfManager(Request $request)
    {
        $search = $request->get('search', '');
        $user = Auth::user();

        $events = Event::with(['user', 'user.userDetail'])
            ->where('user_id', $user->id)
            ->where(function ($query) use ($search) {
                $query->whereHas('user.userDetail', function ($query) use ($search) {
                    $query->where('full_name', 'like', '%' . $search . '%');
                })
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            })
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

    public function getAllEventOfManager()
    {
        $user = Auth::user();

        $events = Event::query();

        $events->where('user_id', $user->id);

        $eventsData = $events->get();

        return response()->json(['events' => $eventsData]);
    }

    public function creatEventOfManager(EventOfManagerRequest $request)
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
            $event->status = 'pending';
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

    public function updateEventOfManager($id, EventOfManagerRequest $request)
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

            return redirect()->route('event_manager.event')->with('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage());
            return redirect()->route('event_manager.event')->with('error', 'Delete failed.');
        }
    }

    public function formEventListOfManager(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $userDetail = $user->userDetail;
        $events = Event::query();
        $events->where('user_id', $user->id);

        $eventsData = $events->paginate(10);
        // dd($eventsData);
        return view('event_managers.event.index', compact('user', 'userDetail', 'eventsData', 'role'));
    }

    public function formCreateEventOfManager()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        return view('event_managers.event.createEvent', compact('user', 'userDetail', 'role'));
    }

    public function formEditEventOfManager($id)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        $event = Event::findOrFail($id);


        return view('event_managers.event.editEvent', compact('user', 'userDetail', 'role', 'event'));
    }
}
