@extends('admin.layout')

@section('title')
    {{ ucwords($type) }} Master
@endsection

@section('page-css')
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" />
@stop

@section('main')

    <section class="content-header">
        <h1>
            {{ ucwords($type) }} Master
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ ucwords($type) }} Master</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Edit form -->
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Edit Driver</strong>
                        </div>                        
                        <div class="panel-body">
                            <form action="{{ route('super.employee.update', ['id'=>$driver->id]) }}" id="form" role="form" method="POST" enctype="multipart/form-data">
                                {{method_field('patch')}}
                                  {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input value="{{ $driver->first_name }}" type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>

                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input value="{{ $driver->last_name }}" type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>

                                <div class="form-group">
                                    <label for="identification_type">Identification Type</label>
                                    <select value="{{ $driver->identification_type }}" class="form-control" id="identification_type" name="identification_type" required>
                                        <option value="National ID">National ID</option>
                                        <option value="Passport">Passport</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="identification_number">National ID</label>
                                    <input value="{{ $driver->identification_number }}" type="text" class="form-control" id="identification_number" name="identification_number" required>
                                </div>

                                <div class="form-group">
                                    <label for="dl_number">Drivers License Number</label>
                                    <input value="{{ $driver->dl_number }}" type="text" class="form-control text-uppercase" id="dl_number" name="dl_number">
                                </div>

                                <div class="form-group">
                                    <label for="mobile_phone">Mobile Number</label>
                                    <input value="{{ $driver->mobile_phone }}" type="text" class="form-control" id="mobile_phone" name="mobile_phone">
                                </div>

                                <udf module="Drivers" :state="{{ $driver }}" :uploads="[]"></udf>


                                <div class="form-group">
                                    <button class="btn btn-success">Save</button>
                                    <router-link to="/drivers" class="btn btn-danger">Back</router-link>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /.content -->


@endsection



@section('page-js')
    <script src="{{ asset('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $('#jDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('super.employee.show', $type) }}',
            columns: [
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name' },
                { data: 'mobile_phone', name: 'mobile_phone' },
                { data: 'identification_type', name: 'identification_type' },
                { data: 'identification_number', name: 'identification_number' },
                { data: 'email', name: 'email' },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions' },
            ]
        });
    </script>
@endsection
