<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function __construct() {}

    public function getAllUser()
    {
        $users = User::with(['userDetail', 'role'])->paginate(10);

        return response()->json([
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    public function formUserList(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $userDetail = $user->userDetail;
        $users = User::with(['userDetail', 'role'])->get();
        return view('admin.user.index', compact('user', 'userDetail', 'users', 'role'));
    }

    public function formUserDetail($id)
    {
        try {
            $user = Auth::user();
            $userDetail = $user->userDetail;
            $users = User::with(['userDetail', 'role'])->findOrFail($id);

            // dd($users);
            return view('admin.user.userDetail', compact('user', 'userDetail', 'users'));
        } catch (\Exception $e) {
            Log::error("Error fetching user details: " . $e->getMessage());
            return response()->json(['error' => 'User not found or other error'], 500);
        }
    }

    public function createUser(UserRequest $request)
    {
        $validatedData = $request->validated();

        $user = new User();
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->role_id = $validatedData['role_id'];
        $user->save();

        $userDetail = new UserDetail();
        $userDetail->user_id = $user->id;
        $userDetail->full_name = $validatedData['full_name'];
        $userDetail->address = $validatedData['address'];
        $userDetail->phone = $validatedData['phone'];
        $userDetail->gender = $validatedData['gender'];
        $userDetail->dob = $validatedData['dob'];
        $userDetail->save();

        return  response()->json(['res' => 'User create successfully!', 'user' => $user]);
    }

    public function updateUser($id, UserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::findOrFail($id);
        $userDetail = $user->userDetail;

        $user->email = $validatedData['email'];
        $user->role_id = $validatedData['role_id'];

        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->save();

        $userDetail->full_name = $validatedData['full_name'];
        $userDetail->address = $validatedData['address'];
        $userDetail->phone = $validatedData['phone'];
        $userDetail->gender = $validatedData['gender'];
        $userDetail->dob = $validatedData['dob'];
        $userDetail->save();

        return response()->json(['res' => 'User updated successfully!', 'user' => $user]);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->userDetail()->delete();
        $user->delete();

        return response()->json(['res' => 'User deleted successfully!']);
    }

    public function search(Request $request)
    {
        $search = $request->get('search', '');

        $users = User::with(['userDetail', 'role'])
            ->whereHas('userDetail', function ($query) use ($search) {
                $query->where('full_name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            })
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhereHas('role', function ($query) use ($search) {
                $query->where('role_name', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return response()->json([
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    public function showFormCreateUser()
    {
        $user = Auth::user();
        $roles = Role::all();
        $userDetail = $user->userDetail;
        return view('admin.user.createUser', compact('user', 'userDetail', 'roles'));
    }

    public function showFormEditUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $userDetail = $user->userDetail;
        return view('admin.user.editUser', compact('user', 'userDetail', 'roles'));
    }

    // public function getUserById($id)
    // {
    //     $user = User::with(['userDetail', 'role'])->findOrFail($id);

    //     return response()->json([
    //         'user' => $user,
    //     ]);
    // }
}
