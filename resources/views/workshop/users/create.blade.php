@extends('admin.layout')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/select2.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #000;
        }
    </style>
@endsection

@section('title') Users Master @stop


@section('main')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Users Master
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Users Master</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Users</h3>
                </div><!-- /.box-header -->
                <div class="box-body">

                    <form action="{{ isset($user) ? route('workshop.users.update', $user->id) : route('workshop.users.store') }}" method="post">
                        {{ csrf_field() }}
                        @if(isset($user))
                            {{ method_field('PUT') }}
                        @endif

                        <div class="row">
                            <div class="col-sm-4">

                                <div class="form-group label-floating{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label for="first_name" class="control-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->first_name or old('first_name') }}" required>
                                    @if ($errors->has('first_name'))
                                        <span class="error-display"><strong>{{ $errors->first('first_name') }}</strong></span>
                                    @else
                                        <span class="help-block">Enter the user's first name.</span>
                                    @endif
                                </div>

                                <div class="form-group label-floating{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <label for="last_name" class="control-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $user->last_name or old('last_name') }}">
                                    @if ($errors->has('last_name'))
                                        <span class="error-display"><strong>{{ $errors->first('last_name') }}</strong></span>
                                    @else
                                        <span class="help-block">Enter the user's last name.</span>
                                    @endif
                                </div>

                                <div class="form-group label-floating{{ $errors->has('username') ? ' has-error' : '' }}">
                                    <label for="username" class="control-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="{{ $user->username or old('username') }}" required>
                                    @if ($errors->has('username'))
                                        <span class="error-display"><strong>{{ $errors->first('username') }}</strong></span>
                                    @else
                                        <span class="help-block">Enter the user's username.</span>
                                    @endif
                                </div>

                                <div class="form-group label-floating{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email or old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="error-display"><strong>{{ $errors->first('email') }}</strong></span>
                                    @else
                                        <span class="help-block">Enter the user's email.</span>
                                    @endif
                                </div>
                                
                                <div class="form-group label-floating{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="control-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    @if ($errors->has('password'))
                                        <span class="error-display"><strong>{{ $errors->first('password') }}</strong></span>
                                    @else
                                        <span class="help-block">Enter the user's password.</span>
                                    @endif
                                </div>
                                
                                <div class="form-group label-floating{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label for="password_confirmation" class="control-label">Password Confirmation</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="error-display"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                                    @else
                                        <span class="help-block">Enter the user's password again.</span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                                    <label for="stations" class="control-label">Stations Assigned</label>
                                    <select class="form-control select2" id="stations" name="stations[]" multiple required>
                                        @foreach($stations as $station)
                                            <option value="{{ $station->id }}"{{ isset($user) ? (in_array($station->id, $user->stations) ? ' selected' : (in_array($station->id, old('stations') ?: []) ? ' selected' : '')) : '' }}>{{ $station->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('stations'))
                                        <span class="error-display"><strong>{{ $errors->first('stations') }}</strong></span>
                                    @else
                                        <span class="help-block">Select the station the user can interact with.</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-sm-8 permissions">
                                <h4><strong>Permissions</strong></h4>
                                <div class="row">
                                    @foreach($permissions as $group => $groupPermissions)
                                        <div class="col-sm-12">
                                            <h4>{{ $group == "" ? 'Common Modules' : ucwords($group) }}</h4>
                                        </div>
                                        @foreach($groupPermissions as $permission)
                                            <div class="col-sm-3 {{ $permission->group }}">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="{{ $permission->slug == '*' ? 'all-permissions' : $permission->slug }}" name="permission[{{ $permission->slug }}]" {{ isset($user) ? in_array($permission->slug, $user->permissions) ? ' checked' : '' : '' }}> {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <input type="submit" name="save" class="btn btn-success" value="Save">
                            <input type="submit" name="save_new" class="btn btn-primary" value="Save & New">
                            <a href="{{ route('workshop.users.index') }}" class="btn btn-danger">
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

@section('page-js')
    <script src="{{ asset('assets/admin/plugins/select2/select2.full.js') }}"></script>

    <script>
        $('.select2').select2();

        function updateGroup(group, isChecked) {
            if (isChecked) {
                $('.' + group).removeClass('hidden');
                return;
            }

            $('.' + group).addClass('hidden');
        }

        function updateChecks(isChecked) {
            if (isChecked) {
                updateGroup('transport', true);
                updateGroup('workshop', true);
                $('.permissions').find('input[type=checkbox]').not('#all-permissions').removeAttr('checked').attr('disabled', 'disabled');
                return;
            }

            updateGroup('transport', false);
            updateGroup('workshop', false);
            $('.permissions').find('input[type=checkbox]').not('#all-permissions').removeAttr('checked').removeAttr('disabled');
        }

        var transport = $('#transport-module');
        var workshop = $('#workshop-module');
        var permissions = $('#all-permissions');

        transport.on('change', function () {
            updateGroup('transport', transport.is(':checked'));
        });

        workshop.on('change', function () {
            updateGroup('workshop', workshop.is(':checked'));
        });

        permissions.on('change', function () {
            updateChecks(permissions.is(':checked'));
        });

        updateGroup('transport', transport.is(':checked'));
        updateGroup('workshop', workshop.is(':checked'));
        if (permissions.is(':checked')) {
            updateChecks(permissions.is(':checked'));
        }
    </script>
@endsection