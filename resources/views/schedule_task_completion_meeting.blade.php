@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Fix Task Completion Meeting') }}
                    </div>

                    <form action="{{ url('/fix_schedule_task_completion_meeting') }}" method="post">
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
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meeting_date" class="form-label">Meeting Date <span style="color: red">*</span></label>
                                    <input class="form-control" type="date" id="meeting_date" name="meeting_date" required="required" placeholder="YYYY-mm-dd" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meeting_time" class="form-label">Meeting Time <span style="color: red">*</span></label>
                                    <input class="form-control" type="time" id="meeting_time" name="meeting_time" required="required" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="meeting_link" class="form-label">Meeting Link</label>
                                    <input class="form-control" type="text" id="meeting_link" name="meeting_link" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success mt-4">SAVE</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <input class="form-control" type="hidden" id="task_id" name="task_id" readonly="readonly" value="{{ $task_id }}" />
                                    <input class="form-control" type="hidden" id="invite_to" name="invite_to" readonly="readonly" value="{{ $invited_to }}" />
                                </div>
                            </div>
                        </div>

                    </div>
                    </form>
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
                    <span class="btn btn-primary" onclick="rescheduleTaskDeliveryDate()">Save</span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">


        function completeAssignedTask() {

            var res = confirm('Do you want to complete the task?');

            if(res == true){
                var task_id = $("#task_id").val();
                var meeting_id = $("#meeting_id").val();

                $.ajax({
                    url: "{{ route("complete_assigned_task") }}",
                    type:'POST',
                    data: {_token:"{{csrf_token()}}", task_id: task_id},
                    dataType: "html",
                    success: function (data) {

                        if(data == 'done'){

                            $.ajax({
                                url: "{{ route("meeting_complete") }}",
                                type:'POST',
                                data: {_token:"{{csrf_token()}}", meeting_id: meeting_id},
                                dataType: "html",
                                success: function (data_1) {

                                    if(data_1 == 'done'){

                                        window.location.href="{{ route('tasks') }}";

                                    }

                                }
                            });

                        }

                    }
                });
            }

        }

        function terminateAssignedTask() {

            var res = confirm('Do you want to terminate the task?');

            if(res == true){
                var task_id = $("#task_id").val();
                var meeting_id = $("#meeting_id").val();

                $.ajax({
                    url: "{{ route("terminate_assigned_task") }}",
                    type:'POST',
                    data: {_token:"{{csrf_token()}}", task_id: task_id},
                    dataType: "html",
                    success: function (data) {

                        if(data == 'done'){

                            $.ajax({
                                url: "{{ route("meeting_complete") }}",
                                type:'POST',
                                data: {_token:"{{csrf_token()}}", meeting_id: meeting_id},
                                dataType: "html",
                                success: function (data_1) {

                                    if(data_1 == 'done'){

                                        window.location.href="{{ route('tasks') }}";

                                    }

                                }
                            });

                        }

                    }
                });
            }

        }

        function rescheduleDeliveryDate() {
            var task_id = $("#task_id").val();

            $("#task_id_2").val(task_id);

            $('#exampleModal').modal('show');
        }

        function rescheduleTaskDeliveryDate() {

            var res = confirm('Do you want to reschedule the delivery date?');

            if(res == true){
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

    </script>

@endsection
