@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Users') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>ACCESS LEVEL</th>
                                    <th>ASSIGN TASK ACCESS</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $k => $u)
                                    <tr>
                                        <td>{{ $k+1 }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td>{{ ($u->access_level == 0 ? 'ADMIN' : 'USER') }}</td>
                                        <td>{{ ($u->assign_task_access == 0 ? 'NO' : 'YES') }}</td>
                                        <td>{{ ($u->status == 0 ? 'INACTIVE' : 'ACTIVE') }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" href="{{ url('/editUser') }}" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
