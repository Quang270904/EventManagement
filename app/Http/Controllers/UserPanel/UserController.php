<?php

namespace App\Http\Controllers\UserPanel;

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

class UserController extends Controller
{

    public function __construct() {}

    public function index()
    {
        return view('user.dashboard');
    }

    public function getAllUser(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;

        $allUserDetails = UserDetail::with('user')
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('full_name', 'like', "%$search%")
                        ->orWhere('address', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                }
            })
            ->paginate(10);

        return view('admin.user.index', compact('user', 'role', 'userDetail', 'allUserDetails'));
    }


    public function showFormCreateUser()
    {
        $user = Auth::user();
        $roles = Role::all();  
        $userDetail = $user->userDetail;
        $role = $user->role;
        return view('admin.user.createUser', compact('user', 'userDetail', 'role','roles'));
    }

    //User

    public function createUser(UserRequest $request)
    {
        try {
            $role = Role::findOrFail($request->role_id);

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

            return redirect()->route('admin.user')->with('success', 'Register successfully');
        } catch (\Exception $e) {
            Log::error('Error registering user: ' . $e->getMessage());
            return redirect()->route('admin.user.create')->with('error', 'Register failed');
        }
    }

    public function showFormEditUser($id)
    {
        $user = User::findOrFail($id);
        $userDetail = $user->userDetail;
        $role = $user->role;

        return view('admin.user.editUser', compact('user', 'userDetail', 'role'));
    }

    public function updateUser($id, UpdateUserRequest $request)
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
                'updated_at' => now(),
            ]);

            return redirect()->route('admin.user')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->route('admin.user.edit', ['id' => $id])->with('error', 'Update failed');
        }
    }


    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->userDetail()->delete();
            $user->delete();
            return redirect()->route('admin.user')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->route('admin.user')->with('error', 'Delete failed');
        }
    }
}
