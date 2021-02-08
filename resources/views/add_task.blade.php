@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('New Task') }}
                        <a class="btn btn-primary float-right" href="{{ url('/uploadCsv') }}" title="Upload CSV">
                            <i class="fa fa-upload"></i> Upload CSV
                        </a>
                    </div>

                    <form method="post">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="task_name" class="form-label">Task Name <span style="color: red">*</span></label>
                                    <input class="form-control" type="text" id="task_name" name="task_name" />
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
                                    <label for="task_assign_to" class="form-label">Assign To <span style="color: red">*</span></label>
                                    <input class="form-control" type="email" id="task_assign_to" name="task_assign_to" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="delivery_date" class="form-label">Delivery Date <span style="color: red">*</span></label>
                                    <input class="form-control" type="date" id="delivery_date" name="delivery_date"  />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success">SAVE</button>
                                    <button type="reset" class="btn btn-danger">RESET</button>
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
