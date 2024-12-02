<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function __construct() {}

    public function getAllEvent(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        $events = Event::query();

        if ($search) {
            $events->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('location', 'like', '%' . $search . '%');
        }

        $events = $events->paginate(10);

        foreach ($events as $event) {
            $event->start_time = Carbon::parse($event->start_time);
            $event->end_time = Carbon::parse($event->end_time);
        }
        return view('admin.event.index', compact('user', 'role', 'events', 'userDetail'));
    }

    public function getManagerEvents(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        if ($role->role_name != 'event_manager') {
            return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to view these events');
        }

        $events = Event::query();

        $events->where('user_id', $user->id);

        if ($search) {
            $events->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        $events = $events->paginate(10);

        foreach ($events as $event) {
            $event->start_time = Carbon::parse($event->start_time);
            $event->end_time = Carbon::parse($event->end_time);
        }

        return view('event_managers.event.index', compact('user', 'role', 'events', 'userDetail'));
    }

    public function eventDetail($id)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        $event = Event::findOrFail($id);

        return view('admin.event.event_detail', compact('event', 'user', 'userDetail', 'role'));
    }

    public function formCreateEvent()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        return view('admin.event.createEvent', compact('user', 'userDetail', 'role'));
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

            $event = Event::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'start_time' => Carbon::parse($validated['start_time']),
                'end_time' => Carbon::parse($validated['end_time']),
                'status' => $validated['status'],
                'image' => $imagePath,
            ]);

            return redirect()->route('admin.event')->with('success', 'Event created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());
            return redirect()->route('admin.event.create')->with('error', 'Failed to create event.');
        }
    }

    public function formEditEvent($id)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        $event = Event::findOrFail($id);


        return view('admin.event.editEvent', compact('user', 'userDetail', 'role', 'event'));
    }

    public function updateEvent($id, EventRequest $request)
    {
        try {
            $user = Auth::user();
            $event = Event::findOrFail($id);

            $validated = $request->validated();

            $imagePath = $event->image;
            if ($request->hasFile('image')) {
                if ($event->image && file_exists(public_path('storage/' . $event->image))) {
                    unlink(public_path('storage/' . $event->image));
                }

                $image = $request->file('image');
                $imagePath = $image->store('events_images', 'public');
            }
            $event->update([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'start_time' => Carbon::parse($validated['start_time']),
                'end_time' => Carbon::parse($validated['end_time']),
                'status' => $validated['status'],
                'image' => $imagePath,
            ]);

            return redirect()->route('admin.event')->with('success', 'Event updated successfully!');
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

            return redirect()->route('admin.event')->with('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage());
            return redirect()->route('admin.event')->with('error', 'Delete failed.');
        }
    }
}
