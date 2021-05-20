@extends('layouts.app')

@section('content')
    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Document List') }}

                        <div class="btn-toolbar float-right">
                            <a class="btn btn-success mr-1" href="http://45.64.134.206/kbank/" target="_blank" title="KNOWLEDGE BANK">
                                Go to KBANK <i class="fa fa-paper-plane"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body mt-2">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="mb-3">
                                    <label for="subject" class="form-label font-weight-bold">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject" />
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="mb-3">
                                    <label for="category" class="form-label font-weight-bold">Category</label>
                                    <select class="form-control" id="category" name="category">
                                        <option value="null">Category</option>
                                        @foreach($categories as $c)
                                            <option value="{{ $c->id }}">{{ $c->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="mb-3">
                                    <label for="applicability" class="form-label font-weight-bold">Applicability</label>
                                    <select class="form-control" id="applicability" name="applicability">
                                        <option value="null">Applicability</option>
                                        @foreach($applicabilities as $a)
                                            <option value="{{ $a->id }}">{{ $a->applicability_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="mb-3">
                                    <label for="document_type" class="form-label font-weight-bold">Document Type</label>
                                    <select class="form-control" id="document_type" name="document_type">
                                        <option value="null">Document Type</option>
                                        @foreach($document_types as $dt)
                                            <option value="{{ $dt->id }}">{{ $dt->document_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="mb-3">
                                    <label for="department" class="form-label font-weight-bold">Department</label>
                                    <select class="form-control" id="department" name="department">
                                        <option value="null">Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="mb-3">
                                    <span class="btn btn-primary mt-4" id="search_btn" onclick="getFilteredDocuments()">SEARCH</span>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="mt-3">
                                    <div class="loader" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mt-3 tableFixHead">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Subject</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Applicability</th>
                                    <th class="text-center">Departments</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Reference Code</th>
                                    <th class="text-center">Version</th>
                                    <th class="text-center">Remarks</th>
                                    <th class="text-center">Action</th>
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

        function getFilteredDocuments() {
            var subject = $("#subject").val();
            subject = subject != '' ? subject : 'null';

            var category = $("#category").val();
            var applicability = $("#applicability").val();
            var document_type = $("#document_type").val();
            var department = $("#department").val();

            var url = "{{ route("document_filter") }}";

            $("#tbody_id").empty();

            $(".loader").css('display', 'block');

            $.ajax({
                type : 'POST',
                url : url,
                data : {_token:"{{csrf_token()}}", subject: subject, category: category, applicability: applicability, document_type: document_type, department: department},
                dataType : 'html',
                success: function (response) {

                    $("#tbody_id").append(response);
                    $(".loader").css('display', 'none');

                }
            });
        }
    </script>
@endsection
