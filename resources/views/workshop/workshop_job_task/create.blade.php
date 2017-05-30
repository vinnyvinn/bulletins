@extends('admin.layout')
@section('title') Job Tasks Master @stop


@section('main')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Job Tasks Master
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Task Master</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Operation</h3>
                </div><!-- /.box-header -->
                <div class="box-body">

                    <form action="{{ route('workshop.job-task.store') }}" method="post">
                        {{ csrf_field() }}
                        
                        <div class="form-group label-floating{{ $errors->has('workshop_job_operation_id') ? ' has-error' : '' }}">
                            <label for="workshop_job_operation_id" class="control-label">Type of Operation</label>
                            <select class="form-control" id="workshop_job_operation_id" name="workshop_job_operation_id">
                                @foreach($operations as $operation)
                                    <option value="{{ $operation->id }}">{{ $operation->name }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">Select the job type that the job operation applies to.</span>
                        </div>

                        <div class="form-group label-floating{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">Task Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            @if ($errors->has('name'))
                                <span>
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @else
                                <span class="help-block">Enter the name of the task to be created.</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Save">
                            <input type="submit" name="save_new" class="btn btn-primary" value="Save & New">
                            <a href="{{ route('workshop.job-task.index') }}" class="btn btn-danger">
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