@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Recurring Pending Tasks') }}
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
                                <label for="recurring_type" class="form-label">Recurring Type</label>
                                <select class="form-control" name="recurring_type" id="recurring_type">
                                    <option value="">Select Recurring Type</option>
                                    <option value="0">Monthly</option>
                                    <option value="1">Weekly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="recurring_task_assigned_by" class="form-label">Assigned By</label>
                                <select class="form-control" id="recurring_task_assigned_by" name="recurring_task_assigned_by">
                                    <option value="">Select Email</option>

                                    @foreach($assigned_by_email_list as $e)

                                        <option value="{{ $e->assigned_by }}">{{ $e->assigned_by }}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <span class="btn btn-success mt-4" id="search_btn" onclick="getMyPendingRecurringTasksReport()">SEARCH</span>
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
                                    <th class="text-center">ASSIGNED BY</th>
                                    <th class="text-center">RECURRING TYPE</th>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_id">
                                @foreach($my_recurring_pending_tasks as $k => $t)
                                    <tr>
                                        <td class="text-center">{{ $k+1 }}</td>
                                        <td>{{ $t->task_name }}</td>
                                        <td class="text-center">{{ $t->assigned_by }}</td>
                                        <td class="text-center">{{ ($t->recurring_type == 0 ? 'MONTHLY' : ($t->recurring_type == 1 ? 'WEEKLY' : '')) }}</td>
                                        <td class="text-center">{{ $t->recurring_date }}</td>
                                        <td class="text-center">{{ ($t->task_detail_status == 2 ? 'Pending' : ($t->task_detail_status == 0 ? 'Terminated' : 'Completed')) }}</td>
                                        <td class="text-center">
                                            <span class="btn btn-sm btn-success" title="COMPLETE" onclick="completeRecurringTask({{ $t->recurring_task_detail_id }});">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            <span class="btn btn-sm btn-primary" title="View" onclick="getRecurringTaskDetail({{ $t->recurring_task_detail_id }})">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                            @if($t->attachment != '')
                                                <a href="{{ asset('storage/app/public/uploads/'.$t->attachment) }}" target="_blank" class="btn btn-sm btn-warning" title="Attachment">
                                                    <i class="fa fa-paperclip"></i>
                                                </a>
                                            @endif
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
                            <input type="hidden" name="recurring_task_id" id="recurring_task_id" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="task_description" class="col-sm-4 col-form-label font-weight-bold">Description:</label>
                        <div class="col-sm-8">
                            <p class="col-form-label" id="task_description"></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="assigned_by" class="col-sm-4 col-form-label font-weight-bold">Assigned By:</label>
                        <div class="col-sm-8">
                            <p class="col-form-label" id="assigned_by"></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="recurring_type_info" class="col-sm-4 col-form-label font-weight-bold">Recurring Type:</label>
                        <div class="col-sm-8">
                            <p class="col-form-label" id="recurring_type_info"></p>
                        </div>
                    </div>
                    <div class="" id="recurring_day_div">
                        <div class="form-group row">
                            <label for="recurring_day" class="col-sm-4 col-form-label font-weight-bold">Delivery Day:</label>
                            <div class="col-sm-8">
                                <p class="col-form-label" id="recurring_day"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="recurring_date" class="col-sm-4 col-form-label font-weight-bold">Delivery Date:</label>
                        <div class="col-sm-8">
                            <p class="col-form-label" id="recurring_date"></p>
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
                                {{--<th>Delivery Date</th>--}}
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="sub_task_row">
                            <tr>
                                <td>Sub Task-1</td>
                                <td>Person-1</td>
                                {{--<td>Date-1</td>--}}
                                <td>Complete</td>
                                <td>
                                    <span class="btn btn-sm btn-success" id="sub_task_complete" title="COMPLETE"><i class="fa fa-check"></i></span>
                                    <span class="btn btn-sm btn-danger" id="sub_task_terminate" title="TERMINATE"><i class="fa fa-times"></i></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Sub Task-2</td>
                                <td>Person-2</td>
                                {{--<td>Date-2</td>--}}
                                <td> Complete </td>
                                <td>
                                    <span class="btn btn-sm btn-success" id="sub_task_complete" title="COMPLETE"><i class="fa fa-check"></i></span>
                                    <span class="btn btn-sm btn-danger" id="sub_task_terminate" title="TERMINATE"><i class="fa fa-times"></i></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Sub Task-3</td>
                                <td>Person-3</td>
                                {{--<td>Date-3</td>--}}
                                <td>Pending</td>
                                <td>
                                    <span class="btn btn-sm btn-success" id="sub_task_complete" title="COMPLETE"><i class="fa fa-check"></i></span>
                                    <span class="btn btn-sm btn-danger" id="sub_task_terminate" title="TERMINATE"><i class="fa fa-times"></i></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Sub Task-4</td>
                                <td>Person-4</td>
                                {{--<td>Date-4</td>--}}
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
                    <span class="btn btn-success" title="COMPLETE" onclick="completeTask();">
                        Complete Task
                    </span>
                    <span class="btn btn-danger" title="TERMINATE" onclick="terminateTask();">
                        Terminate Task
                    </span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    $('select').select2();

    function completeTask() {
        var recurring_task_id = $("#recurring_task_id").val();

        completeRecurringTask(recurring_task_id);
    }

    function terminateTask() {
        var recurring_task_id = $("#recurring_task_id").val();

        terminateRecurringTask(recurring_task_id);
    }

    function getRecurringTaskDetail(recurring_task_id){

        $("#task_id").val('');
        $("#task_name").empty();
        $("#task_description").empty();
        $("#assigned_by").empty();
//        $("#recurring_type_info").empty();
        $("#recurring_date").empty();
        $("#recurring_day").empty();
        $("#reschedule_delivery_date").empty();
        $("#status").empty();

        $.ajax({
            url: "{{ route("get_recurring_task_detail") }}",
            type:'GET',
            data: {_token:"{{csrf_token()}}", recurring_task_id: recurring_task_id},
            dataType: "json",
            success: function (data) {

                $("#recurring_task_id").val(data[0].id);
                $("#task_name").text(data[0].task_name);
                $("#task_description").text(data[0].task_description);
                $("#assigned_by").text(data[0].assigned_by);
                $("#recurring_type_info").text(data[0].recurring_type == 0 ? 'Monthly' : (data[0].recurring_type == 1 ? 'Weekly' : ''));
                $("#recurring_date").text(data[0].recurring_date);

                if(data[0].recurring_type == 0){
                    $("#recurring_day_div").css('display', 'none');
                }
                if(data[0].recurring_type == 1){
                    $("#recurring_day").text(data[0].weekly_recurring_day);
                    $("#recurring_day_div").css('display', 'block');
                }

                $("#status").text(data[0].status == 0 ? 'Terminated' : (data[0].status == 1 ? 'Completed' : 'Pending'));

                recurringSubTaskDetail(recurring_task_id);

                $('#exampleModalLong').modal('show');

            }
        });
    }

    function recurringSubTaskDetail(recurring_task_id) {
        $("#sub_task_row").empty();

        $.ajax({
            url: "{{ route("get_recurring_sub_task_detail") }}",
            type:'GET',
            data: {_token:"{{csrf_token()}}", task_id: recurring_task_id},
            dataType: "json",
            success: function (data_1) {

                for(i=0; i < data_1.length; i++){

                    $("#sub_task_row").append('<tr><td>'+data_1[i].sub_task_name+'</td><td>'+(data_1[i].responsible_person != null ? data_1[i].responsible_person : '')+'</td><td>'+(data_1[i].status==2 ? 'Pending' : (data_1[i].status==1 ? 'Complete' : 'Terminated'))+'</td><td><span class="btn btn-sm btn-success" id="sub_task_complete" onclick="changeRecurringSubTaskStatus('+recurring_task_id+', '+data_1[i].id+', 1)" title="COMPLETE"><i class="fa fa-check"></i></span><span class="btn btn-sm btn-danger" id="sub_task_terminate" title="TERMINATE" onclick="changeRecurringSubTaskStatus('+recurring_task_id+', '+data_1[i].id+', 0)"><i class="fa fa-times"></i></span></td></tr>');

                }

            }
        });

    }

    function changeRecurringSubTaskStatus(recurring_task_id, sub_task_id, status) {
        $.ajax({
            url: "{{ route("recurring_sub_task_status_change") }}",
            type:'POST',
            data: {_token:"{{csrf_token()}}", id: sub_task_id, status: status},
            dataType: "html",
            success: function (data) {

                if(data == 'done'){
                    recurringSubTaskDetail(recurring_task_id);
                }

            }
        });

    }

    function getMyPendingRecurringTasksReport() {
        var task_name_search = $("#task_name_search").val();
        var recurring_type = $("#recurring_type").val();
        var assigned_by = $("#recurring_task_assigned_by").val();

        $("#tbody_id").empty();
        $(".loader").css("display", "block");

        $.ajax({
            url: "{{ route("get_my_pending_recurring_tasks_report") }}",
            type:'POST',
            data: {_token:"{{csrf_token()}}", task_name: task_name_search, assigned_by: assigned_by, recurring_type: recurring_type},
            dataType: "html",
            success: function (data) {

                $("#tbody_id").append(data);
                $(".loader").css("display", "none");

            }
        });

    }

    function completeRecurringTask(id){
        var res_confirm = confirm('Are you sure to complete the task?');

        if(res_confirm == true){

            $.ajax({
                url: "{{ route("complete_recurring_task") }}",
                type:'POST',
                data: {_token:"{{csrf_token()}}", id: id},
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

    function terminateRecurringTask(id) {

        var res = confirm('Do you want to terminate the task?');

        if(res == true){
            $(".loader").css("display", "block");
            var task_id = $("#task_id").val();

            $.ajax({
                url: "{{ route("terminate_recurring_task") }}",
                type:'POST',
                data: {_token:"{{csrf_token()}}", id: id},
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

</script>

@endsection
