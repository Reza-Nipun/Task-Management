@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Scheduled Meetings') }}
                </div>

                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                @endif

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">TASK</th>
                                    <th class="text-center">DELIVERY DATE</th>
                                    <th class="text-center">RESCHEDULE DATE</th>
                                    <th class="text-center">CHANGE COUNT</th>
                                    <th class="text-center">MEETING DATE</th>
                                    <th class="text-center">MEETING TIME</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($meetings as $k => $m)
                                    <tr>
                                        <td>{{ $k+1 }}</td>
                                        <td>{{ $m->task_name }}</td>
                                        <td class="text-center">{{ $m->delivery_date }}</td>
                                        <td class="text-center">{{ $m->reschedule_delivery_date }}</td>
                                        <td class="text-center">{{ $m->change_count }}</td>
                                        <td class="text-center">{{ $m->meeting_date }}</td>
                                        <td class="text-center">{{ $m->meeting_time }}</td>
                                        <td class="text-center">{{ ($m->status == 2 ? 'Completed' : ($m->status == 1 ? 'Active' : 'Cancelled')) }}</td>
                                        <td class="text-center">
                                            @if($m->status == 1)
                                                <a class="btn btn-sm btn-warning" title="Edit Meeting" href="{{ url('edit_meeting/'.$m->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif
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

</div>

<script type="text/javascript">

</script>

@endsection
