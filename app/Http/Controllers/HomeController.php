<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\Meeting;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $email = Auth::user()->email;

        $my_pending_task_count = Task::where('status', 2)->where('assigned_to', $email)->count();
        $assigned_pending_task_count = Task::where('status', 2)->where('assigned_by', $email)->count();
        $meeting_pending_count = Meeting::where(function ($query) use ($email) {
                                    return $query->where('invited_by', '=', $email)
                                        ->orWhere('invited_to', '=', $email);
                                })->where(function ($query) {
                                    return $query->where('meetings.status', '=', 1);
                                })->count();

        return view('home')->with(['my_pending_task_count'=>$my_pending_task_count,
                                   'assigned_pending_task_count'=>$assigned_pending_task_count,
                                   'meeting_pending_count'=>$meeting_pending_count,]);
    }
}
