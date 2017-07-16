@extends('admin.layout')
@section('title') Stations Master @stop


@section('main')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Stations Master
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Stations Master</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Stations</h3>
                </div><!-- /.box-header -->
                <div class="box-body">

                    <form action="{{ isset($station) ? route('workshop.stations.update', $station->id) : route('workshop.stations.store') }}" method="post">
                        {{ csrf_field() }}
                        @if(isset($station))
                            {{ method_field('PUT') }}
                        @endif

                        <div class="form-group label-floating{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">Station Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $station->name or old('name') }}" required>
                            @if ($errors->has('name'))
                                <span class="error-display"><strong>{{ $errors->first('name') }}</strong></span>
                            @else
                                <span class="help-block">Enter the name of the station to be created.</span>
                            @endif
                        </div>

                        <div class="form-group label-floating{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location" class="control-label">Station Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ $station->location or old('location') }}" required>
                            @if ($errors->has('location'))
                                <span class="error-display"><strong>{{ $errors->first('location') }}</strong></span>
                            @else
                                <span class="help-block">Enter the station Location.</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <input type="submit" name="save" class="btn btn-success" value="Save">
                            <input type="submit" name="save_new" class="btn btn-primary" value="Save & New">
                            <a href="{{ route('workshop.stations.index') }}" class="btn btn-danger">
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
