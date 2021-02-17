@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Edit User') }}
                    </div>

                    <form action="{{ url('/update_user/'.$user_info->id) }}" method="post">
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
                                    <label for="task_name" class="form-label">Name <span style="color: red">*</span></label>
                                    <input class="form-control" type="text" id="name" name="name" value="{{ $user_info->name }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_description" class="form-label">Email <span style="color: red">*</span></label>
                                    <input class="form-control" type="email" id="email" name="email" value="{{ $user_info->email }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="assign_to" class="form-label">Access Level <span style="color: red">*</span></label>
                                    <select class="form-control" name="access_level" id="access_level">
                                        <option value="">Access Level</option>
                                        <option value="0" @if($user_info->access_level == 0) selected="selected" @endif>Admin</option>
                                        <option value="1" @if($user_info->access_level == 1) selected="selected" @endif>User</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="delivery_date" class="form-label">Assign Task Access <span style="color: red">*</span></label>
                                    <select class="form-control" name="assign_task_access" id="assign_task_access">
                                        <option value="">Assign Task Access</option>
                                        <option value="0" @if($user_info->assign_task_access == 0) selected="selected" @endif>No</option>
                                        <option value="1" @if($user_info->assign_task_access == 1) selected="selected" @endif>Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_assign_to" class="form-label">Status <span style="color: red">*</span></label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">Select Status</option>
                                        <option value="0" @if($user_info->status == 0) selected="selected" @endif>Inactive</option>
                                        <option value="1" @if($user_info->status == 1) selected="selected" @endif>Active</option>
                                    </select>
                                </div>
                            </div>
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
@endsection
