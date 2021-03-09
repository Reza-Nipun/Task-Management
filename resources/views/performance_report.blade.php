@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Performance Report') }}
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Email Address</label>
                                <select class="form-control" id="assigned_to" name="assigned_to">
                                    <option value="">Select Email</option>

                                    @foreach($assigned_to_emails as $e)

                                    <option value="{{ $e->assigned_to }}">{{ $e->assigned_to }}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="assigned_date_from" class="form-label">Assign Date From</label>
                                <input class="form-control" type="date" id="assigned_date_from" name="assigned_date_from" autocomplete="off" placeholder="YYYY-mm-dd" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <label for="assigned_date_to" class="form-label">Assign Date To</label>
                                <input class="form-control" type="date" id="assigned_date_to" name="assigned_date_to" autocomplete="off" placeholder="YYYY-mm-dd" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="mb-3">
                                <span class="btn btn-success mt-4" onclick="getPerformanceReport()">SEARCH</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Total Tasks</th>
                                    <th class="text-center">Pending Tasks</th>
                                    <th class="text-center">Terminated Tasks</th>
                                    <th class="text-center">First Time Delivery</th>
                                    <th class="text-center">Not First Time Delivery</th>
                                    <th class="text-center">Delivery after 3 or more reschedule</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_id">

                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>



</div>

<script type="text/javascript">
    $('select').select2();

    function getPerformanceReport() {
        var assigned_to = $("#assigned_to").val();
        var assigned_date_from = $("#assigned_date_from").val();
        var assigned_date_to = $("#assigned_date_to").val();

        $("#tbody_id").empty();

        $.ajax({
            url: "{{ route("get_performance_report") }}",
            type:'POST',
            data: {_token:"{{csrf_token()}}", assigned_to: assigned_to, assigned_date_from: assigned_date_from, assigned_date_to: assigned_date_to},
            dataType: "html",
            success: function (data) {

                $("#tbody_id").append(data);

            }
        });

    }

</script>

@endsection
