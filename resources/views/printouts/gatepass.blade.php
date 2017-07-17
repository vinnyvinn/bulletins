<div class="container">
    <div class="row">
        
        <div class="col-xs-5">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row"><div class="col-xs-6">
                            <img style='display:block;height: 80px' src="/images/logo.jpg" alt="Sanghani">
                        </div>
                        <div class="col-xs-6">
                            <h3 class="text-right">{{ config('app.name')  }}</h3>
                            <h4 class="text-right">GATE PASS</h4>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>S/NO</strong></h5>
                            <h6>PASS-{{ $trip->id }}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>Date</strong></h5>
                            <h6>{{ Carbon\Carbon::parse($trip->gatepass_date)->format('d F Y') }}</h6>
                        </div>


                        <div class="col-xs-6">
                            <h5><strong>M.V. No</strong></h5>
                            <h6>{{ $trip->truck->plate_number }}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>Driver</strong></h5>
                            <h6>{{ $trip->driver->first_name }} {{ $trip->driver->last_name }}</h6>
                        </div>

                        <div class="col-xs-6">
                            <h5><strong>FROM</strong></h5>
                            <h6>{{ $trip->route->source }}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>TO</strong></h5>
                            <h6>{{ $trip->route->destination }}</h6>
                        </div>

                        <div class="col-xs-6">
                            <h5><strong>Cargo</strong></h5>
                            <h6>{{ $trip->cargo }}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>No. of Items</strong></h5>
                            <h6>{{ $trip->deliveryNote->bags_loaded }}</h6>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Narration: </strong> {{ $trip->narration }}</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Approved By: </strong>{{ $trip->creator->first_name }} {{ $trip->creator->last_name }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xs-5 col-xs-offset-2">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row"><div class="col-xs-6">
                            <img style='display:block;height: 80px' src="/images/logo.jpg" alt="Sanghani">
                        </div>
                        <div class="col-xs-6">
                            <h3 class="text-right">{{ config('app.name')  }}</h3>
                            <h4 class="text-right">GATE PASS</h4>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>S/NO</strong></h5>
                            <h6>PASS-{{ $trip->id }}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>Date</strong></h5>
                            <h6>{{ Carbon\Carbon::parse($trip->gatepass_date)->format('d F Y') }}</h6>
                        </div>


                        <div class="col-xs-6">
                            <h5><strong>M.V. No</strong></h5>
                            <h6>{{ $trip->truck->plate_number }}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>Driver</strong></h5>
                            <h6>{{ $trip->driver->first_name }} {{ $trip->driver->last_name }}</h6>
                        </div>

                        <div class="col-xs-6">
                            <h5><strong>FROM</strong></h5>
                            <h6>{{ $trip->route->source }}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>TO</strong></h5>
                            <h6>{{ $trip->route->destination }}</h6>
                        </div>

                        <div class="col-xs-6">
                            <h5><strong>Cargo</strong></h5>
                            <h6>{{ $trip->cargo }}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>No. of Items</strong></h5>
                            <h6>{{ $trip->deliveryNote->bags_loaded }}</h6>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Narration: </strong> {{ $trip->narration }}</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Approved By: </strong>{{ $trip->creator->first_name }} {{ $trip->creator->last_name }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
