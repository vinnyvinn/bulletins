@extends('admin.layout')
@section('title') API Integrations @stop


@section('main')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        API Integrations
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">API Integrations</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">API Integration for {{ $integration->name }}</h3>
                </div><!-- /.box-header -->
                <div class="box-body">

                @if(isset($complete))
                    <form action="{{ route('settings_update') }}" method="POST" role="form">
                        {{ csrf_field() }}
                        <div class="form-group label-floating{{ $errors->has('payroll_driver_department') ? ' has-error' : '' }}">
                            <label for="payroll_driver_department" class="control-label">Drivers Department</label>
                            <select name="payroll_driver_department" id="payroll_driver_department" class="form-control">
                                @foreach($departments as $department)
                                    <option {{ old('payroll_driver_department', Helpers::get_option('payroll_driver_department')) == $department->id ? ' selected' : '' }} value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('payroll_driver_department'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('payroll_driver_department') }}</strong>
                                </span>
                            @else
                                <span class="help-block">Select the department from payroll that the drivers belong to.</span>
                            @endif
                        </div>

                        <div class="form-group label-floating{{ $errors->has('payroll_workshop_department') ? ' has-error' : '' }}">
                            <label for="payroll_workshop_department" class="control-label">Workshop Employees Department</label>
                            <select name="payroll_workshop_department" id="payroll_workshop_department" class="form-control">
                                @foreach($departments as $department)
                                    <option {{ old('payroll_workshop_department', Helpers::get_option('payroll_workshop_department')) == $department->id ? ' selected' : '' }} value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('payroll_workshop_department'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('payroll_workshop_department') }}</strong>
                                </span>
                            @else
                                <span class="help-block">Select the department from payroll that the workshop employees belong to.</span>
                            @endif
                        </div>


                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="Save Changes">
                            <a class="btn btn-danger" href="{{ route('workshop.integrations.index') }}">Back</a>
                        </div>
                    </form>
                @else
                    <form action="{{ route('workshop.integrations.update', $integration->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        <div class="form-group label-floating">
                            <label class="control-label">Name</label>
                            <h5>{{ $integration->name }}</h5>
                        </div>


                        <div class="form-group label-floating{{ $errors->has('endpoint') ? ' has-error' : '' }}">
                            <label for="endpoint" class="control-label">CloudWage URL</label>
                            <input required type="text" class="form-control" id="endpoint" name="endpoint" value="{{ old('endpoint', $integration->endpoint) }}">
                            @if ($errors->has('endpoint'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('endpoint') }}</strong>
                                </span>
                            @else
                                <span class="help-block">Enter your CloudWage url: eg. http://demo.cloudwage.com</span>
                            @endif
                        </div>

                        <div id="pat_holder" style="display: none" class="form-group label-floating{{ $errors->has('access_token') ? ' has-error' : '' }}">
                            <label for="access_token" class="control-label">Personal Access Token</label>
                            <textarea rows="10" class="form-control" id="access_token" name="access_token">{{ old('access_token') }}</textarea>
                            @if ($errors->has('access_token'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('access_token') }}</strong>
                                </span>
                            @else
                                <span class="help-block">Enter the generated personal access token.</span>
                            @endif
                        </div>

                        <div id="auth_code_holder">
                            <div class="form-group label-floating">
                                <label class="control-label" for="redirect_uri">Redirect URL</label>
                                <label for="client_id" class="control-label">CloudWage URL</label>

                                <div class="input-group">
                                    <input readonly type="text" class="form-control" id="redirect_uri" name="redirect_uri" value="{{ request()->getSchemeAndHttpHost() . $integration->redirect_uri }}">
                                    <span class="input-group-btn clipboard" data-clipboard-target="#redirect_uri">
                                        <span class="btn btn-primary">
                                            <i class="fa fa-clipboard"></i>
                                        </span>
                                    </span>
                                </div>

                                <span class="help-block">Create a OAuth client with this redirect URL.</span>
                            </div>

                            <div class="form-group label-floating{{ $errors->has('client_id') ? ' has-error' : '' }}">
                                <label for="client_id" class="control-label">Created Client ID</label>
                                <input type="text" class="form-control" id="client_id" name="client_id" value="{{ old('client_id', $integration->client_id) }}">
                                @if ($errors->has('client_id'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('client_id') }}</strong>
                                </span>
                                @else
                                    <span class="help-block">Enter the client ID generated by CloudWage.</span>
                                @endif
                            </div>

                            <div class="form-group label-floating{{ $errors->has('client_secret') ? ' has-error' : '' }}">
                                <label for="client_secret" class="control-label">Created Client Secret</label>
                                <input type="text" class="form-control" id="client_secret" name="client_secret" value="{{ old('client_secret', $integration->client_secret) }}">
                                @if ($errors->has('client_secret'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('client_secret') }}</strong>
                                </span>
                                @else
                                    <span class="help-block">Enter the client secret generated by CloudWage.</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="Save Changes">
                            <a class="btn btn-danger" href="{{ route('workshop.integrations.index') }}">Back</a>
                        </div>
                    </form>
                @endif
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div> <!-- /.col -->
    </div>
    <!-- /.row -->

</section><!-- /.content -->


@endsection


@section('page-js')

    <script>
        $('#grant_type').on('change', function (e) {
            if (e.target.value == 'personal_token') {
                $('#pat_holder').removeAttr('style');
                $('#auth_code_holder').attr('style', 'display:none');
            } else {
                $('#pat_holder').attr('style', 'display:none');
                $('#auth_code_holder').removeAttr('style');
            }
        });
    </script>
@endsection