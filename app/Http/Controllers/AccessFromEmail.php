<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

use Illuminate\Support\Facades\Mail;

class AccessFromEmail extends Controller
{
    public function rescheduleTaskFromEmail($task_id){

        $task_info = Task::find($task_id);

        return view('reschedule_task')->with('task_info', $task_info);

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
}
