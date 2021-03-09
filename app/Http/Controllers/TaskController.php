<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Importer;

use App\Task;
use App\Meeting;
use DB;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getTasks(){
        $assigned_by = Auth::user()->email;

        $assigned_to_emails = Task::where('assigned_by', $assigned_by)->where('status', 2)->groupBy('assigned_to')->get();
        $tasks = Task::where('assigned_by', $assigned_by)->where('status', 2)->orderBy('reschedule_delivery_date', 'asc')->get();

        return view('tasks')->with(['tasks'=>$tasks, 'assigned_to_emails'=>$assigned_to_emails]);
    }

    public function myTasks(){
        $assigned_to = Auth::user()->email;

        $assigned_by_emails = Task::where('assigned_to', $assigned_to)->where('status', 2)->groupBy('assigned_by')->get();
        $tasks = Task::where('assigned_to', $assigned_to)->where('status', 2)->orderBy('reschedule_delivery_date', 'asc')->get();

        return view('my_tasks')->with(['tasks' => $tasks, 'assigned_by_emails' => $assigned_by_emails]);
    }

    public function editTask($id){
        $task_info = Task::find($id);

        return view('edit_task')->with('task_info', $task_info);
    }

    public function updateTask(Request $request, $id){
        $this->validate(request(), [
            'task_name' => 'required',
            'assign_to' => 'required|email',
            'delivery_date' => 'required|',
        ]);

        $task_name = $request->task_name;
        $task_description = $request->task_description;
        $assigned_by = Auth::user()->email;
        $assigned_to = $request->assign_to;
        $assign_date = date('Y-m-d');
        $delivery_date = date('Y-m-d', strtotime($request->delivery_date));
        $remarks = $request->remarks;
        $status = 2;

        $task = Task::find($id);

        $task->task_name = $task_name;
        $task->task_description = $task_description;
        $task->assigned_by = $assigned_by;
        $task->assigned_to = $assigned_to;
        $task->assign_date = $assign_date;
        $task->delivery_date = $delivery_date;
        $task->reschedule_delivery_date = $delivery_date;
        $task->status = $status;
        $task->remarks = $remarks;
        $task->save();

        \Session::flash('message', 'Task Update Successful!');

        return redirect()->back();
    }

    public function completeAssignedTask(Request $request){
        $task_id = $request->task_id;

        $task = Task::find($task_id);

        $task->status = 1;
        $task->actual_complete_date = date('Y-m-d');
        $task->save();

        $meeting = DB::table('meetings')
                    ->where('task_id', $task_id)
                    ->where('status', 1)
                    ->update(['status' => 2]);

        echo 'done';
    }

    public function terminateAssignedTask(Request $request){
        $task_id = $request->task_id;

        $task = Task::find($task_id);

        $task->status = 0;
        $task->termination_date = date('Y-m-d');
        $task->save();

        $meeting = DB::table('meetings')
                ->where('task_id', $task_id)
                ->where('status', 1)
                ->update(['status' => 2]);

        echo 'done';
    }

    public function rescheduleTaskDeliveryDate(Request $request){
        $task_id = $request->task_id;
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

        echo 'done';
    }

    public function getAssignedTaskDetail(Request $request){
        $task_id = $request->task_id;

        $task_info = Task::find($task_id);

        return response()->json($task_info);
    }

    public function addTask(){
        return view('add_task');
    }

    public function saveTask(Request $request){
        $this->validate(request(), [
            'task_name' => 'required',
            'assign_to' => 'required|email',
            'delivery_date' => 'required|',
        ]);

        $task_name = $request->task_name;
        $task_description = $request->task_description;
        $assigned_by = Auth::user()->email;
        $assigned_to = $request->assign_to;
        $assign_date = date('Y-m-d');
        $delivery_date = date('Y-m-d', strtotime($request->delivery_date));
        $remarks = $request->remarks;
        $status = 2;

        $task = new Task();

        $task->task_name = $task_name;
        $task->task_description = $task_description;
        $task->assigned_by = $assigned_by;
        $task->assigned_to = $assigned_to;
        $task->assign_date = $assign_date;
        $task->delivery_date = $delivery_date;
        $task->reschedule_delivery_date = $delivery_date;
        $task->status = $status;
        $task->remarks = $remarks;
        $task->save();

        $task_id = $task->id;

        $data = array(
            'task_id' => $task_id,
            'task_name' => $task_name,
            'task_description' => $task_description,
            'assigned_by' => $assigned_by,
            'delivery_date' => $delivery_date,
            'remarks' => $remarks,
        );

        Mail::send('emails.task_assignment_notification', $data, function($message) use($assigned_to)
        {
            $message
                ->to($assigned_to)
                ->subject('New Task Assignment');
        });

        \Session::flash('message', 'Task Assignment Successful!');

        return redirect('/add_task');

    }

    public function uploadTaskFile(){
        return view('upload_task_csv');
    }

    public function uploadingTaskFile(Request $request){
        $this->validate(request(), [
            'upload_file'   => 'required|mimes:xls,xlsx',
        ]);

        $path = $request->file('upload_file')->getRealPath();

        $excel = Importer::make('Excel');
        $collection = $excel->load($path)->getCollection();

        if(sizeof($collection[1]) == 5)
        {
            $task = new Task();
            $assigned_by = Auth::user()->email;
            $assign_date = date('Y-m-d');

            $arr = json_decode($collection,true);
            foreach ($arr as $k => $row) {

                if($k > 0) {
                    $task_name = $row[0];
                    $task_description = $row[1];
                    $assigned_to = $row[2];
                    $delivery_date = date('Y-m-d', strtotime($row[3]['date']));
                    $remarks = $row[4];

                    if (filter_var($assigned_to, FILTER_VALIDATE_EMAIL)) {
                        echo("$assigned_to is a valid email address");
                    } else {
                        echo("$assigned_to is not a valid email address");
                    }

                    if(($task_name != '') && ($assigned_to != '') && (filter_var($assigned_to, FILTER_VALIDATE_EMAIL) == true) && ($delivery_date != '')){

                        $task->task_name = $task_name;
                        $task->task_description = $task_description;
                        $task->assigned_by = $assigned_by;
                        $task->assigned_to = $assigned_to;
                        $task->assign_date = $assign_date;
                        $task->delivery_date = $delivery_date;
                        $task->reschedule_delivery_date = $delivery_date;
                        $task->remarks = $remarks;
                        $task->status = 2;
                        $task->save();

                        $task_id = $task->id;

                        $data = array(
                            'task_id' => $task_id,
                            'task_name' => $task_name,
                            'task_description' => $task_description,
                            'assigned_by' => $assigned_by,
                            'delivery_date' => $delivery_date,
                            'remarks' => $remarks,
                        );

                        Mail::send('emails.task_assignment_notification', $data, function($message) use($assigned_to)
                        {
                            $message
                                ->to($assigned_to)
                                ->subject('New Task Assignment');
                        });
                    }
                }

            }


            \Session::flash('message', 'Tasks assignment successful!');

            return redirect('/upload_task_file');
        }else{
            \Session::flash('error_message', 'Please use the correct excel format!');

            return redirect('/upload_task_file');
        }

    }

    public function assignedTasksReport(){
        $assigned_by = Auth::user()->email;

        $assigned_to_emails = Task::where('assigned_by', $assigned_by)->groupBy('assigned_to')->get();

        return view('assigned_tasks_report')->with('assigned_to_emails', $assigned_to_emails);
    }

    public function getAssignedTaskReport(Request $request){
        $assigned_by = Auth::user()->email;

        $assigned_to = $request->assigned_to;
        $assigned_date_from = $request->assigned_date_from;
        $assigned_date_to = $request->assigned_date_to;
        $status = $request->status;

        $where = "";

        if($assigned_by != ''){
            $where .= " AND assigned_by='$assigned_by'";
        }

        if($assigned_to != ''){
            $where .= " AND assigned_to='$assigned_to'";
        }

        if($assigned_date_from != '' && $assigned_date_to != ''){
            $where .= " AND assign_date BETWEEN '$assigned_date_from' AND '$assigned_date_to'";
        }

        if($status != ''){
            $where .= " AND status=$status";
        }

        $tasks = DB::select( "SELECT * FROM tasks WHERE 1 $where ORDER BY reschedule_delivery_date ASC" );

        $new_row = '';

        foreach ($tasks as $k => $t){

            $datetime1 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->delivery_date);
            $datetime2 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->assign_date);
            $interval = $datetime1->diff($datetime2);
            $target_lead_time = $interval->format('%a');

            $actual_lead_time=0;
            if($t->actual_complete_date != '') {
                $datetime3 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->actual_complete_date);
                $datetime4 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->assign_date);
                $interval_1 = $datetime3->diff($datetime4);
                $actual_lead_time = $interval_1->format('%a');
            }

            $status = $t->status == 0 ? 'Terminated' : ($t->status == 1 ? 'Completed' : 'Pending');

            $new_row .= '<tr>';
            $new_row .= '<td class="text-center">'.($k+1).'</td>';
            $new_row .= '<td>'.$t->task_name.'</td>';
            $new_row .= '<td>'.$t->assigned_to.'</td>';
            $new_row .= '<td class="text-center">'.$t->assign_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->delivery_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->reschedule_delivery_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->change_count.'</td>';
            $new_row .= '<td class="text-center">'.$status.'</td>';
            $new_row .= '<td class="text-center">'.$target_lead_time.'</td>';
            $new_row .= '<td class="text-center">'.$actual_lead_time.'</td>';
            $new_row .= '<td class="text-center">'.$t->actual_complete_date.'</td>';
            $new_row .= '<td class="text-center">
                            <span class="btn btn-sm btn-primary" title="View" title="Task Detail" onclick="getAssignedTaskDetail( '.$t->id.' )">
                                <i class="fa fa-eye"></i>
                            </span>
                        </td>';
            $new_row .= '</tr>';

        }

        return $new_row;

    }

    public function getAssignedPendingTaskFilter(Request $request){
        $assigned_by = Auth::user()->email;

        $assigned_to = $request->assigned_to;
        $delivery_date_from = $request->delivery_date_from;
        $delivery_date_to = $request->delivery_date_to;
        $status = $request->status;

        $where = "";

        if($assigned_by != ''){
            $where .= " AND assigned_by='$assigned_by'";
        }

        if($assigned_to != ''){
            $where .= " AND assigned_to='$assigned_to'";
        }

        if($delivery_date_from != '' && $delivery_date_to != ''){
            $where .= " AND reschedule_delivery_date BETWEEN '$delivery_date_from' AND '$delivery_date_to'";
        }

        if($status != ''){
            $where .= " AND status=$status";
        }

        $tasks = DB::select( "SELECT * FROM tasks WHERE 1 $where ORDER BY reschedule_delivery_date ASC" );

        $new_row = '';

        foreach ($tasks as $k => $t){

            $datetime1 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->delivery_date);
            $datetime2 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->assign_date);
            $interval = $datetime1->diff($datetime2);
            $target_lead_time = $interval->format('%a');

            $actual_lead_time=0;
            if($t->actual_complete_date != '') {
                $datetime3 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->actual_complete_date);
                $datetime4 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->assign_date);
                $interval_1 = $datetime3->diff($datetime4);
                $actual_lead_time = $interval_1->format('%a');
            }

            $status = $t->status == 0 ? 'Terminated' : ($t->status == 1 ? 'Completed' : 'Pending');

            $new_row .= '<tr>';
            $new_row .= '<td class="text-center">'.($k+1).'</td>';
            $new_row .= '<td>'.$t->task_name.'</td>';
            $new_row .= '<td>'.$t->assigned_to.'</td>';
            $new_row .= '<td class="text-center">'.$t->assign_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->delivery_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->reschedule_delivery_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->change_count.'</td>';
            $new_row .= '<td class="text-center">'.$status.'</td>';
            $new_row .= '<td class="text-center">
                            <a class="btn btn-sm btn-warning" href="'.url('/edit_task/'.$t->id).'" title="Edit Task">
                                <i class="fa fa-edit"></i>
                            </a>
                            <span class="btn btn-sm btn-primary" title="View" title="Task Detail" onclick="getAssignedTaskDetail( '.$t->id.' )">
                                <i class="fa fa-eye"></i>
                            </span>
                            <span class="btn btn-sm btn-success" title="Meeting" onclick="fixMeeting('."'".$t->id."'".' , '."'".$t->assigned_to."'".');">
                                <i class="far fa-comments"></i>
                            </span>
                        </td>';
            $new_row .= '</tr>';

        }

        return $new_row;

    }

    public function taskConfirmation($task_id){
        $email = Auth::user()->email;

        $task_info = Task::find($task_id);
        $assigned_by = $task_info->assigned_by;

        $take_action_on_task = 0;
        if($email == $assigned_by){
            $take_action_on_task = 1;
        }

        return view('task_confirmation')->with(['task_info' => $task_info, 'take_action_on_task' => $take_action_on_task]);
    }

    public function myTasksReport(){
        $assigned_to = Auth::user()->email;

        $assigned_by_emails = Task::where('assigned_to', $assigned_to)->groupBy('assigned_by')->get();

        return view('my_tasks_report')->with('assigned_by_emails', $assigned_by_emails);
    }

    public function getMyTaskReport(Request $request){
        $assigned_to = Auth::user()->email;

        $assigned_by = $request->assigned_by;
        $assigned_date_from = $request->assigned_date_from;
        $assigned_date_to = $request->assigned_date_to;
        $status = $request->status;

        $where = "";

        if($assigned_by != ''){
            $where .= " AND assigned_by='$assigned_by'";
        }

        if($assigned_to != ''){
            $where .= " AND assigned_to='$assigned_to'";
        }

        if($assigned_date_from != '' && $assigned_date_to != ''){
            $where .= " AND assign_date BETWEEN '$assigned_date_from' AND '$assigned_date_to'";
        }

        if($status != ''){
            $where .= " AND status=$status";
        }

        $tasks = DB::select( "SELECT * FROM tasks WHERE 1 $where ORDER BY reschedule_delivery_date ASC" );

        $new_row = '';

        foreach ($tasks as $k => $t){

            $datetime1 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->delivery_date);
            $datetime2 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->assign_date);
            $interval = $datetime1->diff($datetime2);
            $target_lead_time = $interval->format('%a');

            $actual_lead_time=0;
            if($t->actual_complete_date != ''){
                $datetime3 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->actual_complete_date);
                $datetime4 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->assign_date);
                $interval_1 = $datetime3->diff($datetime4);
                $actual_lead_time = $interval_1->format('%a');
            }

            $status = $t->status == 0 ? 'Terminated' : ($t->status == 1 ? 'Completed' : 'Pending');

            $new_row .= '<tr>';
            $new_row .= '<td class="text-center">'.($k+1).'</td>';
            $new_row .= '<td>'.$t->task_name.'</td>';
            $new_row .= '<td>'.$t->assigned_by.'</td>';
            $new_row .= '<td class="text-center">'.$t->assign_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->delivery_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->reschedule_delivery_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->change_count.'</td>';
            $new_row .= '<td class="text-center">'.$status.'</td>';
            $new_row .= '<td class="text-center">'.$target_lead_time.'</td>';
            $new_row .= '<td class="text-center">'.$actual_lead_time.'</td>';
            $new_row .= '<td class="text-center">'.$t->actual_complete_date.'</td>';
            $new_row .= '<td class="text-center">
                            <span class="btn btn-sm btn-primary" title="View" title="Task Detail" onclick="getMyTaskDetail( '.$t->id.' )">
                                <i class="fa fa-eye"></i>
                            </span>
                        </td>';
            $new_row .= '</tr>';

        }

        return $new_row;

    }

    public function getPendingTaskFilter(Request $request){
        $assigned_to = Auth::user()->email;

        $assigned_by = $request->assigned_by;
        $delivery_date_from = $request->delivery_date_from;
        $delivery_date_to = $request->delivery_date_to;
        $status = $request->status;

        $where = "";

        if($assigned_by != ''){
            $where .= " AND assigned_by='$assigned_by'";
        }

        if($assigned_to != ''){
            $where .= " AND assigned_to='$assigned_to'";
        }

        if($delivery_date_from != '' && $delivery_date_to != ''){
            $where .= " AND reschedule_delivery_date BETWEEN '$delivery_date_from' AND '$delivery_date_to'";
        }

        if($status != ''){
            $where .= " AND status=$status";
        }

        $tasks = DB::select( "SELECT * FROM tasks WHERE 1 $where ORDER BY reschedule_delivery_date ASC" );

        $new_row = '';

        foreach ($tasks as $k => $t){

            $datetime1 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->delivery_date);
            $datetime2 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->assign_date);
            $interval = $datetime1->diff($datetime2);
            $target_lead_time = $interval->format('%a');

            $actual_lead_time=0;
            if($t->actual_complete_date != ''){
                $datetime3 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->actual_complete_date);
                $datetime4 = \Carbon\Carbon::createFromFormat('Y-m-d', $t->assign_date);
                $interval_1 = $datetime3->diff($datetime4);
                $actual_lead_time = $interval_1->format('%a');
            }

            $status = $t->status == 0 ? 'Terminated' : ($t->status == 1 ? 'Completed' : 'Pending');

            $new_row .= '<tr>';
            $new_row .= '<td class="text-center">'.($k+1).'</td>';
            $new_row .= '<td>'.$t->task_name.'</td>';
            $new_row .= '<td>'.$t->assigned_by.'</td>';
            $new_row .= '<td class="text-center">'.$t->assign_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->delivery_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->reschedule_delivery_date.'</td>';
            $new_row .= '<td class="text-center">'.$t->change_count.'</td>';
            $new_row .= '<td class="text-center">'.$status.'</td>';
            $new_row .= '<td class="text-center">
                            <span class="btn btn-sm btn-warning" title="Reschedule Task" onclick="rescheduleDeliveryDate('.$t->id.')">
                                <i class="fa fa-clock"></i>
                            </span>
                            <span class="btn btn-sm btn-primary" title="View" title="Task Detail" onclick="getAssignedTaskDetail('.$t->id.' )">
                                <i class="fa fa-eye"></i>
                            </span>
                            <span class="btn btn-sm btn-success" title="Meeting" onclick="fixMeeting('."'".$t->id."'".' , '."'".$t->assigned_by."'".');">
                                <i class="far fa-comments"></i>
                            </span>
                        </td>';
            $new_row .= '</tr>';

        }

        return $new_row;

    }

    public function performanceReport(){
        $assigned_to_emails = Task::groupBy('assigned_to')->select('assigned_to')->get();

        return view('performance_report')->with('assigned_to_emails', $assigned_to_emails);
    }

    public function getPerformanceReport(Request $request){
        $assigned_to = $request->assigned_to;
        $assigned_date_from = $request->assigned_date_from;
        $assigned_date_to = $request->assigned_date_to;

        $where = "";

        if($assigned_to != ''){
            $where .= " AND assigned_to='$assigned_to'";
        }

        if($assigned_date_from != '' && $assigned_date_to != ''){
            $where .= " AND assign_date BETWEEN '$assigned_date_from' AND '$assigned_date_to'";
        }

        $tasks = DB::select(
                 DB::raw("SELECT t1.*, t2.first_time_delivery_tasks, t3.not_first_time_delivery_tasks, t4.more_than_three_rescheduled_delivery_tasks
                        FROM 
                        (SELECT assigned_to, COUNT(id) AS total_tasks 
                         FROM `tasks` 
                         WHERE 1 $where 
                         GROUP BY assigned_to) AS t1
                        
                        LEFT JOIN
                        (SELECT assigned_to, COUNT(id) AS first_time_delivery_tasks 
                         FROM `tasks` 
                         WHERE 1 $where
                         AND change_count=0 
                         GROUP BY assigned_to) AS t2
                        ON t1.assigned_to=t2.assigned_to
                        
                        LEFT JOIN
                        (SELECT assigned_to, COUNT(id) AS not_first_time_delivery_tasks 
                         FROM `tasks` 
                         WHERE 1 $where
                         AND change_count> 0 
                         GROUP BY assigned_to) AS t3
                        ON t1.assigned_to=t3.assigned_to
                        
                        LEFT JOIN
                        (SELECT assigned_to, COUNT(id) AS more_than_three_rescheduled_delivery_tasks 
                         FROM `tasks` 
                         WHERE 1 $where
                         AND change_count>3 
                         GROUP BY assigned_to) AS t4
                        ON t1.assigned_to=t4.assigned_to") );

        $new_row = '';

        foreach ($tasks as $k => $t){

            $new_row .= '<tr>';
            $new_row .= '<td class="text-center">'.($k+1).'</td>';
            $new_row .= '<td class="text-center">'.$t->assigned_to.'</td>';
            $new_row .= '<td class="text-center">'.($t->total_tasks <> '' ? $t->total_tasks : 0).'</td>';
            $new_row .= '<td class="text-center">'.($t->first_time_delivery_tasks <> '' ? $t->first_time_delivery_tasks : 0).'</td>';
            $new_row .= '<td class="text-center">'.($t->not_first_time_delivery_tasks <> '' ? $t->not_first_time_delivery_tasks : 0).'</td>';
            $new_row .= '<td class="text-center">'.($t->more_than_three_rescheduled_delivery_tasks <> '' ? $t->more_than_three_rescheduled_delivery_tasks : 0).'</td>';
            $new_row .= '</tr>';

        }

        return $new_row;
    }
}