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

                    <div class="table-responsive">
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
                                            <span class="btn btn-sm btn-success" title="COMPLETE" onclick="completeRecurringTask({{ $t->id }});">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            @if($t->attachment != '')
                                                <a href="{{ asset('storage/app/public/uploads/'.$t->attachment) }}" target="_blank" class="btn btn-sm btn-primary" title="VIEW DOCUMENT">
                                                    <i class="fa fa-eye"></i>
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

</div>

<script type="text/javascript">
    $('select').select2();

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

                    console.log(data);

                    if(data == 'done'){
                        $("#search_btn").click();
                    }

                }
            });

        }
    }

</script>

@endsection
