@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('DASHBOARD') }}
                </div>

                <div class="card-body">
                    {{--@if (session('status'))--}}
                        {{--<div class="alert alert-success" role="alert">--}}
                            {{--{{ session('status') }}--}}
                        {{--</div>--}}
                    {{--@endif--}}

                    {{--{{ __('You are logged in!') }}--}}

                    <div class="row text-center justify-content-center">
                        <div class="col-md-3 bg-danger mr-5">
                            <h1 class="card-title text-white">MY TASKS</h1>
                            <h2 class="card-title text-white">
                                <a href="{{ url('/my_tasks') }}" class="badge badge-light">{{ $my_pending_task_count }}</a>
                            </h2>
                        </div>
                        <div class="col-md-3 bg-warning mr-5">
                            <h1 class="card-title">ASSIGNED
                                </h1>
                            <h2 class="card-title">
                                <a href="{{ url('/tasks') }}" class="badge badge-light">{{ $assigned_pending_task_count }}</a>
                                @if(Auth::user()->assign_task_access == 1)
                                    <a class="btn btn-sm btn-success" href="{{ url('/add_task') }}" title="Assign Task">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                @endif
                            </h2>
                        </div>
                        <div class="col-md-3 bg-info">
                            <h1 class="card-title text-white">MEETING</h1>
                            <h2 class="card-title text-white">
                                <a href="{{ url('/meetings') }}" class="badge badge-light">{{ $meeting_pending_count }}</a>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Recurring Tasks') }}
                </div>

                <div class="card-body">
                    {{--@if (session('status'))--}}
                    {{--<div class="alert alert-success" role="alert">--}}
                    {{--{{ session('status') }}--}}
                    {{--</div>--}}
                    {{--@endif--}}

                    {{--{{ __('You are logged in!') }}--}}

                    <div class="row text-center justify-content-center">
                        <div class="col-md-3 bg-danger mr-5">
                            <h1 class="card-title text-white">
                                <a href="{{ url('/get_my_recurring_tasks') }}" class="badge badge-light">MY TASKS</a>
                            </h1>
                        </div>
                        <div class="col-md-3 bg-warning mr-5">
                            <h1 class="card-title">
                                <a href="{{ url('/get_assigned_recurring_tasks') }}" class="badge badge-light">ASSIGNED</a>
                                @if(Auth::user()->assign_task_access == 1)
                                    <a class="btn btn-sm btn-success" href="{{ url('/add_recurring_task') }}" title="Assign Task">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                @endif
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
