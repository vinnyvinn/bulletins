@extends('admin.layout')

@section('title')
    DRIVERS
@endsection

@section('page-css')
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" />
@stop

@section('main')

    <section class="content-header">
        <h1>
            DRIVERS
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $driver->first_name }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="container">
            <div class="col-md-8 col-md-offset-1">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <a href="{{ route('super.employee.index') }}"><button type="button" name="button" class="btn btn-primary btn-xs">Back</button></a>
                  <strong>{{ $driver->first_name }} {{ $driver->last_name }}</strong>
                </div>

              <div class="panel-body">
                  <div class="row">
                      <div class="col-sm-6">
                          <h5><strong>First Name</strong></h5>
                          {{ $driver->first_name }}
                          <hr>

                          <h5><strong>Last Name</strong></h5>
                          {{ $driver->last_name }}
                          <hr>

                          <h5><strong>{{ $driver->identification_type }}</strong></h5>
                          {{ $driver->identification_number }}
                          <hr>
                      </div>
                      <div class="col-sm-6">
                          <h5><strong>Driving License</strong></h5>
                          {{ $driver->dl_number }}
                          <hr>

                          <h5><strong>Mobile</strong></h5>
                          {{ $driver->mobile_phone }}
                          <hr>
                      </div>
                  </div>

                  <show-udfs module="Drivers" :state="{{ $driver }}"></show-udfs>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
    </section><!-- /.content -->


@endsection



@section('page-js')

@endsection
