@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Reschedule Meeting') }}
                    </div>

                    <form action="{{ url('/rescheduling_meeting/'.$meeting_info->id) }}" method="post">
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
                                    <label for="task_name" class="form-label">Meeting Date <span style="color: red">*</span></label>
                                    <input class="form-control" type="date" id="meeting_date" name="meeting_date" value="{{ $meeting_info->meeting_date }}" required="required" placeholder="YYYY-mm-dd" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_description" class="form-label">Meeting Time <span style="color: red">*</span></label>
                                    <input class="form-control" type="time" id="meeting_time" name="meeting_time" value="{{ $meeting_info->meeting_time }}" required="required" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="assign_to" class="form-label">Meeting Link</label>
                                    <input class="form-control" type="text" id="meeting_link" name="meeting_link" value="{{ $meeting_info->meeting_link }}" />
                                </div>
                            </div>
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
@endsection
