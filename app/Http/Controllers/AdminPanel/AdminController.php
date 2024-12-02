<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $role = $user->role;
        return view('admin.dashboard.home.layout', compact('user', 'userDetail', 'role'));
    }
}
