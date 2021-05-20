@extends('layouts.app')

@section('content')
<div class="">
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
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Task</label>
                                <input type="text" class="form-control" name="task_name_search" id="task_name_search" placeholder="Search Task..." />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Assigned To</label>
                                <select class="form-control" id="task_assigned_to" name="task_assigned_to">
                                    <option value="">Select Email</option>

                                    @foreach($assigned_to_emails as $e)

                                        <option value="{{ $e->assigned_to }}">{{ $e->assigned_to }}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="delivery_date_from" class="form-label">Delivery Date From</label>
                                <input class="form-control" type="date" id="delivery_date_from" name="delivery_date_from" placeholder="YYYY-mm-dd" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="delivery_date_to" class="form-label">Delivery Date To</label>
                                <input class="form-control" type="date" id="delivery_date_to" name="delivery_date_to" placeholder="YYYY-mm-dd" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <span class="btn btn-success mt-4" id="search_btn" onclick="getAssignedPendingTaskReport()">SEARCH</span>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="mt-3">
                                <div class="loader" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive tableFixHead">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">TASK</th>
                                    <th class="text-center">ASSIGNED TO</th>
                                    <th class="text-center">ASSIGNED ON</th>
                                    <th class="text-center">DELIVERY DATE</th>
                                    <th class="text-center">RESCHEDULE DATE</th>
                                    <th class="text-center">CHANGE COUNT</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_id">
                                @foreach($tasks as $k => $t)
                                    <tr>
                                        <td class="text-center">{{ $k+1 }}</td>
                                        <td>{{ $t->task_name }}</td>
                                        <td>{{ $t->assigned_to }}</td>
                                        <td class="text-center">{{ $t->assign_date }}</td>
                                        <td class="text-center">{{ $t->delivery_date }}</td>
                                        <td class="text-center">{{ $t->reschedule_delivery_date }}</td>
                                        <td class="text-center">{{ $t->change_count }}</td>
                                        <td class="text-center">{{ ($t->status == 2 ? 'Pending' : ($t->status == 0 ? 'Terminated' : 'Completed')) }}</td>
                                        <td>
                                            <span class="btn btn-sm btn-warning" title="Edit Task" onclick="editAssignedTask({{ $t->id }})">
                                                <i class="fa fa-edit"></i>
                                            </span>
                                            <span class="btn btn-sm btn-primary" title="View" title="Task Detail" onclick="getAssignedTaskDetail({{ $t->id }})">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                            <span class="btn btn-sm btn-success" title="Meeting" onclick="fixMeeting('{{ $t->id }}' , '{{ $t->assigned_to }}');">
                                                <i class="far fa-comments"></i>
                                            </span>
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

<div class="modal fade" id="editModalLong" tabindex="-1" role="dialog" aria-labelledby="editModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLongTitle">Edit Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="edit_task_name" class="col-sm-4 col-form-label font-weight-bold">Task: <span style="color: red">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="edit_task_name" />
                        <input type="hidden" name="edit_task_id" id="edit_task_id" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="edit_task_description" class="col-sm-4 col-form-label font-weight-bold">Description:</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" id="edit_task_description"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="edit_assigned_to" class="col-sm-4 col-form-label font-weight-bold">Assigned To: <span style="color: red">*</span></label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="edit_assigned_to" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="edit_delivery_date" class="col-sm-4 col-form-label font-weight-bold">Delivery Date: <span style="color: red">*</span></label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="edit_delivery_date" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="edit_reschedule_delivery_date" class="col-sm-4 col-form-label font-weight-bold">Reschedule Date: <span style="color: red">*</span></label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="edit_reschedule_delivery_date" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="edit_remarks" class="col-sm-4 col-form-label font-weight-bold">Remarks:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="edit_remarks" />
                    </div>
                </div>
                <div class="form-group row">
                    <table class="table table-bordered text-center" id="MyTable">
                        <thead>
                        <tr>
                            <th>Task <span style="color: red">*</span></th>
                            <th>Responsible</th>
                            <th>Delivery</th>
                            <th title="ADD"><span class="btn btn-sm btn-primary" onclick="addSubTaskRow()"><i class="fa fa-plus"></i></span></th>
                        </tr>
                        </thead>
                        <tbody id="sub_task_row">
                        <tr>
                            <td><input type="text" class="form-control" name="sub_task_name[]" required="required" /></td>
                            <td><input type="text" class="form-control" name="responsible_person[]" /></td>
                            <td><input type="date" class="form-control" name="delivery_date[]" /></td>
                            <td title="DELETE"><span class="btn btn-sm btn-danger" id="DeleteButton"><i class="fa fa-archive"></i></span></td>
                        </tr>
                        <tr>
                            <td><input type="text" class="form-control" name="sub_task_name[]" required="required" /></td>
                            <td><input type="text" class="form-control" name="responsible_person[]" /></td>
                            <td><input type="date" class="form-control" name="delivery_date[]" /></td>
                            <td title="DELETE"><span class="btn btn-sm btn-danger" id="DeleteButton"><i class="fa fa-archive"></i></span></td>
                        </tr>
                        <tr>
                            <td><input type="text" class="form-control" name="sub_task_name[]" required="required" /></td>
                            <td><input type="text" class="form-control" name="responsible_person[]" /></td>
                            <td><input type="date" class="form-control" name="delivery_date[]" /></td>
                            <td title="DELETE"><span class="btn btn-sm btn-danger" id="DeleteButton"><i class="fa fa-archive"></i></span></td>
                        </tr>
                        <tr>
                            <td><input type="text" class="form-control" name="sub_task_name[]" required="required" /></td>
                            <td><input type="text" class="form-control" name="responsible_person[]" /></td>
                            <td><input type="date" class="form-control" name="delivery_date[]" /></td>
                            <td title="DELETE"><span class="btn btn-sm btn-danger" id="DeleteButton"><i class="fa fa-archive"></i></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <div class="loader" style="display: none;"></div>
                <span class="btn btn-success" onclick="updateAssignedTask();">Update</span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
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
                <div class="form-group row">
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
                        <tr>
                            <td>Sub Task-1</td>
                            <td>Person-1</td>
                            <td>Date-1</td>
                            <td>Complete</td>
                            <td>
                                <span class="btn btn-sm btn-success" id="sub_task_complete" title="COMPLETE"><i class="fa fa-check"></i></span>
                                <span class="btn btn-sm btn-danger" id="sub_task_terminate" title="TERMINATE"><i class="fa fa-times"></i></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Sub Task-2</td>
                            <td>Person-2</td>
                            <td>Date-2</td>
                            <td> Complete </td>
                            <td>
                                <span class="btn btn-sm btn-success" id="sub_task_complete" title="COMPLETE"><i class="fa fa-check"></i></span>
                                <span class="btn btn-sm btn-danger" id="sub_task_terminate" title="TERMINATE"><i class="fa fa-times"></i></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Sub Task-3</td>
                            <td>Person-3</td>
                            <td>Date-3</td>
                            <td>Pending</td>
                            <td>
                                <span class="btn btn-sm btn-success" id="sub_task_complete" title="COMPLETE"><i class="fa fa-check"></i></span>
                                <span class="btn btn-sm btn-danger" id="sub_task_terminate" title="TERMINATE"><i class="fa fa-times"></i></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Sub Task-4</td>
                            <td>Person-4</td>
                            <td>Date-4</td>
                            <td>Terminated</td>
                            <td>
                                <span class="btn btn-sm btn-success" id="sub_task_complete" title="COMPLETE"><i class="fa fa-check"></i></span>
                                <span class="btn btn-sm btn-danger" id="sub_task_terminate" title="TERMINATE"><i class="fa fa-times"></i></span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <div class="loader" style="display: none;"></div>
                <span class="btn btn-success" onclick="completeAssignedTask();">Complete Task</span>
                <span class="btn btn-danger" onclick="terminateAssignedTask();">Terminate Task</span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Fix Meeting Time</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="meeting_date" class="col-sm-4 col-form-label font-weight-bold">Select Date:</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" name="meeting_date" id="meeting_date" placeholder="YYYY-mm-dd" />
                        <input type="hidden" class="form-control" name="task_id_3" id="task_id_3" />
                        <input type="hidden" class="form-control" name="invite_to" id="invite_to" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="meeting_time" class="col-sm-4 col-form-label font-weight-bold">Select Time:</label>
                    <div class="col-sm-8">
                        <input type="time" class="form-control" name="meeting_time" id="meeting_time" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="meeting_link" class="col-sm-4 col-form-label font-weight-bold">Meeting Link:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="meeting_link" id="meeting_link" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="loader" style="display: none;"></div>
                <span class="btn btn-primary" onclick="fixMeetingDateTime()">Save</span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</div>

<script type="text/javascript">
    $('select').select2();

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

                subTaskDetail(task_id);

                $('#exampleModalLong').modal('show');

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

    function editAssignedTask(task_id) {
        $("#edit_task_id").val('');
        $("#edit_task_name").empty();
        $("#edit_task_description").empty();
        $("#edit_assigned_to").empty();
        $("#edit_delivery_date").empty();
        $("#edit_remarks").empty();

        $.ajax({
            url: "{{ route("get_assigned_task_detail") }}",
            type:'GET',
            data: {_token:"{{csrf_token()}}", task_id: task_id},
            dataType: "json",
            success: function (data) {

                $("#edit_task_id").val(data.id);
                $("#edit_task_name").val(data.task_name);
                $("#edit_task_description").val(data.task_description);
                $("#edit_assigned_to").val(data.assigned_to);
                $("#edit_delivery_date").val(data.delivery_date);
                $("#edit_reschedule_delivery_date").val(data.reschedule_delivery_date);
                $("#edit_remarks").val(data.remarks);

                subTaskDetailtoUpdate(task_id);

                $('#editModalLong').modal('show');

            }
        });
    }

    function subTaskDetailtoUpdate(task_id) {
        $("#sub_task_row").empty();

        $.ajax({
            url: "{{ route("get_sub_task_detail") }}",
            type:'GET',
            data: {_token:"{{csrf_token()}}", task_id: task_id},
            dataType: "json",
            success: function (data_1) {

                for(i=0; i < data_1.length; i++){

                    $("#sub_task_row").append('<tr><td><textarea class="form-control" name="sub_task_name_old[]">'+data_1[i].sub_task_name+'</textarea><input type="hidden" class="form-control" name="sub_task_id[]" value="'+data_1[i].id+'" /></td><td><input type="text" class="form-control" name="responsible_person_old[]" value="'+data_1[i].responsible_person+'" /></td><td><input type="date" class="form-control" name="sub_task_delivery_date_old[]" value="'+data_1[i].delivery_date+'" /></td><td></td></tr>');

                }

            }
        });

    }

    function updateAssignedTask() {
        var task_id = $("#edit_task_id").val();
        var task_name = $("#edit_task_name").val();
        var task_description = $("#edit_task_description").val();
        var assigned_to = $("#edit_assigned_to").val();
        var delivery_date = $("#edit_delivery_date").val();
        var reschedule_delivery_date = $("#edit_reschedule_delivery_date").val();
        var remarks = $("#edit_remarks").val();

//        OLD SUB TASK DATA START
        var sub_task_ids = [];
        $("input[name='sub_task_id[]']").each(function() {
            sub_task_ids.push($(this).val());
        });

        var sub_task_name_olds = [];
        $("textarea[name='sub_task_name_old[]']").each(function() {
            sub_task_name_olds.push($(this).val());
        });

        var responsible_person_olds = [];
        $("input[name='responsible_person_old[]']").each(function() {
            responsible_person_olds.push($(this).val());
        });

        var sub_task_delivery_date_olds = [];
        $("input[name='sub_task_delivery_date_old[]']").each(function() {
            sub_task_delivery_date_olds.push($(this).val());
        });
//        OLD SUB TASK DATA END

//        NEW SUB TASK DATA START
        var sub_task_names = [];
        $("textarea[name='sub_task_name[]']").each(function() {
            sub_task_names.push($(this).val());
        });

        var responsible_persons = [];
        $("input[name='responsible_person[]']").each(function() {
            responsible_persons.push($(this).val());
        });

        var sub_task_delivery_dates = [];
        $("input[name='sub_task_delivery_date[]']").each(function() {
            sub_task_delivery_dates.push($(this).val());
        });
//        NEW SUB TASK DATA END

        if(task_id != '' && task_name != '' && assigned_to != '' && delivery_date != '' && reschedule_delivery_date != ''){
            $.ajax({
                url: "{{ route("update_task_info") }}",
                type:'POST',
                data: {_token:"{{csrf_token()}}", task_id: task_id, task_name: task_name, task_description: task_description, assign_to: assigned_to, delivery_date: delivery_date, reschedule_delivery_date: reschedule_delivery_date, remarks: remarks, sub_task_ids: sub_task_ids, sub_task_name_olds: sub_task_name_olds, responsible_person_olds: responsible_person_olds, sub_task_delivery_date_olds: sub_task_delivery_date_olds, sub_task_names: sub_task_names, responsible_persons: responsible_persons, sub_task_delivery_dates: sub_task_delivery_dates},
                dataType: "html",
                success: function (data) {

                    if(data == 'done'){
                        $("#search_btn").click();
                        $('#editModalLong').modal('hide');
                    }

                }
            });
        }else{
            alert("Please select the required fields!");
        }
    }

    function completeAssignedTask() {

        var res = confirm('Do you want to complete the task?');

        if(res == true){
            $(".loader").css("display", "block");
            var task_id = $("#task_id").val();

            $.ajax({
                url: "{{ route("complete_assigned_task") }}",
                type:'POST',
                data: {_token:"{{csrf_token()}}", task_id: task_id},
                dataType: "html",
                success: function (data) {

                    if(data == 'done'){

                        $("#search_btn").click();
                        $('#exampleModalLong').modal('hide');
                    }

                }
            });
        }

    }

    function terminateAssignedTask() {

        var res = confirm('Do you want to terminate the task?');

        if(res == true){
            $(".loader").css("display", "block");
            var task_id = $("#task_id").val();

            $.ajax({
                url: "{{ route("terminate_assigned_task") }}",
                type:'POST',
                data: {_token:"{{csrf_token()}}", task_id: task_id},
                dataType: "html",
                success: function (data) {

                    if(data == 'done'){

                        $("#search_btn").click();
                        $('#exampleModalLong').modal('hide');

                    }

                }
            });
        }

    }

    function fixMeeting(task_id, assigned_to) {

        $("#task_id_3").val('');
        $("#invite_to").val('');

        var url = '{{ route("check_pending_meeting", ":id") }}';
        url = url.replace(':id', task_id );

        $.ajax({
            url: url,
            type:'GET',
            data: {_token:"{{csrf_token()}}"},
            dataType: "json",
            success: function (data) {

                if(data.length > 0){

                    var meeting_id = data[0].id;

                    alert("Already a meeting is set, please complete it.");

                    {{--window.location.href="{{ route('meetings') }}";--}}
                }else{
                    $("#task_id_3").val(task_id);

                    $("#invite_to").val(assigned_to);

                    $('#exampleModal2').modal('show');
                }

            }
        });

    }

    function fixMeetingDateTime() {
        var res = confirm('Confirm to fix the meeting?');

        if(res == true){
            $(".loader").css("display", "block");
            var task_id_3 = $("#task_id_3").val();
            var meeting_date = $("#meeting_date").val();
            var meeting_time = $("#meeting_time").val();
            var invite_to = $("#invite_to").val();
            var meeting_link = $("#meeting_link").val();

            if(meeting_date != '' && meeting_time != ''){
                $.ajax({
                    url: "{{ route("fix_meeting_date_time") }}",
                    type:'POST',
                    data: {_token:"{{csrf_token()}}", task_id: task_id_3, meeting_date: meeting_date, meeting_time: meeting_time, invite_to: invite_to, meeting_link: meeting_link},
                    dataType: "html",
                    success: function (data) {

                        if(data == 'done'){

                            $("#search_btn").click();
                            $('#exampleModal2').modal('hide');

                        }

                    }
                });
            }
        }
    }

    function getAssignedPendingTaskReport() {
        var task_name_search = $("#task_name_search").val();
        var assigned_to = $("#task_assigned_to").val();
        var delivery_date_from = $("#delivery_date_from").val();
        var delivery_date_to = $("#delivery_date_to").val();
        var status = 2;

        $("#tbody_id").empty();
        $(".loader").css("display", "block");

        $.ajax({
            url: "{{ route("get_assigned_pending_task_filter") }}",
            type:'POST',
            data: {_token:"{{csrf_token()}}", task_name: task_name_search, assigned_to: assigned_to, delivery_date_from: delivery_date_from, delivery_date_to: delivery_date_to, status: status},
            dataType: "html",
            success: function (data) {

                $("#tbody_id").append(data);
                $(".loader").css("display", "none");

            }
        });

    }

    function addSubTaskRow(){
        $("#sub_task_row").append('<tr><td><textarea class="form-control" name="sub_task_name[]" required="required"></textarea></td><td><input type="text" class="form-control" name="responsible_person[]" /></td><td><input type="date" class="form-control" name="sub_task_delivery_date[]" /></td><td title="DELETE"><span class="btn btn-sm btn-danger" id="DeleteButton"><i class="fa fa-archive"></i></span></td></tr>');
    }

    $("#MyTable").on("click", "#DeleteButton", function() {
        $(this).closest("tr").remove();
    });

</script>

@endsection
