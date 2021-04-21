@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Edit Meeting') }}
                    </div>

                    <form action="{{ url('/update_meeting/'.$meeting_info[0]->id) }}" method="post">
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
                                    <label for="task_name" class="form-label">Task Name</label>
                                    <input class="form-control" type="text" id="task_name" name="task_name" readonly="readonly" value="{{ $meeting_info[0]->task_name }}" />
                                    <input class="form-control" type="hidden" id="task_id_3" name="task_id_3" readonly="readonly" value="{{ $meeting_info[0]->task_id }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_description" class="form-label">Task Description</label>
                                    <input class="form-control" type="text" id="task_description" name="task_description" readonly="readonly" value="{{ $meeting_info[0]->task_description }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meeting_date" class="form-label">Meeting Date <span style="color: red">*</span></label>
                                    <input class="form-control" type="date" id="meeting_date" name="meeting_date" required="required" value="{{ $meeting_info[0]->meeting_date }}" placeholder="YYYY-mm-dd" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meeting_time" class="form-label">Meeting Time <span style="color: red">*</span></label>
                                    <input class="form-control" type="time" id="meeting_time" name="meeting_time" required="required" value="{{ $meeting_info[0]->meeting_time }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meeting_description" class="form-label">Meeting Description</label>
                                    <textarea class="form-control" id="meeting_description" name="meeting_description">{{ $meeting_info[0]->description }}</textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meeting_link" class="form-label">Meeting Link</label>
                                    <input class="form-control" type="text" id="meeting_link" name="meeting_link" value="{{ $meeting_info[0]->meeting_link }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <input class="form-control" type="text" id="remarks" name="remarks" value="{{ $meeting_info[0]->remarks }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meeting_status" class="form-label">Meeting Status <span style="color: red">*</span></label>
                                    <select class="form-control" id="meeting_status" name="meeting_status" required="required">
                                        <option value="">Select Status</option>
                                        <option value="0" @if($meeting_info[0]->status == 0) selected="selected" @endif>Cancel</option>
                                        <option value="1" @if($meeting_info[0]->status == 1) selected="selected" @endif>Active</option>
                                        <option value="2" @if($meeting_info[0]->status == 2) selected="selected" @endif>Complete</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="delivery_date" class="form-label">Delivery Date</label>
                                    <input class="form-control" type="text" id="delivery_date" name="delivery_date" readonly="readonly" value="{{ $meeting_info[0]->reschedule_delivery_date }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success mt-4">SAVE</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <input class="form-control" type="hidden" id="task_id" name="task_id" readonly="readonly" value="{{ $meeting_info[0]->task_id }}" />
                                    <input class="form-control" type="hidden" id="meeting_id" name="meeting_id" readonly="readonly" value="{{ $meeting_info[0]->id }}" />

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
                    </form>
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
                var meeting_id = $("#meeting_id").val();

                $.ajax({
                    url: "{{ route("complete_assigned_task") }}",
                    type:'POST',
                    data: {_token:"{{csrf_token()}}", task_id: task_id},
                    dataType: "html",
                    success: function (data) {

                        if(data == 'done'){

                            $.ajax({
                                url: "{{ route("meeting_complete") }}",
                                type:'POST',
                                data: {_token:"{{csrf_token()}}", meeting_id: meeting_id},
                                dataType: "html",
                                success: function (data_1) {

                                    if(data_1 == 'done'){

                                        window.location.href="{{ route('tasks') }}";

                                    }

                                }
                            });

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

                            $.ajax({
                                url: "{{ route("meeting_complete") }}",
                                type:'POST',
                                data: {_token:"{{csrf_token()}}", meeting_id: meeting_id},
                                dataType: "html",
                                success: function (data_1) {

                                    if(data_1 == 'done'){

                                        window.location.href="{{ route('tasks') }}";

                                    }

                                }
                            });

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
