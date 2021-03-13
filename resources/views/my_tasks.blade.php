@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('My Tasks') }}
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label for="task_assigned_by" class="form-label">Assigned By</label>
                                <select class="form-control" id="task_assigned_by" name="task_assigned_by">
                                    <option value="">Select Email</option>

                                    @foreach($assigned_by_emails as $e)

                                        <option value="{{ $e->assigned_by }}">{{ $e->assigned_by }}</option>

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
                                <span class="btn btn-success mt-4" id="search_btn" onclick="getMyPendingTasksReport()">SEARCH</span>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="mt-3">
                                <div class="loader" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">TASK</th>
                                    <th class="text-center">ASSIGNED BY</th>
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
                                        <td>{{ $k+1 }}</td>
                                        <td>{{ $t->task_name }}</td>
                                        <td class="text-center">{{ $t->assigned_by }}</td>
                                        <td class="text-center">{{ $t->assign_date }}</td>
                                        <td class="text-center">{{ $t->delivery_date }}</td>
                                        <td class="text-center">{{ $t->reschedule_delivery_date }}</td>
                                        <td class="text-center">{{ $t->change_count }}</td>
                                        <td class="text-center">{{ ($t->status == 2 ? 'Pending' : ($t->status == 0 ? 'Terminated' : 'Completed')) }}</td>
                                        <td class="text-center">
                                            <span class="btn btn-sm btn-warning" title="Reschedule Task" onclick="rescheduleDeliveryDate({{ $t->id }})">
                                                <i class="fa fa-clock"></i>
                                            </span>
                                            <span class="btn btn-sm btn-primary" title="View" title="Task Detail" onclick="getAssignedTaskDetail({{ $t->id }})">
                                                <i class="fa fa-eye"></i>
                                            </span>
                                            <span class="btn btn-sm btn-success" title="Meeting" onclick="fixMeeting('{{ $t->id }}' , '{{ $t->assigned_by }}');">
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
                        <label for="assigned_by" class="col-sm-4 col-form-label font-weight-bold">Assigned By:</label>
                        <div class="col-sm-8">
                            <p class="col-form-label" id="assigned_by"></p>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                    <div class="loader" style="display: none;"></div>
                    <span class="btn btn-primary" onclick="rescheduleTaskDeliveryDate()">Save</span>
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
        $("#assigned_by").empty();
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
                $("#assigned_by").text(data.assigned_by);
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

    function rescheduleDeliveryDate(task_id) {
        $("#task_id_2").val('');
        $("#task_id_2").val(task_id);

        $('#exampleModal').modal('show');
    }

    function rescheduleTaskDeliveryDate() {

        var res = confirm('Do you want to reschedule the delivery date?');

        if(res == true){
            $(".loader").css("display", "block");
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

    function fixMeeting(task_id, assigned_by) {

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

                    $("#invite_to").val(assigned_by);

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

    function getMyPendingTasksReport() {
        var assigned_by = $("#task_assigned_by").val();
        var delivery_date_from = $("#delivery_date_from").val();
        var delivery_date_to = $("#delivery_date_to").val();
        var status = 2;

        $("#tbody_id").empty();
        $(".loader").css("display", "block");

        $.ajax({
            url: "{{ route("get_pending_task_filter") }}",
            type:'POST',
            data: {_token:"{{csrf_token()}}", assigned_by: assigned_by, delivery_date_from: delivery_date_from, delivery_date_to: delivery_date_to, status: status},
            dataType: "html",
            success: function (data) {

                $("#tbody_id").append(data);
                $(".loader").css("display", "none");

            }
        });

    }

</script>

@endsection
