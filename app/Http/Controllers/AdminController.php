<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::paginate(100);

        return view('admin.index', compact('users'));
    }

    public function login($user_id)
    {
        Auth::loginUsingId($user_id);

        return redirect()->route('workers.index')->with('success', 'Logged in');
    }
}
