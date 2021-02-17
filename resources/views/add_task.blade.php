@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('New Task') }}
                        <a class="btn btn-primary float-right" href="{{ url('/upload_task_file') }}" title="Upload Excel">
                            <i class="fa fa-upload"></i> Upload Excel
                        </a>
                    </div>

                    <form action="{{ url('/save_task') }}" method="post">
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
                                    <input class="form-control" type="text" id="task_name" name="task_name" required="required" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_description" class="form-label">Task Description</label>
                                    <input class="form-control" type="text" id="task_description" name="task_description" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="assign_to" class="form-label">Assign To <span style="color: red">*</span></label>
                                    <input class="form-control" type="email" id="assign_to" name="assign_to" required="required" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="delivery_date" class="form-label">Delivery Date <span style="color: red">*</span></label>
                                    <input class="form-control" type="date" id="delivery_date" name="delivery_date" required="required" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_assign_to" class="form-label">Remarks</label>
                                    <input class="form-control" type="text" id="remarks" name="remarks" />
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
