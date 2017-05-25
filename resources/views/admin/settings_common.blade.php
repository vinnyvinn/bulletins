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