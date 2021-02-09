<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Task;

class TaskController extends Controller
{
    public function getTasks(){
        $assigned_by = Auth::user()->email;

        $tasks = Task::where('assigned_by', $assigned_by)->where('status', 2)->get();

        return view('tasks')->with('tasks', $tasks);
    }

    public function myTasks(){
        $assigned_by = Auth::user()->email;

        $tasks = Task::where('assigned_to', $assigned_by)->where('status', 2)->get();

        return view('my_tasks')->with('tasks', $tasks);
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

        \Session::flash('message', 'Task Assignment Successful!');

        return redirect('/add_task');

    }

    public function uploadTaskFile(){
        return view('upload_task_csv');
    }

    public function uploadingTaskFile(Request $request){
        $this->validate(request(), [
            'upload_file'   => 'required|mimes:csv,txt',
        ]);

        $path = $request->file('upload_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        foreach ($data as $k => $v){
            if($k > 0){


                $task_name = $v[0];
                $task_description = $v[1];
                $assigned_to = $v[2];
                $delivery_date = $v[3];
                $remarks = $v[4];

                $assigned_by = Auth::user()->email;
                $assign_date = date('Y-m-d');
                $status = 2;

                if($task_name != '' && $assigned_to != '' && $delivery_date != ''){

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

                }


            }
        }

        \Session::flash('message', 'Task Assignment Successful!');

        return redirect('/upload_task_file');

    }
}
