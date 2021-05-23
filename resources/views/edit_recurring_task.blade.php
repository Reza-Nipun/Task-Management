@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Edit Recurring Task') }}
                    </div>

                    <form action="{{ route('update_recurring_task', $recurring_task->id) }}" method="post" enctype="multipart/form-data">
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
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label for="recurring_type" class="form-label">Recurring Type <span style="color: red">*</span></label>
                                    <select class="form-control" name="recurring_type" id="recurring_type" onchange="checkRecurringType()">
                                        <option value="">Select Recurring Type</option>
                                        <option value="0" @if($recurring_task->recurring_type == 0) selected="selected" @endif >Monthly</option>
                                        <option value="1" @if($recurring_task->recurring_type == 1) selected="selected" @endif >Weekly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3" id="last_date_of_month_div" style="display: none;">
                                    <label for="last_date_of_month" class="form-label">Recur on Last Date of Month? <span style="color: red">*</span></label>
                                    <select class="form-control" name="last_date_of_month" id="last_date_of_month" onchange="isLastDateofMonth();">
                                        <option value="">Last Date of Month?</option>
                                        <option value="0" @if($recurring_task->last_date_of_month == 0) selected="selected" @endif >NO</option>
                                        <option value="1" @if($recurring_task->last_date_of_month == 1) selected="selected" @endif >YES</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="weekly_recurring_day_div" style="display: none;">
                                    <label for="weekly_recurring_day" class="form-label">Week Day <span style="color: red">*</span></label>
                                    @if($recurring_task->recurring_type == 1)
                                    <select class="form-control" name="weekly_recurring_day" id="weekly_recurring_day">
                                        <option value="" @if($recurring_task->weekly_recurring_day == "") selected="selected" @endif >Select Day</option>
                                        <option value="sunday" @if($recurring_task->weekly_recurring_day == 'sunday') selected="selected" @endif >Sunday</option>
                                        <option value="monday" @if($recurring_task->weekly_recurring_day == 'monday') selected="selected" @endif >Monday</option>
                                        <option value="tuesday" @if($recurring_task->weekly_recurring_day == 'tuesday') selected="selected" @endif >Tuesday</option>
                                        <option value="wednesday" @if($recurring_task->weekly_recurring_day == 'wednesday') selected="selected" @endif >Wednesday</option>
                                        <option value="thursday" @if($recurring_task->weekly_recurring_day == 'thursday') selected="selected" @endif >Thursday</option>
                                        <option value="friday" @if($recurring_task->weekly_recurring_day == 'friday') selected="selected" @endif >Friday</option>
                                        <option value="saturday" @if($recurring_task->weekly_recurring_day == 'saturday') selected="selected" @endif >Saturday</option>
                                    </select>
                                    @endif
                                    @if($recurring_task->recurring_type == 0)
                                        <select class="form-control" name="weekly_recurring_day" id="weekly_recurring_day">
                                            <option value="">Select Day</option>
                                            <option value="sunday">Sunday</option>
                                            <option value="monday">Monday</option>
                                            <option value="tuesday">Tuesday</option>
                                            <option value="wednesday">Wednesday</option>
                                            <option value="thursday">Thursday</option>
                                            <option value="friday">Friday</option>
                                            <option value="saturday">Saturday</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3" id="monthly_recurring_date_div" style="display: none;">
                                    <label for="monthly_recurring_date" class="form-label">Monthly Recurring Date <span style="color: red">*</span></label>
                                    <select class="form-control" name="monthly_recurring_date" id="monthly_recurring_date">
                                        <option value="">Select Date</option>
                                        @for($i=1;$i<=30;$i++)
                                            <option value="{{ $i }}" @if($recurring_task->monthly_recurring_date == $i) selected="selected" @endif >{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_name" class="form-label">Task Name <span style="color: red">*</span></label>
                                    <input class="form-control" type="text" id="task_name" name="task_name" value="{{ $recurring_task->task_name }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_description" class="form-label">Task Description</label>
                                    <textarea class="form-control" id="task_description" name="task_description">{{ $recurring_task->task_description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="assign_to" class="form-label">Assign To <span style="color: red">*</span></label>
                                    <input class="form-control" type="email" id="assign_to" name="assign_to" value="{{ $recurring_task->assigned_to }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="assign_to" class="form-label">Attachment </label>
                                    <input class="form-control" type="file" id="file" name="file" />
                                    <input class="form-control" type="hidden" id="pre_file_name" name="pre_file_name" value="{{ $recurring_task->attachment }}" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span style="color: red">*</span></label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">Select Status</option>
                                        <option value="0" @if($recurring_task->status == 0) selected="selected" @endif >INACTIVE</option>
                                        <option value="1" @if($recurring_task->status == 1) selected="selected" @endif >ACTIVE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">

                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label for="task_assign_to" class="form-label">Sub-Tasks</label>
                                    <table class="table table-bordered text-center" id="MyTable">
                                        <thead>
                                        <tr>
                                            <th>Task <span style="color: red">*</span></th>
                                            <th>Responsible Person</th>
                                            <th>Delivery</th>
                                            <th title="ADD"><span class="btn btn-sm btn-success" onclick="addSubTaskRow()"><i class="fa fa-plus"></i></span></th>
                                        </tr>
                                        </thead>
                                        <tbody id="sub_task_row">
                                            @foreach($recurring_sub_tasks as $recurring_sub_task)
                                                <tr>
                                                    <td>
                                                        <textarea class="form-control" name="sub_task_name_olds[]" required="required">{{ $recurring_sub_task->sub_task_name }}</textarea>
                                                        <input type="hidden" class="form-control" name="sub_task_ids[]" value="{{ $recurring_sub_task->id }}" />
                                                    </td>
                                                    <td><input type="text" class="form-control" name="responsible_person_olds[]" value="{{ $recurring_sub_task->responsible_person }}" /></td>
                                                    <td><input type="date" class="form-control" name="sub_task_delivery_date_olds[]" value="{{ $recurring_sub_task->delivery_date }}" /></td>
                                                    <td>
                                                        <select class="form-control" name="status_olds[]" required="required">
                                                            <option value="">Select Status</option>
                                                            <option value="0" @if($recurring_sub_task->status == 0) selected="selected" @endif>Inactive</option>
                                                            <option value="1" @if($recurring_sub_task->status == 1) selected="selected" @endif>Active</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success mt-4">UPDATE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $( document ).ready(function() {
            checkRecurringType();

            if('<?php echo $recurring_task->recurring_type;?>' == 0){
                isLastDateofMonth();
            }

        });

        function addSubTaskRow(){
            $("#sub_task_row").append('<tr><td><textarea class="form-control" name="sub_task_name[]" required="required"></textarea></td><td><input type="text" class="form-control" name="responsible_person[]" /></td><td><input type="date" class="form-control" name="sub_task_delivery_date[]" /></td><td title="DELETE"><span class="btn btn-sm btn-danger" id="DeleteButton"><i class="fa fa-archive"></i></span></td></tr>');
        }

        $("#MyTable").on("click", "#DeleteButton", function() {
            $(this).closest("tr").remove();
        });

        function checkRecurringType(){
            var recurring_type = $('#recurring_type').val();

            if(recurring_type == 0){
                $('#last_date_of_month_div').css('display', 'block');
                $('#weekly_recurring_day_div').css('display', 'none');

                if('<?php echo $recurring_task->recurring_type;?>' == 0){
                    isLastDateofMonth();
                }

                $('#weekly_recurring_day option[value=""]').attr("selected", "selected");
            }

            if(recurring_type == 1){
                $('#last_date_of_month_div').css('display', 'none');
                $('#monthly_recurring_date_div').css('display', 'none');
                $('#weekly_recurring_day_div').css('display', 'block');

                $('#last_date_of_month option[value=""]').attr("selected", "selected");
                $('#monthly_recurring_date option[value=""]').attr("selected", "selected");
            }

        }

        function isLastDateofMonth(){
            var last_date_of_month = $('#last_date_of_month').val();

            if(last_date_of_month == 0){
                $('#monthly_recurring_date_div').css('display', 'block');
            }

            if(last_date_of_month == 1){
                $('#monthly_recurring_date_div').css('display', 'none');

                $('#monthly_recurring_date option[value=""]').attr("selected", "selected");
            }
        }
    </script>
@endsection
