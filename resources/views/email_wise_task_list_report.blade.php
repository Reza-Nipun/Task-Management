@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Task List - <span class="font-weight-bold">{{ $email }}</span>
                </div>

                <div class="card-body">
                    <div class="table-responsive tableFixHead">
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
                                    <th class="text-center">COMPLETE DATE</th>
                                    <th class="text-center">TERMINATE DATE</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_id">
                                @foreach($tasks as $k=>$t)

                                    <tr>
                                        <td class="text-center">{{ $k+1 }}</td>
                                        <td class="text-center">{{ $t->task_name }}</td>
                                        <td class="text-center">{{ $t->assigned_by }}</td>
                                        <td class="text-center">{{ $t->assign_date }}</td>
                                        <td class="text-center">{{ $t->delivery_date }}</td>
                                        <td class="text-center">{{ $t->reschedule_delivery_date }}</td>
                                        <td class="text-center">{{ $t->change_count }}</td>
                                        <td class="text-center">{{ $t->status == 0 ? 'Terminated' : ($t->status == 1 ? 'Completed' : 'Pending') }}</td>
                                        <td class="text-center">{{ $t->actual_complete_date }}</td>
                                        <td class="text-center">{{ $t->termination_date }}</td>
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
                        <label for="assigned_to_2" class="col-sm-4 col-form-label font-weight-bold">Assigned To:</label>
                        <div class="col-sm-8">
                            <p class="col-form-label" id="assigned_to_2"></p>
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
                        <label for="status_1" class="col-sm-4 col-form-label font-weight-bold">Status:</label>
                        <div class="col-sm-8">
                            <p class="col-form-label" id="status_1"></p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="complete_date" class="col-sm-4 col-form-label font-weight-bold">Complete Date:</label>
                        <div class="col-sm-8">
                            <p class="col-form-label" id="complete_date"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
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
        $("#assigned_to_2").empty();
        $("#assign_date").empty();
        $("#delivery_date").empty();
        $("#reschedule_delivery_date").empty();
        $("#change_count").empty();
        $("#remarks").empty();
        $("#status_1").empty();

        $.ajax({
            url: "{{ route("get_assigned_task_detail") }}",
            type:'GET',
            data: {_token:"{{csrf_token()}}", task_id: task_id},
            dataType: "json",
            success: function (data) {

                $("#task_id").val(data.id);
                $("#task_name").text(data.task_name);
                $("#task_description").text(data.task_description);
                $("#assigned_to_2").text(data.assigned_to);
                $("#assign_date").text(data.assign_date);
                $("#delivery_date").text(data.delivery_date);
                $("#reschedule_delivery_date").text(data.reschedule_delivery_date);
                $("#change_count").text(data.change_count != null ? data.change_count : 0);
                $("#remarks").text(data.remarks);
                $("#status_1").text(data.status == 0 ? 'Terminated' : (data.status == 1 ? 'Completed' : 'Pending'));
                $("#complete_date").text(data.actual_complete_date);

                $('#exampleModalLong').modal('show');

            }
        });

    }

    function getAssignedTaskReport() {
        var assigned_to = $("#assigned_to").val();
        var assigned_date_from = $("#assigned_date_from").val();
        var assigned_date_to = $("#assigned_date_to").val();
        var status = $("#status").val();

        $("#tbody_id").empty();
        $(".loader").css("display", "block");

        $.ajax({
            url: "{{ route("get_assigned_task_report") }}",
            type:'POST',
            data: {_token:"{{csrf_token()}}", assigned_to: assigned_to, assigned_date_from: assigned_date_from, assigned_date_to: assigned_date_to, status: status},
            dataType: "html",
            success: function (data) {

                $("#tbody_id").append(data);
                $(".loader").css("display", "none");

            }
        });

    }

</script>

@endsection
