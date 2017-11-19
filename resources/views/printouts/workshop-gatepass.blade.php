<div class="container">
    <div class="row">
    
        <div class="col-xs-10 col-xs-offset-1">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6">
                            <h3>
                                <img style='display:block;height: 80px' src="/images/logo.jpg" alt="Sanghani">
                                <strong>GATEPASS</strong>
                            </h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <h3>{{ config('app.name') }}</h3>
                            <h5>{{ config('app.telephone') }}</h5>
                            <h5>{{ config('app.email') }}</h5>
                            <h5>{{ config('app.location') }}</h5>
                        </div>
                        <div class="col-xs-12">
                            <hr>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>S/NO</strong></h5>
                            <h6>EXPASS-{{ $pass->id }}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>Date</strong></h5>
                            <h6>{{ Carbon\Carbon::parse($pass->created_at)->format('d F Y') }}</h6>
                        </div>


                        <div class="col-xs-6">
                            <h5><strong>M.V. No</strong></h5>
                            <h6>{{ $pass->service->jobCard->vehicle_number }}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>Driver</strong></h5>
                            <h6>{{ $pass->driver->first_name }} {{ $pass->driver->last_name }}</h6>
                        </div>

                        <div class="col-xs-6">
                            <h5><strong>Service Type</strong></h5>
                            <h6>{{ $pass->type }}</h6>
                        </div>

                        <div class="col-xs-6">
                            <h5><strong>No. of Items</strong></h5>
                            <h6>{{ $pass->total }}</h6>
                        </div>

                        <div class="col-xs-6">
                            <h5><strong>Approximate Cost</strong></h5>
                            <h6>KES {{ number_format($pass->service->approximate_cost, 2) }}</h6>
                        </div>

                        @if($pass->type == 'Parts')
                            <div class="col-xs-12">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Qty</th>
                                        <th>Item</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pass->parts as $part)
                                        <tr>
                                            <td>{{ $part->quantity }}</td>
                                            <td>{{ $part->item_name }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="col-xs-12">
                            <h5><strong>Narration: </strong> {{ $pass->remarks }}</h5>
                        </div>

                        {{--<div class="col-xs-12">--}}
                            {{--<h5><strong>Approved By: </strong>{{ $pass->creator->first_name }} {{ $pass->creator->last_name }}</h5>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
