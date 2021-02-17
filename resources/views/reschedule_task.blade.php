@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Reschedule Task') }}
                    </div>

                    <form action="{{ url('/rescheduling_task/'.$task_info->id) }}" method="post">
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
                                    <label for="task_name" class="form-label">Task Name <span style="color: red">*</span></label>
                                    <input class="form-control" type="text" id="task_name" name="task_name" value="{{ $task_info->task_name }}" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_description" class="form-label">Task Description</label>
                                    <input class="form-control" type="text" id="task_description" name="task_description" value="{{ $task_info->task_description }}" readonly="readonly" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="assign_to" class="form-label">Assign To <span style="color: red">*</span></label>
                                    <input class="form-control" type="email" id="assign_to" name="assign_to" value="{{ $task_info->assigned_to }}" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="delivery_date" class="form-label">Delivery Date <span style="color: red">*</span></label>
                                    <input class="form-control" type="date" id="reschedule_date" name="reschedule_date" value="{{ $task_info->reschedule_delivery_date }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_assign_to" class="form-label">Remarks</label>
                                    <input class="form-control" type="text" id="remarks" name="remarks" value="{{ $task_info->remarks }}" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success mt-4">SAVE</button>
                                    <button type="reset" class="btn btn-danger mt-4">RESET</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
