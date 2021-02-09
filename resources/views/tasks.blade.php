@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Assigned Tasks') }}

                    <div class="btn-toolbar float-right">
                        @if(Auth::user()->assign_task_access == 1)
                            <a class="btn btn-success mr-1" href="{{ url('/add_task') }}" title="Assign Task">
                                <i class="fa fa-plus"></i> Assign Task
                            </a>
                        @endif

                        <a class="btn btn-primary" href="{{ url('/my_tasks') }}" title="My Tasks">
                            <i class="fa fa-list"></i> My Tasks
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">TASK</th>
                                    <th class="text-center">ASSIGNED TO</th>
                                    <th class="text-center">ASSIGNED ON</th>
                                    <th class="text-center">DELIVERY DATE</th>
                                    <th class="text-center">CHANGE COUNT</th>
                                    <th class="text-center">REMARKS</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $k => $t)
                                    <tr>
                                        <td>{{ $k+1 }}</td>
                                        <td>{{ $t->task_name }}</td>
                                        <td>{{ $t->assigned_to }}</td>
                                        <td>{{ $t->assign_date }}</td>
                                        <td>{{ $t->reschedule_delivery_date }}</td>
                                        <td>{{ $t->change_count }}</td>
                                        <td>{{ $t->remarks }}</td>
                                        <td>{{ ($t->status == 2 ? 'Pending' : ($t->status == 0 ? 'Terminated' : 'Completed')) }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" href="{{ url('/edit_task/'.$t->id) }}" title="Edit Task">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <span class="btn btn-sm btn-primary" title="View" title="Task Detail" onclick="getAssignedTaskDetail({{ $t->id }})">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                            <a class="btn btn-sm btn-success" href="{{ url('/edit_task') }}" title="Meeting">
                                                <i class="far fa-comments"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Task Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="task_name" class="col-sm-4 col-form-label font-weight-bold">Task:</label>
                    <div class="col-sm-8">
                        <p class="col-form-label" id="task_name"></p>
                        <input type="hidden" name="task_id" id="task_id" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="task_description" class="col-sm-4 col-form-label font-weight-bold">Description:</label>
                    <div class="col-sm-8">
                        <p class="col-form-label" id="task_description"></p>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="assigned_to" class="col-sm-4 col-form-label font-weight-bold">Assigned To:</label>
                    <div class="col-sm-8">
                        <p class="col-form-label" id="assigned_to"></p>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="assign_date" class="col-sm-4 col-form-label font-weight-bold">Assigned On:</label>
                    <div class="col-sm-8">
                        <p class="col-form-label" id="assign_date"></p>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="delivery_date" class="col-sm-4 col-form-label font-weight-bold">Delivery Date:</label>
                    <div class="col-sm-8">
                        <p class="col-form-label" id="delivery_date"></p>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="reschedule_delivery_date" class="col-sm-4 col-form-label font-weight-bold">Reschedule Date:</label>
                    <div class="col-sm-8">
                        <p class="col-form-label" id="reschedule_delivery_date"></p>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="change_count" class="col-sm-4 col-form-label font-weight-bold">Change Count:</label>
                    <div class="col-sm-8">
                        <p class="col-form-label" id="change_count"></p>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="remarks" class="col-sm-4 col-form-label font-weight-bold">Remarks:</label>
                    <div class="col-sm-8">
                        <p class="col-form-label" id="remarks"></p>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="status" class="col-sm-4 col-form-label font-weight-bold">Status:</label>
                    <div class="col-sm-8">
                        <p class="col-form-label" id="status"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="btn btn-success" onclick="completeAssignedTask();">Complete Task</span>
                <span class="btn btn-danger" onclick="terminateAssignedTask();">Terminate Task</span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</div>

<script type="text/javascript">

    function getAssignedTaskDetail(task_id){

        $("#task_id").val('');
        $("#task_name").empty();
        $("#task_description").empty();
        $("#assigned_to").empty();
        $("#assign_date").empty();
        $("#delivery_date").empty();
        $("#reschedule_delivery_date").empty();
        $("#change_count").empty();
        $("#remarks").empty();
        $("#status").empty();

        $.ajax({
            url: "{{ route("get_assigned_task_detail") }}",
            type:'GET',
            data: {_token:"{{csrf_token()}}", task_id: task_id},
            dataType: "json",
            success: function (data) {
                console.log(data);

                $("#task_id").val(data.id);
                $("#task_name").text(data.task_name);
                $("#task_description").text(data.task_description);
                $("#assigned_to").text(data.assigned_to);
                $("#assign_date").text(data.assign_date);
                $("#delivery_date").text(data.delivery_date);
                $("#reschedule_delivery_date").text(data.reschedule_delivery_date);
                $("#change_count").text(data.change_count != null ? data.change_count : 0);
                $("#remarks").text(data.remarks);
                $("#status").text(data.status == 0 ? 'Terminated' : (data.status == 1 ? 'Completed' : 'Pending'));

                $('#exampleModalLong').modal('show');
            }
        });

    }

    function completeAssignedTask() {

        var res = confirm('Do you want to complete the task?');

        if(res == true){

        }

    }

    function terminateAssignedTask() {

        var res = confirm('Do you want to terminate the task?');

        if(res == true){

        }

    }

</script>

@endsection
