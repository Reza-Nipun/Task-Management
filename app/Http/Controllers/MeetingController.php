<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Meeting;


class MeetingController extends Controller
{
    public function index()
    {
        $email = Auth::user()->email;

        $meetings = Meeting::where(function ($query) use ($email) {
                    return $query->where('invited_by', '=', $email)
                        ->orWhere('invited_to', '=', $email);
                })->where(function ($query) {
                    return $query->where('meetings.status', '=', 1);
                })
            ->leftJoin('tasks', 'tasks.id', '=', 'meetings.task_id')
            ->select('meetings.*','tasks.task_name', 'tasks.task_description', 'tasks.reschedule_delivery_date', 'tasks.change_count')
            ->get();

        return view('meetings')->with('meetings', $meetings);
    }

    public function checkPendingMeeting($task_id){
        $meeting = Meeting::where('task_id', $task_id)->where('status', '=', 1)->get();

        return response()->json($meeting);
    }

    public function editMeeting($id){
         $meeting_info = Meeting::find($id)->leftJoin('tasks', 'tasks.id', '=', 'meetings.task_id')
            ->select('meetings.*','tasks.task_name', 'tasks.task_description', 'tasks.assigned_by', 'tasks.reschedule_delivery_date', 'tasks.change_count')
            ->get();

        if($meeting_info[0]->status == 1){

            $email = Auth::user()->email;
            $assigned_by = $meeting_info[0]->assigned_by;

            $take_action_on_task = 0;
            if($email == $assigned_by){
                $take_action_on_task = 1;
            }

            return view('edit_meeting')->with(['meeting_info' => $meeting_info , 'take_action_on_task' => $take_action_on_task]);

        }else{
            return redirect('meetings');
        }
    }

    public function updateMeeting(Request $request, $meeting_id){

        $this->validate(request(), [
            'meeting_date' => 'required',
            'meeting_time' => 'required',
            'meeting_status' => 'required',
        ]);

        $meeting = Meeting::find($meeting_id);

        $meeting->meeting_date = $request->meeting_date;
        $meeting->meeting_time = $request->meeting_time;
        $meeting->description = $request->meeting_description;
        $meeting->meeting_link = $request->meeting_link;
        $meeting->remarks = $request->remarks;
        $meeting->status = $request->meeting_status;

        $meeting->save();

        \Session::flash('message', 'Task Update Successful!');

        return redirect('meetings');

    }

    public function meetingComplete(Request $request){

        $meeting_id = $request->meeting_id;

        $meeting = Meeting::find($meeting_id);

        $meeting->status = 2;

        $meeting->save();

        echo 'done';
    }

    public function store(Request $request)
    {
        $meeting = new Meeting();

        $meeting->task_id = $request->task_id;
        $meeting->meeting_date = $request->meeting_date;
        $meeting->meeting_time = $request->meeting_time;
        $meeting->invited_by = Auth::user()->email;
        $meeting->invited_to = $request->invite_to;
        $meeting->meeting_link = $request->meeting_link;
        $meeting->status = 1;

        $meeting->save();

        return 'done';
    }


    public function destroy(Meeting $meeting)
    {

    }
}
