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

    public function formCreateEvent()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        return view('event_managers.event.createEvent', compact('user', 'userDetail', 'role'));
    }

    public function creatEvent(EventOfManagerRequest $request)
    {

        try {
            $user = Auth::user();

            $validated = $request->validated();

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Log::info('Image uploaded with MIME type: ' . $image->getClientMimeType());
                // Log::info('Image uploaded with file name: ' . $image->getClientOriginalName());

                $imagePath = $image->store('images', 'public');

                // Log::info('Image stored at path: ' . $imagePath);
            }

            $event = Event::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'start_time' => Carbon::parse($validated['start_time']),
                'end_time' => Carbon::parse($validated['end_time']),
                'status' =>'pending',
                'image' => $imagePath,
            ]);

            return redirect()->route('event_manager.event')->with('success', 'Event created successfully!');
        } catch (\Exception $e) {
            // Log::error('Error creating event: ' . $e->getMessage());
            return redirect()->route('event_manager.event.create')->with('error', 'Failed to create event.');
        }
    }

    public function formEditEvent($id)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        $event = Event::findOrFail($id);

        return view('event_managers.event.editEvent', compact('user', 'userDetail', 'role', 'event'));
    }

    public function updateEvent($id, EventOfManagerRequest $request)
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
                'image' => $imagePath,
            ]);

            return redirect()->route('event_manager.event')->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating event: ' . $e->getMessage());
            return redirect()->route('event_manager.event.edit', ['id' => $id])->with('error', 'Update failed.');
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
}
