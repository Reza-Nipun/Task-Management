<?php

namespace App\Http\Controllers;

use App\RecurringTask;
use App\RecurringTaskDetail;
use Illuminate\Http\Request;
use App\Task;
use App\Meeting;
use Illuminate\Support\Facades\DB;

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
            'task_id' => $task_id,
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

            $emails = array($assigned_to);

            if($t->change_count > 3){
                array_push($emails, $assigned_by);
            }

            Mail::send('emails.task_reminder_notification', $data, function($message) use($emails)
            {
                $message
                    ->to($emails)
                    ->subject('Reminder to Delivery Task');
            });

        }

    }

    public function autoMailHalfwayDeliveryDateTasksNotification(){
        $date = date('Y-m-d');

        $task_info = DB::select("SELECT A.*, round(A.date_difference/2) AS half_of_date_difference, 
                    DATE_ADD(A.reschedule_delivery_date, INTERVAL -round(A.date_difference/2) DAY) AS middle_date 
                    FROM 
                    (SELECT *, DATEDIFF(reschedule_delivery_date, assign_date) AS date_difference FROM `tasks` WHERE status=2) AS A
                    WHERE A.date_difference > 4");


        foreach ($task_info AS $t){

            $assigned_to = $t->assigned_to;
            $assigned_by = $t->assigned_by;

            if($date == $t->middle_date){
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


                $emails = array($assigned_to);

                if($t->change_count > 3){
                    array_push($emails, $assigned_by);
                }

                Mail::send('emails.task_reminder_notification', $data, function($message) use($emails)
                {
                    $message
                        ->to($emails)
                        ->subject('Reminder to Delivery Task');
                });
            }

        }
    }

    public function autoRescheduleDeliveryDateTasks(){
        $date = date('Y-m-d');

        $task_info = Task::where('status', '=', 2)->where('reschedule_delivery_date', '<', $date)->get();

        foreach ($task_info AS $t){

            $task = Task::find($t->id);
            $task->change_count = ($task->change_count != null ? $task->change_count : 0) + 1;
            $task->reschedule_delivery_date = "$date";
            $task->save();

//            $assigned_to = $t->assigned_to;
//            $assigned_by = $t->assigned_by;
//
//            $data = array(
//                'task_id' => $t->id,
//                'task_name' => $t->task_name,
//                'task_description' => $t->task_description,
//                'assigned_by' => $t->assigned_by,
//                'delivery_date' => $t->delivery_date,
//                'reschedule_delivery_date' => $task->reschedule_delivery_date,
//                'change_count' => $task->change_count,
//                'remarks' => $t->remarks,
//                'system_message' => 'N.B. - As the task was not completed or rescheduled on the target date, so system has rescheduled the task automatically.',
//            );
//
//            Mail::send('emails.task_reschedule_notification', $data, function($message) use($assigned_to, $assigned_by)
//            {
//                $message
//                    ->to($assigned_to)
//                    ->cc($assigned_by)
//                    ->subject('Task Auto-Reschedule Delivery Date');
//            });
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
                    'meeting_id' => $m->id,
                    'task_id' => $task_id,
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

                if($invited_by <> $invited_to){

                    $emails = array($invited_by, $invited_to);

                    Mail::send('emails.meeting_reminder_notification', $data, function ($message) use ($emails) {
                        $message
                            ->to($emails)
                            ->subject('Meeting Schedule Notification');
                    });

                }

        }
    }

    public function reschedulingMeetingFromEmail($meeting_id){

        $meeting_info = Meeting::find($meeting_id);

        if($meeting_info->status == 1){
            return view('reschedule_meeting')->with('meeting_info', $meeting_info);
        } elseif ($meeting_info->status == 2){
            echo '<h1 style="color: green;">Meeting is already completed!</h1>';
        } elseif ($meeting_info->status == 0){
            echo '<h1 style="color: red;">Meeting is already terminated!</h1>';
        }

    }

    public function reschedulingMeeting(Request $request, $meeting_id){
        $this->validate(request(), [
            'meeting_date' => 'required|date',
            'meeting_time' => 'required|regex:/(\d+\:\d+)/',
        ]);

        $meeting = Meeting::find($meeting_id);
        $meeting->meeting_date = $request->meeting_date;
        $meeting->meeting_time = $request->meeting_time;
        $meeting->meeting_link = $request->meeting_link;
        $meeting->save();

        $task_info = Task::find($meeting->task_id);

        $data = array(
            'meeting_id' => $meeting_id,
            'task_id' => $meeting->task_id,
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

        Mail::send('emails.meeting_reschedule_notification', $data, function($message) use($emails)
        {
            $message
                ->to($emails)
                ->subject('Meeting Reschedule Notification');
        });

        \Session::flash('message', 'Meeting Reschedule Successful!');

        return redirect()->back();

    }

    public function autoRecurringMonthlyTask(){
        $current_date = date('Y-m-d');

        $monthly_recurring_tasks = RecurringTask::where('recurring_type', 0)->where('status', 1)->get();

        foreach ($monthly_recurring_tasks as $monthly_recurring_task){
            $monthly_recurring_task_id = $monthly_recurring_task->id;

            $monthly_recurring_task_detail = RecurringTaskDetail::where('recurring_task_id', $monthly_recurring_task_id)
                                                ->selectRaw("MAX(recurring_date) as max_recurring_date")
                                                ->get();

            $max_recurring_date = $monthly_recurring_task_detail[0]->max_recurring_date;

            if($max_recurring_date != ''){

                if($current_date>$max_recurring_date){

                    if($monthly_recurring_task->last_date_of_month == 0){
                        $dt_formating = date("Y-m", strtotime($max_recurring_date));;
                        $dt = $dt_formating.'-'.$monthly_recurring_task->monthly_recurring_date;
                        $date = date('Y-m-d', strtotime('+1 month', strtotime($dt)));

                        $new_monthly_recurring_task = new RecurringTaskDetail();
                        $new_monthly_recurring_task->recurring_task_id = $monthly_recurring_task_id;
                        $new_monthly_recurring_task->recurring_date = $date;
                        $new_monthly_recurring_task->status = 2;
                        $new_monthly_recurring_task->save();
                    }

                    if($monthly_recurring_task->last_date_of_month == 1){
                        $date = date("Y-m-t");

                        $new_monthly_recurring_task = new RecurringTaskDetail();
                        $new_monthly_recurring_task->recurring_task_id = $monthly_recurring_task_id;
                        $new_monthly_recurring_task->recurring_date = $date;
                        $new_monthly_recurring_task->status = 2;
                        $new_monthly_recurring_task->save();
                    }
                }

            }else{
                if($monthly_recurring_task->last_date_of_month == 0){
                    $date = date('Y-m').'-'.$monthly_recurring_task->monthly_recurring_date;

                    $new_monthly_recurring_task = new RecurringTaskDetail();
                    $new_monthly_recurring_task->recurring_task_id = $monthly_recurring_task_id;
                    $new_monthly_recurring_task->recurring_date = $date;
                    $new_monthly_recurring_task->status = 2;
                    $new_monthly_recurring_task->save();
                }

                if($monthly_recurring_task->last_date_of_month == 1){
                    $date = date("Y-m-t");

                    $new_monthly_recurring_task = new RecurringTaskDetail();
                    $new_monthly_recurring_task->recurring_task_id = $monthly_recurring_task_id;
                    $new_monthly_recurring_task->recurring_date = $date;
                    $new_monthly_recurring_task->status = 2;
                    $new_monthly_recurring_task->save();
                }
            }
        }

        return response()->json('success', 200);
    }

    public function autoRecurringWeeklyTask(){
        $current_date = date('Y-m-d');

        $weekly_recurring_tasks = RecurringTask::where('recurring_type', 1)->where('status', 1)->get();

        foreach ($weekly_recurring_tasks as $weekly_recurring_task){
            $weekly_recurring_task_id = $weekly_recurring_task->id;
            $weekly_recurring_day = $weekly_recurring_task->weekly_recurring_day;

            $is_exist = RecurringTaskDetail::where('recurring_task_id', $weekly_recurring_task_id)->get();

            if(sizeof($is_exist)==0){

                $date = date('Y-m-d', strtotime("next $weekly_recurring_day"));

                $weekly_recurring_task = new RecurringTaskDetail();
                $weekly_recurring_task->recurring_task_id = $weekly_recurring_task_id;
                $weekly_recurring_task->recurring_date = $date;
                $weekly_recurring_task->status = 2;
                $weekly_recurring_task->save();

            }else{
                if($current_date >= $is_exist[0]->recurring_date){
                    $date = date('Y-m-d', strtotime("next $weekly_recurring_day"));

                    $is_already_exist_task = RecurringTaskDetail::where('recurring_task_id', $weekly_recurring_task_id)->where('recurring_date', $date)->get();

                    if(sizeof($is_already_exist_task) == 0){
                        $weekly_recurring_task = new RecurringTaskDetail();
                        $weekly_recurring_task->recurring_task_id = $weekly_recurring_task_id;
                        $weekly_recurring_task->recurring_date = $date;
                        $weekly_recurring_task->status = 2;
                        $weekly_recurring_task->save();
                    }

                }

            }

        }

        return response()->json('success', 200);
    }

    public function testMail(){

        for ($i=1; $i<=20; $i++){

        $emails = array('nipun.sarker@interfabshirt.com', 'nipunsarker56@gmail.com');

        Mail::send('welcome', [], function($message) use($emails)
        {
            $message
                ->to($emails)
                ->subject('Task Manager - Test Mail');
        });

        }
    }
}
