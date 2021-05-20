@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Assigned Recurring Tasks') }}

                    <div class="btn-toolbar float-right">
                        @if(Auth::user()->assign_task_access == 1)
                            <a class="btn btn-success mr-1" href="{{ url('/add_recurring_task') }}" title="Assign Task">
                                <i class="fa fa-plus"></i> Assign Recurring Task
                            </a>
                        @endif

                        <a class="btn btn-primary" href="{{ url('/get_my_recurring_tasks') }}" title="My Recurring Tasks">
                            <i class="fa fa-list"></i> My Recurring Tasks
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="recurring_task_name_search" class="form-label">Task Name</label>
                                <input type="text" class="form-control" name="recurring_task_name_search" id="recurring_task_name_search" placeholder="Search Recurring Task..." />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="recurring_task_assigned_to" class="form-label">Assigned To</label>
                                <select class="form-control" id="recurring_task_assigned_to" name="recurring_task_assigned_to">
                                    <option value="">Select Email</option>

                                    @foreach($assigned_to_email_list as $e)

                                        <option value="{{ $e->assigned_to }}">{{ $e->assigned_to }}</option>

                                    @endforeach
                                </select>
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
                                <span class="btn btn-success mt-4" id="search_btn" onclick="getAssignedRecurringTasks()">SEARCH</span>
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
                                    <th class="text-center">TYPE</th>
                                    <th class="text-center">LAST DATE of MONTH?</th>
                                    <th class="text-center">MONTH RECURRING DATE</th>
                                    <th class="text-center">WEEK RECURRING DAY</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_id">
                                @foreach($assigned_recurring_tasks as $k => $t)
                                    <tr>
                                        <td class="text-center">{{ $k+1 }}</td>
                                        <td>{{ $t->task_name }}</td>
                                        <td>{{ $t->assigned_to }}</td>
                                        <td class="text-center">{{ ($t->recurring_type == 0 ? 'MONTHLY' : ($t->recurring_type == 1 ? 'WEEKLY' : '')) }}</td>
                                        <td class="text-center">
                                            @if($t->recurring_type == 0)
                                                {{ ($t->last_date_of_month == 1 ? 'YES' : ($t->last_date_of_month == 0 ? 'NO' : '')) }}
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $t->monthly_recurring_date }}</td>
                                        <td class="text-center">
                                            @if($t->recurring_type == 1)
                                                @if($t->weekly_recurring_day == 0)
                                                    {{ 'SUNDAY' }}
                                                @elseif($t->weekly_recurring_day == 1)
                                                    {{ 'MONDAY' }}
                                                @elseif($t->weekly_recurring_day == 2)
                                                    {{ 'TUESDAY' }}
                                                @elseif($t->weekly_recurring_day == 3)
                                                    {{ 'WEDNESDAY' }}
                                                @elseif($t->weekly_recurring_day == 4)
                                                    {{ 'THURSDAY' }}
                                                @elseif($t->weekly_recurring_day == 5)
                                                    {{ 'FRIDAY' }}
                                                @elseif($t->weekly_recurring_day == 6)
                                                    {{ 'SATURDAY' }}
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">{{ ($t->status == 1 ? 'ACTIVE' : ($t->status == 0 ? 'INACTIVE' : '')) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('edit_recurring_task', $t->id) }}" target="_blank" class="btn btn-sm btn-warning" title="Edit Recurring Task">
                                                <i class="fa fa-edit"></i>
                                            </a>
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

    function getAssignedRecurringTasks() {
        var recurring_task_name_search = $("#recurring_task_name_search").val();
        var recurring_task_assigned_to = $("#recurring_task_assigned_to").val();
        var recurring_type = $("#recurring_type").val();

        $("#tbody_id").empty();
        $(".loader").css("display", "block");

        $.ajax({
            url: "{{ route("get_assigned_recurring_tasks_filter") }}",
            type:'POST',
            data: {_token:"{{csrf_token()}}", recurring_task_name_search: recurring_task_name_search, recurring_type: recurring_type, recurring_task_assigned_to: recurring_task_assigned_to},
            dataType: "html",
            success: function (data) {

                $("#tbody_id").append(data);
                $(".loader").css("display", "none");

            }
        });

    }

</script>

@endsection
