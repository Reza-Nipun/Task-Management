<?php

namespace App\Http\Controllers;

use App\RecurringSubTask;
use App\RecurringTaskDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\RecurringTask;

class RecurringTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

    }

    public function addRecurringTask()
    {
        return view('add_recurring_task');
    }

    public function saveRecurringTask(Request $request){
        $this->validate($request, [
           'recurring_type' => 'required',
           'last_date_of_month' => 'required_if:recurring_type,0',
           'monthly_recurring_date' => 'required_if:last_date_of_month,0',
           'weekly_recurring_day' => 'required_if:recurring_type,1',
           'task_name' => 'required',
           'assign_to' => 'required|email',
           'file' => 'mimes:pdf',
        ]);

        $file = $request->file('file');
        $file_name_with_extension='';
        if(isset($file)){
            $file_name = basename($request->file('file')->getClientOriginalName(), '.'.$request->file('file')->getClientOriginalExtension());
            //Display File Extension
            $file_extension = $file->getClientOriginalExtension();

            //Renamed File Name
            $file_name_with_extension = $file_name.'-'.date('YmdHis').'.'.$file_extension;

            //Move Uploaded File
            $destinationPath = 'storage/app/public/uploads';
            $file->move($destinationPath,$file_name_with_extension);
        }

        $assigned_to = $request->assign_to;

        $recuring_task = new RecurringTask;

        $recuring_task->task_name = $request->task_name;
        $recuring_task->task_description = $request->task_description;
        $recuring_task->attachment = $file_name_with_extension;
        $recuring_task->assigned_by = Auth::user()->email;
        $recuring_task->assigned_to = $assigned_to;
        $recuring_task->recurring_type = $request->recurring_type;
        $recuring_task->last_date_of_month = $request->last_date_of_month;
        $recuring_task->monthly_recurring_date = $request->monthly_recurring_date;
        $recuring_task->weekly_recurring_day = $request->weekly_recurring_day;
        $recuring_task->status = 1;
        $recuring_task->save();

        $recurring_task_id = $recuring_task->id;


        $sub_tasks = $request->sub_task_name;
        $responsible_persons = $request->responsible_person;
        $delivery_dates = $request->sub_task_delivery_date;

        if(isset($sub_tasks)){
            foreach($sub_tasks AS $k => $st){
                if(!empty($st)){
                    $sub_task = new RecurringSubTask();
                    $sub_task->parent_recurring_task_id = $recurring_task_id;
                    $sub_task->sub_task_name = $st;
                    $sub_task->responsible_person = $responsible_persons[$k];
                    $sub_task->delivery_date = ($delivery_dates[$k] == '' ? '0000-00-00' : date('Y-m-d', strtotime($delivery_dates[$k])));
                    $sub_task->status=1;
                    $sub_task->save();
                }
            }
        }

        $data = array(
            'task_id' => $recurring_task_id,
            'task_name' => $request->task_name,
            'task_description' => $request->task_description,
            'assigned_by' => Auth::user()->email,
            'recurring_type' => $request->recurring_type,
            'last_date_of_month' => $request->last_date_of_month,
            'month_date' => $request->monthly_recurring_date,
            'weekly_recurring_day' => $request->weekly_recurring_day,
        );

        Mail::send('emails.recurring_task_assignment_notification', $data, function($message) use($assigned_to)
        {
            $message
                ->to($assigned_to)
                ->subject('New Recurring Task Assignment');
        });

        \Session::flash('message', 'Recurring Task Creation Successful!');

        return redirect()->back();
    }

    public function getMyRecurringTasks(){
        $my_email = Auth::user()->email;

        $assigned_by_email_list = RecurringTask::where('assigned_to', $my_email)->groupBy('assigned_by')->select('assigned_by')->get();

        $my_recurring_pending_tasks = DB::select(
                "SELECT t1.*, t2.id AS recurring_task_detail_id, t2.recurring_date, t2.status AS task_detail_status 
                FROM (SELECT * FROM `recurring_tasks` WHERE assigned_to='$my_email' AND status=1) AS t1
    
                INNER JOIN
                (SELECT * FROM `recurring_task_details` WHERE status=2) AS t2
                ON t1.id=t2.recurring_task_id
                
                ORDER BY t2.recurring_date ASC"
        );

        return view('my_recurring_pending_tasks')->with(['my_recurring_pending_tasks'=>$my_recurring_pending_tasks, 'assigned_by_email_list'=>$assigned_by_email_list]);
    }

    public function getMyPendingRecurringTasksReport(Request $request){
        $task_name = $request->task_name;
        $assigned_by = $request->assigned_by;
        $recurring_type = $request->recurring_type;

        $my_email = Auth::user()->email;

        $where = '';

        if($task_name != ''){
            $where .= " AND task_name LIKE '%$task_name%'";
        }

        if($assigned_by != ''){
            $where .= " AND assigned_by='$assigned_by'";
        }

        if($recurring_type != ''){
            $where .= " AND recurring_type=$recurring_type";
        }

        $my_recurring_pending_tasks = DB::select(
            "SELECT t1.*, t2.id AS recurring_task_detail_id, t2.recurring_date, t2.status AS task_detail_status 
                FROM (SELECT * FROM `recurring_tasks` WHERE assigned_to='$my_email' AND status=1 $where) AS t1
    
                INNER JOIN
                (SELECT * FROM `recurring_task_details` WHERE status=2) AS t2
                ON t1.id=t2.recurring_task_id
                
                ORDER BY t2.recurring_date ASC"
        );


        $new_row = '';
        $view_doc_btn = '';

        foreach ($my_recurring_pending_tasks as $k => $t){

            if($t->attachment != ''){
                $view_doc_btn = '<a href="'.asset('storage/app/public/uploads/'.$t->attachment).'" target="_blank" class="btn btn-sm btn-primary ml-1" title="Attachment"><i class="fa fa-paperclip"></i></a>';
            }else{
                $view_doc_btn = '';
            }

            $new_row .= '<tr>';
            $new_row .= '<td class="text-center">'.($k+1).'</td>';
            $new_row .= '<td>'.$t->task_name.' '.$t->attachment.'</td>';
            $new_row .= '<td class="text-center">'.$t->assigned_by.'</td>';
            $new_row .= '<td class="text-center">'.($t->recurring_type == 0 ? 'MONTHLY' : ($t->recurring_type == 1 ? 'WEEKLY' : '')).'</td>';
            $new_row .= '<td class="text-center">'.$t->recurring_date.'</td>';
            $new_row .= '<td class="text-center">'.($t->task_detail_status == 2 ? 'Pending' : ($t->task_detail_status == 0 ? 'Terminated' : 'Completed')).'</td>';
            $new_row .= '<td class="text-center"><span class="btn btn-sm btn-success" title="COMPLETE" onclick="completeRecurringTask('.$t->recurring_task_detail_id.');"><i class="fa fa-check"></i></span>'.$view_doc_btn.'</td>';
            $new_row .= '</tr>';

        }

        return $new_row;
    }

    public function completeRecurringTask(Request $request){
        $id = $request->id;

        DB::table('recurring_task_details')
            ->where('id', $id)
            ->where('status', 2)
            ->update(['status' => 1, 'actual_complete_date' => date('Y-m-d')]);

        DB::table('recurring_sub_task_details')
            ->where('parent_recurring_task_id', $id)
            ->where('status', 2)
            ->update(['status' => 1, 'actual_complete_date' => date('Y-m-d')]);

        echo 'done';
    }

    public function getAssignedRecurringTasks(){
        $assigned_by = Auth::user()->email;

        $assigned_to_email_list = RecurringTask::where('assigned_by', $assigned_by)
                                ->where('status', 1)
                                ->groupBy('assigned_to')
                                ->select('assigned_to')
                                ->get();

        $assigned_recurring_tasks = RecurringTask::where('assigned_by', $assigned_by)
                                    ->where('status', 1)
                                    ->get();

        return view('recurring_tasks')->with(['assigned_to_email_list'=>$assigned_to_email_list, 'assigned_recurring_tasks'=>$assigned_recurring_tasks]);
    }

    public function getAssignedRecurringTasksFilter(Request $request){
        $recurring_task_name_search = $request->recurring_task_name_search;
        $recurring_type = $request->recurring_type;
        $recurring_task_assigned_to = $request->recurring_task_assigned_to;

        $assigned_by = Auth::user()->email;

        $where = '';

        if($recurring_task_name_search != ''){
            $where .= " AND task_name LIKE '%$recurring_task_name_search%'";
        }

        if($recurring_type != ''){
            $where .= " AND recurring_type=$recurring_type";
        }

        if($recurring_task_assigned_to != ''){
            $where .= " AND assigned_to='$recurring_task_assigned_to'";
        }

        $assigned_recurring_tasks = DB::select("SELECT * FROM recurring_tasks WHERE assigned_by='$assigned_by' $where");

        $new_row = '';
        $view_doc_btn = '';

        foreach ($assigned_recurring_tasks as $k => $t){

            if($t->attachment != ''){
                $view_doc_btn = '<a href="'.asset('storage/app/public/uploads/'.$t->attachment).'" target="_blank" class="btn btn-sm btn-primary ml-1" title="Attachment"><i class="fa fa-paperclip"></i></a>';
            }else{
                $view_doc_btn = '';
            }

            $new_row .= '<tr>';
            $new_row .= '<td>'.($k+1).'</td>';
            $new_row .= '<td class="text-center">'.$t->task_name.'</td>';
            $new_row .= '<td class="text-center">'.$t->assigned_to.'</td>';
            $new_row .= '<td class="text-center">'.($t->recurring_type == 0 ? 'MONTHLY' : ($t->recurring_type == 1 ? 'WEEKLY' : '')).'</td>';
            $new_row .= '<td class="text-center">'.($t->recurring_type == 0 ? ($t->last_date_of_month == 1 ? 'YES' : ($t->last_date_of_month == 0 ? 'NO' : '')) : '').'</td>';
            $new_row .= '<td class="text-center">'.$t->monthly_recurring_date.'</td>';
            $new_row .= '<td class="text-center">'.($t->recurring_type == 1 ? ($t->weekly_recurring_day == 0 ? 'SUNDAY' : ($t->weekly_recurring_day == 1 ? 'MONDAY' : ($t->weekly_recurring_day == 2 ? 'TUESDAY' : ($t->weekly_recurring_day == 3 ? 'WEDNESDAY' : $t->weekly_recurring_day == 4 ? 'THURSDAY' : ($t->weekly_recurring_day == 5 ? 'FRIDAY' : ($t->weekly_recurring_day == 6 ? 'SATURDAY' : '')))))) : '').'</td>';
            $new_row .= '<td class="text-center">'.($t->status == 1 ? 'ACTIVE' : ($t->status == 0 ? 'INACTIVE' : '')).'</td>';
            $new_row .= '<td class="text-center"><a href="'.route('edit_recurring_task', $t->id).'" target="_blank" class="btn btn-sm btn-warning" title="Edit Recurring Task"><i class="fa fa-edit"></i></a>'.$view_doc_btn.'</td>';
            $new_row .= '</tr>';

        }

        return $new_row;
    }

    public function editRecurringTask($id){

        $recurring_task = RecurringTask::find($id);
        $recurring_sub_tasks = RecurringSubTask::where('parent_recurring_task_id', $id)->get();

        return view('edit_recurring_task')->with(['recurring_task'=>$recurring_task, 'recurring_sub_tasks'=>$recurring_sub_tasks]);
    }

    public function updateRecurringTask(Request $request, $id){
        $this->validate($request, [
            'recurring_type' => 'required',
            'last_date_of_month' => 'required_if:recurring_type,0',
            'monthly_recurring_date' => 'required_if:last_date_of_month,0',
            'weekly_recurring_day' => 'required_if:recurring_type,1',
            'task_name' => 'required',
            'assign_to' => 'required|email',
            'file' => 'mimes:pdf',
        ]);

        $pre_file_name = $request->pre_file_name;

        $file = $request->file('file');
        $file_name_with_extension='';
        if(isset($file)){
            $file_name = basename($request->file('file')->getClientOriginalName(), '.'.$request->file('file')->getClientOriginalExtension());
            //Display File Extension
            $file_extension = $file->getClientOriginalExtension();

            //Renamed File Name
            $file_name_with_extension = $file_name.'-'.date('YmdHis').'.'.$file_extension;

            //Move Uploaded File
            $destinationPath = 'storage/app/public/uploads';
            $file->move($destinationPath,$file_name_with_extension);

            Storage::delete('public/uploads/' . $pre_file_name); // Removing Previous File
        }else{
            $file_name_with_extension = $pre_file_name;
        }

        $recuring_task = RecurringTask::find($id);

        $recuring_task->task_name = $request->task_name;
        $recuring_task->task_description = $request->task_description;
        $recuring_task->attachment = $file_name_with_extension;
        $recuring_task->assigned_by = Auth::user()->email;
        $recuring_task->assigned_to = $request->assign_to;
        $recuring_task->recurring_type = $request->recurring_type;
        $recuring_task->last_date_of_month = $request->recurring_type == 1 ? '' : $request->last_date_of_month;
        $recuring_task->monthly_recurring_date = $request->recurring_type == 1 ? '' : $request->monthly_recurring_date;
        $recuring_task->weekly_recurring_day = $request->weekly_recurring_day;
        $recuring_task->status = 1;
        $recuring_task->save();


//        OLD SUB TASK DATA START
        $sub_task_ids = $request->sub_task_ids;
        $sub_task_name_olds = $request->sub_task_name_olds;
        $responsible_person_olds = $request->responsible_person_olds;
        $sub_task_delivery_date_olds = $request->sub_task_delivery_date_olds;
        $status_olds = $request->status_olds;
        if(isset($sub_task_ids)) {
            foreach ($sub_task_ids as $k => $sub_task_id) {
                $sub_task = RecurringSubTask::find($sub_task_id);

                $sub_task->sub_task_name = $sub_task_name_olds[$k];
                $sub_task->responsible_person = $responsible_person_olds[$k];
                $sub_task->delivery_date = $sub_task_delivery_date_olds[$k];
                $sub_task->status = $status_olds[$k];
                $sub_task->save();
            }
        }
//        OLD SUB TASK DATA END


//        NEW SUB TASK DATA START
        $sub_task_names = $request->sub_task_name;
        $responsible_persons = $request->responsible_person;
        $sub_task_delivery_dates = $request->sub_task_delivery_date;

        if(isset($sub_task_names)){
            foreach($sub_task_names as $k => $new_sub_task){
                if(!empty($new_sub_task)){
                    $sub_task_new = new RecurringSubTask();

                    $sub_task_new->parent_recurring_task_id = $id;
                    $sub_task_new->sub_task_name = $new_sub_task;
                    $sub_task_new->responsible_person = $responsible_persons[$k];
                    $sub_task_new->delivery_date = $sub_task_delivery_dates[$k];
                    $sub_task_new->status = 1;
                    $sub_task_new->save();
                }
            }
        }
//        NEW SUB TASK DATA END

        \Session::flash('message', 'Recurring Task Creation Successful!');

        return redirect()->back();

    }

}
