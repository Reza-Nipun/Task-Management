@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Assigned Tasks Report') }}
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Assigned To</label>
                                <select class="form-control" id="assigned_to" name="assigned_to">
                                    <option value="">Select Email</option>

                                    @foreach($assigned_to_emails as $e)

                                    <option value="{{ $e->assigned_to }}">{{ $e->assigned_to }}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="assigned_date_from" class="form-label">Assign Date From</label>
                                <input class="form-control" type="date" id="assigned_date_from" name="assigned_date_from" placeholder="YYYY-mm-dd" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="assigned_date_to" class="form-label">Assign Date To</label>
                                <input class="form-control" type="date" id="assigned_date_to" name="assigned_date_to" placeholder="YYYY-mm-dd" autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Select Status</option>
                                    <option value="2">Pending</option>
                                    <option value="1">Completed</option>
                                    <option value="0">Terminated</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <span class="btn btn-success mt-4" onclick="getAssignedTaskReport()">SEARCH</span>
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
                                    <th class="text-center">ASSIGNED TO</th>
                                    <th class="text-center">ASSIGNED ON</th>
                                    <th class="text-center">DELIVERY DATE</th>
                                    <th class="text-center">RESCHEDULE DATE</th>
                                    <th class="text-center">CHANGE COUNT</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">TARGET LEAD TIME</th>
                                    <th class="text-center">ACTUAL LEAD TIME</th>
                                    <th class="text-center">COMPLETE DATE</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_id">

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
