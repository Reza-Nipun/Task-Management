<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\Meeting;

use Illuminate\Support\Facades\Mail;

class AccessFromEmail extends Controller
{
    public function rescheduleTaskFromEmail($task_id){

        $task_info = Task::find($task_id);

        if($task_info->status == 2){
            return view('reschedule_task')->with('task_info', $task_info);
        } elseif ($task_info->status == 1){
            echo '<h1 style="color: green;">Task is already completed!</h1>';
        } elseif ($task_info->status == 0){
            echo '<h1 style="color: red;">Task is already terminated!</h1>';
        }

    }

    public function rescheduleTaskDeliveryDate(Request $request, $task_id){
        $reschedule_date = date('Y-m-d', strtotime($request->reschedule_date));

        $task = Task::find($task_id);

        $task->change_count = ($task->change_count != null ? $task->change_count : 0) + 1;
        $task->reschedule_delivery_date = "$reschedule_date";
        $task->save();

        $task_name = $task->task_name;
        $task_description = $task->task_description;
        $assigned_to = $task->assigned_to;
        $assigned_by = $task->assigned_by;
        $delivery_date = $task->delivery_date;
        $reschedule_delivery_date = $task->reschedule_delivery_date;
        $change_count = $task->change_count;
        $remarks = $task->remarks;

        $data = array(
            'task_name' => $task_name,
            'task_description' => $task_description,
            'assigned_by' => $assigned_by,
            'delivery_date' => $delivery_date,
            'reschedule_delivery_date' => $reschedule_delivery_date,
            'change_count' => $change_count,
            'remarks' => $remarks,
        );

        $emails = array($assigned_by, $assigned_to);

        Mail::send('emails.task_reschedule_notification', $data, function($message) use($emails)
        {
            $message
                ->to($emails)
                ->subject('Task Reschedule Delivery Date');
        });

        \Session::flash('message', 'Task Reschedule Successful!');

        return redirect()->back();
    }

    public function autoMailDeliveryDateTasksNotification(){
        $date = date('Y-m-d');
        $tomorrow_date = date('Y-m-d', strtotime($date . "+1 days"));

        $task_info = Task::where('status', '=', 2)->where('reschedule_delivery_date', '<=', $tomorrow_date)->get();

        foreach ($task_info AS $t){

            $assigned_to = $t->assigned_to;
            $assigned_by = $t->assigned_by;

            $data = array(
                'task_id' => $t->id,
                'task_name' => $t->task_name,
                'task_description' => $t->task_description,
                'assigned_by' => $t->assigned_by,
                'delivery_date' => $t->delivery_date,
                'reschedule_delivery_date' => $t->reschedule_delivery_date,
                'change_count' => $t->change_count,
                'remarks' => $t->remarks,
            );

            Mail::send('emails.task_reminder_notification', $data, function($message) use($assigned_to, $assigned_by)
            {
                $message
                    ->to($assigned_to)
                    ->cc($assigned_by)
                    ->subject('Reminder to Delivery Task');
            });

        }

    }

    public function autoMailMeetingNotification()
    {
        $date = date('Y-m-d');

        $meeting = Meeting::where('status', 1)->where('meeting_date', '<=', $date)->get();

        foreach ($meeting AS $m) {

                $task_id = $m->task_id;
                $task_info = Task::find($task_id);

                $data = array(
                    'task_name' => $task_info->task_name,
                    'task_description' => $task_info->task_description,
                    'assigned_by' => $task_info->assigned_by,
                    'delivery_date' => $task_info->reschedule_delivery_date,
                    'meeting_link' => $m->meeting_link,
                    'meeting_date' => $m->meeting_date,
                    'meeting_time' => $m->meeting_time,
                );

                $invited_by = $m->invited_by;
                $invited_to = $m->invited_to;

                $emails = array($invited_by, $invited_to);

                Mail::send('emails.meeting_reminder_notification', $data, function ($message) use ($emails) {
                    $message
                        ->to($emails)
                        ->subject('Meeting Schedule Notification');
                });

        }
    }
}
