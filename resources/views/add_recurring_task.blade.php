@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('New Recurring Task') }}
                    </div>

                    <form action="{{ url('/save_recurring_task') }}" method="post" enctype="multipart/form-data">
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
                                        <option value="0">Monthly</option>
                                        <option value="1">Weekly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3" id="last_date_of_month_div" style="display: none;">
                                    <label for="last_date_of_month" class="form-label">Recur on Last Date of Month? <span style="color: red">*</span></label>
                                    <select class="form-control" name="last_date_of_month" id="last_date_of_month" onchange="isLastDateofMonth();">
                                        <option value="">Last Date of Month?</option>
                                        <option value="0">NO</option>
                                        <option value="1">YES</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="weekly_recurring_day_div" style="display: none;">
                                    <label for="weekly_recurring_day" class="form-label">Week Day <span style="color: red">*</span></label>
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
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3" id="monthly_recurring_date_div" style="display: none;">
                                    <label for="monthly_recurring_date" class="form-label">Monthly Recurring Date <span style="color: red">*</span></label>
                                    <select class="form-control" name="monthly_recurring_date" id="monthly_recurring_date">
                                        <option value="">Select Date</option>
                                        @for($i=1;$i<=30;$i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_name" class="form-label">Task Name <span style="color: red">*</span></label>
                                    <input class="form-control" type="text" id="task_name" name="task_name" value="{{old('task_name')}}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_description" class="form-label">Task Description</label>
                                    <textarea class="form-control" id="task_description" name="task_description">{{old('task_description')}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="assign_to" class="form-label">Assign To <span style="color: red">*</span></label>
                                    <input class="form-control" type="email" id="assign_to" name="assign_to" value="{{old('assign_to')}}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="assign_to" class="form-label">Attachment </label>
                                    <input class="form-control" type="file" id="file" name="file" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success mt-4">SAVE</button>
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

        function checkRecurringType(){
            var recurring_type = $('#recurring_type').val();

            if(recurring_type == 0){
                $('#last_date_of_month_div').css('display', 'block');
                $('#weekly_recurring_day_div').css('display', 'none');

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
