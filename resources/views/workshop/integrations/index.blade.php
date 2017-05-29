@extends('admin.layout')

@section('title')
    API Integrations
@endsection

@section('page-css')
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" />
@stop

@section('main')

    <section class="content-header">
        <h1>
            API Integrations
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">API Integrations</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">API Integrations</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped" id="jDataTable">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Endpoint</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($integrations as $integration)
                                <tr>
                                    <td>{{ $integration->name }}</td>
                                    <td>{{ $integration->endpoint }}</td>
                                    <td>
                                        <a href="{{ route('workshop.integrations.edit', $integration->id) }}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div> <!-- /.col -->
        </div>
        <!-- /.row -->
    </section><!-- /.content -->


@endsection



@section('page-js')
    <script src="{{ asset('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $('#jDataTable').DataTable();
    </script>
@endsection
