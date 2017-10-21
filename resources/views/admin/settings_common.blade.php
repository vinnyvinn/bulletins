@extends('admin.layout')
@section('title') {{ empty($pageTitle) ? '' : $pageTitle }}   @stop


@section('main')

        <!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ empty($pageTitle) ? '' : $pageTitle }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Shops</li>
    </ol>
</section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $pageTitle ? $pageTitle : '' }}</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            {!! Form::open(['route'=>'settings_update', 'class' => 'form-horizontal']) !!}

                            <input type="hidden" name="company_name" id="company_name" class="form-control" value="{{ config('app.name') }}" />

                            <div class="form-group {{ $errors->has('invoice_footer_note')? 'has-error' : '' }}">
                                <label class="col-sm-3 control-label" for="invoice_footer_note">Invoice footer note*</label>
                                <div class="col-sm-9">
                                    <input type="text" name="invoice_footer_note" id="invoice_footer_note" class="form-control" value="{{ old('invoice_footer_note')? old('invoice_footer_note') : Helpers::get_option('invoice_footer_note') }}" />
                                    {!! $errors->has('invoice_footer_note')? '<p class="help-block"> '.$errors->first('invoice_footer_note').' </p>':'' !!}
                                </div>
                            </div>

                            <br>
                            <h4><strong>SAGE Settings</strong></h4>
                            <hr>


                            <div class="form-group {{ $errors->has('make_udf') ? 'has-error' : '' }}">
                                <label class="col-sm-3 control-label" for="make_udf">Make User Defined Field*</label>
                                <div class="col-sm-9">

                                    <select name="make_udf" id="make_udf" class="form-control">
                                        @foreach($stkItemColumns as $column)
                                            <option {{ old('make_udf', Helpers::get_option('make_udf')) == $column->idUserDict ? 'selected' : '' }} value="{{ $column->idUserDict }}">{{ $column->cFieldName }} ({{ $column->cFieldDescription }})</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('make_udf')? '<p class="help-block"> '.$errors->first('make_udf').' </p>':'' !!}
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('model_udf') ? 'has-error' : '' }}">
                                <label class="col-sm-3 control-label" for="model_udf">Model User Defined Field*</label>
                                <div class="col-sm-9">

                                    <select name="model_udf" id="model_udf" class="form-control">
                                        @foreach($stkItemColumns as $column)
                                            <option {{ old('model_udf', Helpers::get_option('model_udf')) == $column->idUserDict ? 'selected' : '' }} value="{{ $column->idUserDict }}">{{ $column->cFieldName }} ({{ $column->cFieldDescription }})</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('model_udf')? '<p class="help-block"> '.$errors->first('model_udf').' </p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('spares_item_group') ? 'has-error' : '' }}">
                                <label class="col-sm-3 control-label" for="spares_item_group">Spares Item Group*</label>
                                <div class="col-sm-9">

                                    <select name="spares_item_group" id="spares_item_group" class="form-control">
                                        @foreach($stkItemGroups as $group)
                                            <option {{ old('spares_item_group', trim(Helpers::get_option('spares_item_group'))) == trim($group->StGroup) ? 'selected' : '' }} value="{{ $group->StGroup }}">{{ $group->StGroup }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('spares_item_group')? '<p class="help-block"> '.$errors->first('spares_item_group').' </p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('billable_item_group') ? 'has-error' : '' }}">
                                <label class="col-sm-3 control-label" for="billable_item_group">Contracts Billable Items Group*</label>
                                <div class="col-sm-9">
                                    <select name="billable_item_group" id="billable_item_group" class="form-control">
                                        @foreach($stkItemGroups as $group)
                                            <option {{ old('billable_item_group', trim(Helpers::get_option('billable_item_group'))) == trim($group->StGroup) ? 'selected' : '' }} value="{{ $group->StGroup }}">{{ $group->StGroup }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('billable_item_group')? '<p class="help-block"> '.$errors->first('billable_item_group').' </p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('consumption_journal_batch') ? 'has-error' : '' }}">
                                <label class="col-sm-3 control-label" for="consumption_journal_batch">Item Consumption Journal Batch*</label>
                                <div class="col-sm-9">
                                    <select name="consumption_journal_batch" id="consumption_journal_batch" class="form-control">
                                        @foreach($journalBatches as $batch)
                                            <option {{ old('consumption_journal_batch', trim(Helpers::get_option('consumption_journal_batch'))) == trim($batch->IDInvJrBatches) ? 'selected' : '' }} value="{{ $batch->IDInvJrBatches }}">{{ $batch->cInvJrNumber }}({{ $batch->cInvJrDescription }})</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('consumption_journal_batch')? '<p class="help-block"> '.$errors->first('consumption_journal_batch').' </p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('warehouse_transfer_batch') ? 'has-error' : '' }}">
                                <label class="col-sm-3 control-label" for="warehouse_transfer_batch">Warehouse Transfer Batch*</label>
                                <div class="col-sm-9">

                                    <select name="warehouse_transfer_batch" id="warehouse_transfer_batch" class="form-control">
                                        @foreach($warehouseBatches as $batch)
                                            <option {{ old('warehouse_transfer_batch', trim(Helpers::get_option('warehouse_transfer_batch'))) == trim($batch->idWhseTransferBatch) ? 'selected' : '' }} value="{{ $batch->idWhseTransferBatch }}">{{ $batch->cBatchNo }} ({{ $batch->cBatchDescription }})</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('warehouse_transfer_batch')? '<p class="help-block"> '.$errors->first('warehouse_transfer_batch').' </p>':'' !!}
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('warehouse_to_transfer') ? 'has-error' : '' }}">
                                <label class="col-sm-3 control-label" for="warehouse_to_transfer">Warehouse to Transfer to*</label>
                                <div class="col-sm-9">

                                    <select name="warehouse_to_transfer" id="warehouse_to_transfer" class="form-control">
                                        @foreach($warehouses as $warehouse)
                                            <option {{ old('warehouse_to_transfer', trim(Helpers::get_option('warehouse_to_transfer'))) == trim($warehouse->WhseLink) ? 'selected' : '' }} value="{{ $warehouse->WhseLink }}">{{ $warehouse->Name }} ({{ $warehouse->Code }})</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('warehouse_to_transfer')? '<p class="help-block"> '.$errors->first('warehouse_to_transfer').' </p>':'' !!}
                                </div>
                            </div>



                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-10">
                                        <button type="submit" class="btn btn-danger">Submit</button>
                                    </div>
                                </div>
                            </form>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->


                </div><!-- /.col -->
            </div><!-- /.row -->

        </section><!-- /.content -->

@endsection