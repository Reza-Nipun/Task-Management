@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('My Tasks') }}

                    <div class="btn-toolbar float-right">
                        @if(Auth::user()->assign_task_access == 1)
                            <a class="btn btn-success" href="{{ url('/tasks') }}" title="Assigned Tasks">
                                <i class="fa fa-list"></i> Assigned Tasks
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">TASK</th>
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
                                        <td class="text-center">{{ $t->assign_date }}</td>
                                        <td class="text-center">{{ $t->reschedule_delivery_date }}</td>
                                        <td class="text-center">{{ $t->change_count }}</td>
                                        <td>{{ $t->remarks }}</td>
                                        <td class="text-center">{{ ($t->status == 2 ? 'Pending' : ($t->status == 0 ? 'Terminated' : 'Completed')) }}</td>
                                        <td class="text-center">
                                            <span class="btn btn-sm btn-warning" title="Reschedule Task" onclick="rescheduleDeliveryDate({{ $t->id }})">
                                                <i class="fa fa-clock"></i>
                                            </span>
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
                            <input type="date" class="form-control" name="reschedule_date" id="reschedule_date" />
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
</div>

<script type="text/javascript">

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
            var task_id = $("#task_id_2").val();
            var reschedule_date = $("#reschedule_date").val();

            $.ajax({
                url: "{{ route("reschedule_task_delivery_date") }}",
                type:'POST',
                data: {_token:"{{csrf_token()}}", task_id: task_id, reschedule_date: reschedule_date},
                dataType: "html",
                success: function (data) {

                    if(data == 'done'){

                        location.reload();

                    }

                }
            });
        }
    }

</script>

@endsection
