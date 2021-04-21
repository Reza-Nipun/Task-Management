@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Task Confirmation') }}
                    </div>


                        {{ csrf_field() }}

                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                    @endif

                    @if(count($errors))
                        <div class="form-group">
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meeting_date" class="form-label">Task Name <span style="color: red">*</span></label>
                                    <input class="form-control" type="text" id="task_name" name="task_name" required="required" readonly="readonly" value="<?php echo $task_info->task_name;?>" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_description" class="form-label">Task Description <span style="color: red">*</span></label>
                                    <input class="form-control" type="text" id="task_description" name="task_description" readonly="readonly" required="required" value="<?php echo $task_info->task_description;?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="assigned_to" class="form-label">Assigned By</label>
                                    <input class="form-control" type="text" id="assigned_to" name="assigned_to" readonly="readonly" value="<?php echo $task_info->assigned_by;?>" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="assigned_to" class="form-label">Assigned To</label>
                                    <input class="form-control" type="text" id="assigned_to" name="assigned_to" readonly="readonly" value="<?php echo $task_info->assigned_to;?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="assigned_on" class="form-label">Assigned On</label>
                                    <input class="form-control" type="text" id="assigned_on" name="assigned_on" readonly="readonly" value="<?php echo $task_info->assign_date;?>" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="delivery_date" class="form-label">Delivery Date</label>
                                    <input class="form-control" type="text" id="delivery_date" name="delivery_date" readonly="readonly" value="<?php echo $task_info->delivery_date;?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="rescheduled_date" class="form-label">Rescheduled Delivery Date</label>
                                    <input class="form-control" type="text" id="rescheduled_date" name="rescheduled_date" readonly="readonly" value="<?php echo $task_info->reschedule_delivery_date;?>" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="change_count" class="form-label">Change Count</label>
                                    <input class="form-control" type="text" id="change_count" name="change_count" readonly="readonly" value="<?php echo $task_info->change_count;?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <input class="form-control" type="hidden" id="task_id" name="task_id" readonly="readonly" value="{{ $task_info->id }}" />

                                    @if($take_action_on_task == 1)
                                        <span class="btn btn-primary mt-4" onclick="completeAssignedTask();">COMPLETE TASK</span>
                                        <span class="btn btn-danger mt-4" onclick="terminateAssignedTask();">TERMINATE TASK</span>
                                    @endif

                                    <span class="btn btn-warning mt-4" onclick="rescheduleDeliveryDate()">RESCHEDULE TASK</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered text-center" id="MyTable">
                                    <thead>
                                        <tr>
                                            <th>Sub Task <span style="color: red">*</span></th>
                                            <th>Responsible</th>
                                            <th>Delivery Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sub_task_row_2">
                                    @foreach($sub_tasks as $sub_task)
                                        <tr>
                                            <td>{{ $sub_task->sub_task_name }}</td>
                                            <td>{{ $sub_task->responsible_person }}</td>
                                            <td>{{ $sub_task->delivery_date }}</td>
                                            <td>{{ ($sub_task->status==2 ? 'Pending' : ($sub_task->status==1 ? 'Complete' : 'Terminated')) }}</td>
                                            <td>
                                                <span class="btn btn-sm btn-success" id="sub_task_complete" title="COMPLETE" onclick="changeSubTaskStatus('{{ $sub_task->parent_task_id }}', '{{ $sub_task->id }}', 1)"><i class="fa fa-check"></i></span>
                                                <span class="btn btn-sm btn-danger" id="sub_task_terminate" title="TERMINATE" onclick="changeSubTaskStatus('{{ $sub_task->parent_task_id }}', '{{ $sub_task->id }}', 0)"><i class="fa fa-times"></i></span>
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
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reschedule Delivery Date</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="reschedule_date" class="col-sm-4 col-form-label font-weight-bold">Select Date:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="reschedule_date" id="reschedule_date" placeholder="YYYY-mm-dd" />
                            <input type="hidden" class="form-control" name="task_id_2" id="task_id_2" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="btn btn-primary" onclick="rescheduleTaskDeliveryDate()">Save</span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">


        function completeAssignedTask() {

            var res = confirm('Do you want to complete the task?');

            if(res == true){
                var task_id = $("#task_id").val();

                $.ajax({
                    url: "{{ route("complete_assigned_task") }}",
                    type:'POST',
                    data: {_token:"{{csrf_token()}}", task_id: task_id},
                    dataType: "html",
                    success: function (data) {

                        if(data == 'done'){

                            window.location.href="{{ route('tasks') }}";

                        }

                    }
                });
            }

        }

        function terminateAssignedTask() {

            var res = confirm('Do you want to terminate the task?');

            if(res == true){
                var task_id = $("#task_id").val();
                var meeting_id = $("#meeting_id").val();

                $.ajax({
                    url: "{{ route("terminate_assigned_task") }}",
                    type:'POST',
                    data: {_token:"{{csrf_token()}}", task_id: task_id},
                    dataType: "html",
                    success: function (data) {

                        if(data == 'done'){

                            window.location.href="{{ route('tasks') }}";

                        }

                    }
                });
            }

        }

        function rescheduleDeliveryDate() {
            var task_id = $("#task_id").val();

            $("#task_id_2").val(task_id);

            $('#exampleModal').modal('show');
        }

        function rescheduleTaskDeliveryDate() {

            var res = confirm('Do you want to reschedule the delivery date?');

            if(res == true){
                var task_id_2 = $("#task_id_2").val();
                var reschedule_date = $("#reschedule_date").val();

                $.ajax({
                    url: "{{ route("reschedule_task_delivery_date") }}",
                    type:'POST',
                    data: {_token:"{{csrf_token()}}", task_id: task_id_2, reschedule_date: reschedule_date},
                    dataType: "html",
                    success: function (data) {

                        if(data == 'done'){

                            location.reload();

                        }

                    }
                });
            }
        }

        function changeSubTaskStatus(task_id, sub_task_id, status) {
            $.ajax({
                url: "{{ route("sub_task_status_change") }}",
                type:'POST',
                data: {_token:"{{csrf_token()}}", sub_task_id: sub_task_id, status: status},
                dataType: "html",
                success: function (data) {

                    if(data == 'done'){
                        subTaskDetail(task_id);
                    }

                }
            });

        }

        function subTaskDetail(task_id) {
            $("#sub_task_row_2").empty();

            $.ajax({
                url: "{{ route("get_sub_task_detail") }}",
                type:'GET',
                data: {_token:"{{csrf_token()}}", task_id: task_id},
                dataType: "json",
                success: function (data_1) {

                    for(i=0; i < data_1.length; i++){

                        $("#sub_task_row_2").append('<tr><td>'+data_1[i].sub_task_name+'</td><td>'+data_1[i].responsible_person+'</td><td>'+data_1[i].delivery_date+'</td><td>'+(data_1[i].status==2 ? 'Pending' : (data_1[i].status==1 ? 'Complete' : 'Terminated'))+'</td><td><span class="btn btn-sm btn-success" id="sub_task_complete" onclick="changeSubTaskStatus('+task_id+', '+data_1[i].id+', 1)" title="COMPLETE"><i class="fa fa-check"></i></span><span class="btn btn-sm btn-danger" id="sub_task_terminate" title="TERMINATE" onclick="changeSubTaskStatus('+task_id+', '+data_1[i].id+', 0)"><i class="fa fa-times"></i></span></td></tr>');

                    }

                }
            });

        }

    </script>

@endsection
