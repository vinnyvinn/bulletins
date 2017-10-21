@extends('admin.layout')
@section('title') Make Master @stop


@section('main')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Job Types Master
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Make Master</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Type</h3>
                </div><!-- /.box-header -->
                <div class="box-body">

                    <form action="{{ route('workshop.job-type.store') }}" method="post">
                        {{ csrf_field() }}
                        
                        <div class="form-group label-floating{{ $errors->has('service_type') ? ' has-error' : '' }}">
                            <label for="service_type" class="control-label">Service Type</label>
                            <select class="form-control" id="service_type" name="service_type">
                                <option value="{{ \SmoDav\Support\Constants::WORK_NORMAL }}">{{ \SmoDav\Support\Constants::WORK_NORMAL }}</option>
                                <option value="{{ \SmoDav\Support\Constants::WORK_SERVICE }}">{{ \SmoDav\Support\Constants::WORK_SERVICE }}</option>
                            </select>
                            <span class="help-block">Select the service type that the job type applies to.</span>
                        </div>

                        <div class="form-group label-floating{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">Job Type Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            <span class="help-block">Enter the job type to be created.</span>
                            @if ($errors->has('name'))
                                <br>
                                <h6 class="error-display">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </h6>
                            @endif
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Save">
                            <input type="submit" name="save_new" class="btn btn-primary" value="Save & New">
                            <a href="{{ route('workshop.job-type.index') }}" class="btn btn-danger">
                                Back
                            </a>
                        </div>

                    </form>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div> <!-- /.col -->
    </div>
    <!-- /.row -->

</section><!-- /.content -->


@endsection