<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function users(){
        $users = User::all();

        return view('users')->with('users', $users);
    }

    public function editUser($id){
        $user_info = User::find($id);

        return view('edit_user')->with('user_info', $user_info);
    }

    public function updateUser(Request $request, $id){
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'access_level' => 'required',
            'assign_task_access' => 'required',
            'status' => 'required',
        ]);

        $user_info = User::find($id);
        $user_info->name = $request->name;
        $user_info->email = $request->email;
        $user_info->access_level = $request->access_level;
        $user_info->assign_task_access = $request->assign_task_access;
        $user_info->status = $request->status;
        $user_info->save();

        \Session::flash('message', 'User Info Update Successful!');

        return redirect()->back();

    }

}
