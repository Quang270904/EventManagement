<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = Role::whereNotIn('role_name', ['admin', 'event_manager'])->get();

        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request): RedirectResponse
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
}
