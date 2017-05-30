@extends('admin.layout')

@section('title')
    {{ ucwords($type) }} Master
@endsection

@section('page-css')
    <link href="{{ asset('assets/admin/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" />
@stop

@section('main')

    <section class="content-header">
        <h1>
            {{ ucwords($type) }} Master
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ ucwords($type) }} Master</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <driver-form></driver-form>
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
            ajax: '{{ route('super.employee.show', $type) }}',
            columns: [
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name' },
                { data: 'mobile_phone', name: 'mobile_phone' },
                { data: 'identification_type', name: 'identification_type' },
                { data: 'identification_number', name: 'identification_number' },
                { data: 'email', name: 'email' },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions' },
            ]
        });
    </script>
@endsection
