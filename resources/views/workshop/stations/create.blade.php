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

                        <form action="{{ isset($station) ? route('workshop.stations.update', $station->id) : route('workshop.stations.store') }}"
                              method="post">
                            {{ csrf_field() }}
                            @if(isset($station))
                                {{ method_field('PUT') }}
                            @endif

                            <div class="form-group label-floating{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="control-label">Station Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ $station->name or old('name') }}" required>
                                @if ($errors->has('name'))
                                    <span class="error-display"><strong>{{ $errors->first('name') }}</strong></span>
                                @else
                                    <span class="help-block">Enter the name of the station to be created.</span>
                                @endif
                            </div>

                            <div class="form-group label-floating{{ $errors->has('location') ? ' has-error' : '' }}">
                                <label for="location" class="control-label">Station Location</label>
                                <input type="text" class="form-control" id="location" name="location"
                                       value="{{ $station->location or old('location') }}" required>
                                @if ($errors->has('location'))
                                    <span class="error-display"><strong>{{ $errors->first('location') }}</strong></span>
                                @else
                                    <span class="help-block">Enter the station Location.</span>
                                @endif
                            </div>


                            <div class="form-group {{ $errors->has('consumption_journal_batch') ? 'has-error' : '' }}">
                                <label class=" control-label" for="consumption_journal_batch">Item
                                    Consumption Journal Batch*</label>

                                <select name="consumption_journal_batch" id="consumption_journal_batch"
                                        class="form-control">
                                    @foreach($journalBatches as $batch)
                                        <option {{ old('consumption_journal_batch', trim(Helpers::get_option('consumption_journal_batch'))) == trim($batch->IDInvJrBatches) ? 'selected' : '' }} value="{{ $batch->IDInvJrBatches }}">{{ $batch->cInvJrNumber }}
                                            ({{ $batch->cInvJrDescription }})
                                        </option>
                                    @endforeach
                                </select>
                                {!! $errors->has('consumption_journal_batch')? '<p class="help-block"> '.$errors->first('consumption_journal_batch').' </p>':'' !!}

                            </div>


                            <div class="form-group {{ $errors->has('warehouse_transfer_batch') ? 'has-error' : '' }}">
                                <label class="control-label" for="warehouse_transfer_batch">Warehouse
                                    Transfer Batch*</label>

                                <select name="warehouse_transfer_batch" id="warehouse_transfer_batch"
                                        class="form-control">
                                    @foreach($warehouseBatches as $batch)
                                        <option {{ old('warehouse_transfer_batch', trim(Helpers::get_option('warehouse_transfer_batch'))) == trim($batch->idWhseTransferBatch) ? 'selected' : '' }} value="{{ $batch->idWhseTransferBatch }}">{{ $batch->cBatchNo }}
                                            ({{ $batch->cBatchDescription }})
                                        </option>
                                    @endforeach
                                </select>
                                {!! $errors->has('warehouse_transfer_batch')? '<p class="help-block"> '.$errors->first('warehouse_transfer_batch').' </p>':'' !!}
                            </div>


                            <div class="form-group {{ $errors->has('warehouse_to_transfer') ? 'has-error' : '' }}">
                                <label class="control-label" for="warehouse_to_transfer">Warehouse to
                                    Transfer to*</label>
                                    <select name="warehouse_to_transfer" id="warehouse_to_transfer"
                                            class="form-control">
                                        @foreach($warehouses as $warehouse)
                                            <option {{ old('warehouse_to_transfer', trim(Helpers::get_option('warehouse_to_transfer'))) == trim($warehouse->WhseLink) ? 'selected' : '' }} value="{{ $warehouse->WhseLink }}">{{ $warehouse->Name }}
                                                ({{ $warehouse->Code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('warehouse_to_transfer')? '<p class="help-block"> '.$errors->first('warehouse_to_transfer').' </p>':'' !!}

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
