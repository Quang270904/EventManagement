<?php

namespace App\Http\Controllers\EventManagerPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EventManagerController extends Controller
{

    public function getAllEventManager(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        $roleUser = Role::where('role_name', 'event_manager')->first();

        if (!$roleUser) {
            $allUserDetails = collect();
        } else {
            $allUserDetails = UserDetail::whereHas('user.role', function ($query) use ($roleUser) {
                $query->where('role_id', $roleUser->id);
            })
                ->with('user')
                ->where(function ($query) use ($search) {
                    if ($search) {
                        $query->where('full_name', 'like', "%$search%")
                            ->orWhere('address', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%");
                    }
                })
                ->paginate(10);
        }

        return view('admin.event_manager.index', compact('user', 'role', 'userDetail', 'allUserDetails'));
    }

    public function formCreateEventManager()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        return view('admin.event_manager.createEventManager', compact('user', 'userDetail', 'role'));
    }

    public function createEventManager(UserRequest $request)
    {
        try {

            $role = Role::firstOrCreate(['role_name' => 'event_manager']);

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role->id,
            ]);

            UserDetail::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('admin.eventManager')->with('success',  'Register successfully');
        } catch (\Exception $e) {
            Log::error('Error registering user: ' . $e->getMessage());
            return redirect()->route('admin.user.create')->with('error', 'Register faild');
        }
    }

    public function formEditEventManager($id)
    {
        $user = User::findOrFail($id);
        $userDetail = $user->userDetail;
        $role = $user->role;

        return view('admin.event_manager.editEventManager', compact('user', 'userDetail', 'role'));
    }

    public function updateEventManager($id, UpdateUserRequest $request)
    {
        try {
            $user = User::findOrFail($id);

            $user->update([
                'email' => $request->email,
                'role_id' => $user->role_id,
            ]);

            $user->userDetail->update([
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'gender' => $request->gender,
                'dob' => $request->dob,
            ]);

            return redirect()->route('admin.eventManager')->with('success', 'EventManager updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating eventManager: ' . $e->getMessage());
            return redirect()->route('admin.eventManager.edit', ['id' => $id])->with('error', 'Update failed');
        }
    }

    public function deleteEventManager($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->userDetail()->delete();
            $user->delete();
            return redirect()->route('admin.eventManager')->with('success', 'EventManager deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->route('admin.dashboard.eventManager')->with('error', 'Delete failed');
        }
    }
}
