@extends('admin.layout')
@section('title', 'Breakdown Area Master')

@section('main')

    <section class="content-header">
        <h1>
            Breakdown Area Master
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Breakdown Area Master</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Breakdown Area Master</h3>
                    </div>
                    <div class="box-body">

                        <form action="{{ route('workshop.area.update', $area->id) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}

                            <div class="form-group {{ $errors->has('name')? 'has-error' : '' }}">
                                <label for="name" class="control-label col-sm-3">Name *</label>

                                <div class="col-sm-7">
                                    <input required type="text" name="name" id="name" class="form-control" value="{{ $area->name or old('name') }}">
                                    {!! $errors->has('name')? '<p class="help-block"> '.$errors->first('name').' </p>':'' !!}
                                </div>
                            </div>

                            <br>
                            <br>


                            <div class="form-group">
                                <div class="col-sm-7 col-sm-offset-3">
                                    <button type="submit" name="submit" class="btn btn-success">
                                        <i class="fa fa-plus"></i> Save
                                    </button>

                                    <a href="{{ route('workshop.area.index') }}" class="btn btn-danger">Back</a>
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