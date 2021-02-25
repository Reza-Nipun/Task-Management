@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Upload Task') }}
                        <a class="btn btn-warning float-right" href="{{ asset('storage/uploads/upload_tasks.xlsx') }}" title="CSV Format File">
                            <i class="fa fa-download"></i> Excel Format
                        </a>
                    </div>

                    <form action="{{ url('/uploading_task_file') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                    @endif

                    @if(Session::has('error_message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error_message') }}</p>
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
                                    <label for="upload_file" class="form-label">Upload File <span style="color: red">*</span></label>
                                    <input class="form-control" type="file" id="upload_file" name="upload_file" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success mt-4">UPLOAD</button>
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
