<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
         $meeting_info = Meeting::where('meetings.id', $id)->leftJoin('tasks', 'tasks.id', '=', 'meetings.task_id')
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

        $task_id = $request->task_id_3;
        $task_info = Task::find($task_id);

        $data = array(
            'task_name' => $task_info->task_name,
            'task_description' => $task_info->task_description,
            'assigned_by' => $task_info->assigned_by,
            'delivery_date' => $task_info->reschedule_delivery_date,
            'meeting_link' => $request->meeting_link,
            'meeting_date' => $request->meeting_date,
            'meeting_time' => $request->meeting_time,
        );

        $invited_by = $meeting->invited_by;
        $invited_to = $meeting->invited_to;

        $emails = array($invited_by, $invited_to);

        Mail::send('emails.task_meeting_notification', $data, function($message) use($emails)
        {
            $message
                ->to($emails)
                ->subject('Meeting Schedule Notification');
        });

        \Session::flash('message', 'Meeting Update Successful!');

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
        $invited_by = Auth::user()->email;
        $invited_to = $request->invite_to;
        $task_id = $request->task_id;
        $meeting_date = $request->meeting_date;
        $meeting_time = $request->meeting_time;
        $meeting_link = $request->meeting_link;

        $meeting = new Meeting();

        $meeting->task_id = $task_id;
        $meeting->meeting_date = $meeting_date;
        $meeting->meeting_time = $meeting_time;
        $meeting->invited_by = $invited_by;
        $meeting->invited_to = $invited_to;
        $meeting->meeting_link = $meeting_link;
        $meeting->status = 1;

        $meeting->save();

        $task_info = Task::find($task_id);

        $data = array(
            'task_name' => $task_info->task_name,
            'task_description' => $task_info->task_description,
            'assigned_by' => $task_info->assigned_by,
            'delivery_date' => $task_info->reschedule_delivery_date,
            'meeting_link' => $meeting_link,
            'meeting_date' => $meeting_date,
            'meeting_time' => $meeting_time,
        );

        $emails = array($invited_by, $invited_to);

        Mail::send('emails.task_meeting_notification', $data, function($message) use($emails)
        {
            $message
                ->to($emails)
                ->subject('Meeting Schedule Notification');
        });

        return 'done';
    }

    public function destroy(Meeting $meeting)
    {

    }
}