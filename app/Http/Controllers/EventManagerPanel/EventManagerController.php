<?php

namespace App\Http\Controllers\EventManagerPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventOfManagerRequest;
use App\Http\Requests\EventRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                'status' =>'pending',
                'image' => $imagePath,
            ]);

            return redirect()->route('event_managers.event')->with('success', 'Event created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());
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

            return redirect()->route('event_manager.event')->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating event: ' . $e->getMessage());
            return redirect()->route('event_manager.event.edit', ['id' => $id])->with('error', 'Update failed.');
        }
    }

    // public function getAllEventManager(Request $request)
    // {
    //     $search = $request->input('search');
    //     $user = Auth::user();
    //     $userDetail = $user->userDetail;
    //     $role = $user->role;

    //     $roleUser = Role::where('role_name', 'event_manager')->first();

    //     if (!$roleUser) {
    //         $allUserDetails = collect();
    //     } else {
    //         $allUserDetails = UserDetail::whereHas('user.role', function ($query) use ($roleUser) {
    //             $query->where('role_id', $roleUser->id);
    //         })
    //             ->with('user')
    //             ->where(function ($query) use ($search) {
    //                 if ($search) {
    //                     $query->where('full_name', 'like', "%$search%")
    //                         ->orWhere('address', 'like', "%$search%")
    //                         ->orWhere('phone', 'like', "%$search%");
    //                 }
    //             })
    //             ->paginate(10);
    //     }

    //     return view('admin.event_manager.index', compact('user', 'role', 'userDetail', 'allUserDetails'));
    // }

    // public function formCreateEventManager()
    // {
    //     $user = Auth::user();
    //     $userDetail = $user->userDetail;
    //     $role = $user->role;
    //     return view('admin.event_manager.createEventManager', compact('user', 'userDetail', 'role'));
    // }

    // public function createEventManager(UserRequest $request)
    // {
    //     try {

    //         $role = Role::firstOrCreate(['role_name' => 'event_manager']);

    //         $user = User::create([
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //             'role_id' => $role->id,
    //         ]);

    //         UserDetail::create([
    //             'user_id' => $user->id,
    //             'full_name' => $request->full_name,
    //             'phone' => $request->phone,
    //             'address' => $request->address,
    //             'gender' => $request->gender,
    //             'dob' => $request->dob,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);

    //         return redirect()->route('admin.eventManager')->with('success',  'Register successfully');
    //     } catch (\Exception $e) {
    //         Log::error('Error registering user: ' . $e->getMessage());
    //         return redirect()->route('admin.user.create')->with('error', 'Register faild');
    //     }
    // }

    // public function formEditEventManager($id)
    // {
    //     $user = User::findOrFail($id);
    //     $userDetail = $user->userDetail;
    //     $role = $user->role;

    //     return view('admin.event_manager.editEventManager', compact('user', 'userDetail', 'role'));
    // }

    // public function updateEventManager($id, UpdateUserRequest $request)
    // {
    //     try {
    //         $user = User::findOrFail($id);

    //         $user->update([
    //             'email' => $request->email,
    //             'role_id' => $user->role_id,
    //         ]);

    //         $user->userDetail->update([
    //             'full_name' => $request->full_name,
    //             'phone' => $request->phone,
    //             'address' => $request->address,
    //             'gender' => $request->gender,
    //             'dob' => $request->dob,
    //         ]);

    //         return redirect()->route('admin.eventManager')->with('success', 'EventManager updated successfully');
    //     } catch (\Exception $e) {
    //         Log::error('Error updating eventManager: ' . $e->getMessage());
    //         return redirect()->route('admin.eventManager.edit', ['id' => $id])->with('error', 'Update failed');
    //     }
    // }

    // public function deleteEventManager($id)
    // {
    //     try {
    //         $user = User::findOrFail($id);
    //         $user->userDetail()->delete();
    //         $user->delete();
    //         return redirect()->route('admin.eventManager')->with('success', 'EventManager deleted successfully');
    //     } catch (\Exception $e) {
    //         Log::error('Error deleting user: ' . $e->getMessage());
    //         return redirect()->route('admin.dashboard.eventManager')->with('error', 'Delete failed');
    //     }
    // }
}
