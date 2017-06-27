@extends('admin.layout')
@section('title') Loading & Offloading Points Master @stop


@section('main')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Loading & Offloading Points Master
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Loading & Offloading Points Master</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Loading & Offloading Points</h3>
                </div><!-- /.box-header -->
                <div class="box-body">

                    <form action="{{ route('workshop.carriage-point.store') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group label-floating{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">Location Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            @if ($errors->has('name'))
                                <span class="error-display"><strong>{{ $errors->first('name') }}</strong></span>
                            @else
                                <span class="help-block">Enter the name of the loading and offloading location to be created.</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <input type="submit" name="save" class="btn btn-success" value="Save">
                            <input type="submit" name="save_new" class="btn btn-primary" value="Save & New">
                            <a href="{{ route('workshop.carriage-point.index') }}" class="btn btn-danger">
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