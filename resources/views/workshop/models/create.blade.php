@extends('admin.layout')
@section('title') Make Master @stop


@section('main')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Make Master
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
        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Make Master</h3>
                </div><!-- /.box-header -->
                <div class="box-body">

                    <form action="{{ route('workshop.make.store') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label col-sm-3">Name *</label>

                            <div class="col-sm-7">
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Make Name">
                                {!! $errors->has('name')? '<p class="help-block"> '.$errors->first('name').' </p>':'' !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-7 col-sm-offset-3">
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <i class="fa fa-plus-square-o"></i> Add New Make
                                </button>
                            </div>
                        </div>
                    </form>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div> <!-- /.col -->
    </div>
    <!-- /.row -->

</section><!-- /.content -->


@endsection