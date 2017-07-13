@extends('admin.layout')

@section('title')
    Stations Master
@endsection

@section('page-css')
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" />
@stop

@section('main')

    <section class="content-header">
        <h1>
            Stations Master
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Stations Master</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Stations Master</h3>
                        <a href="{{ route('workshop.stations.create') }}" class="btn btn-primary pull-right">Add New</a>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped" id="jDataTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                            </thead>
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
        $('#jDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('workshop.stations.index') }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'location', name: 'location' },
                { data: 'actions', name: 'actions', searchable: false },
            ]
        });
    </script>
@endsection
