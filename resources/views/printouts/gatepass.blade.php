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
                            <h6>{{ $trip->deliveryNote ? $trip->cargo : 'Not Loaded' }}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>No. of Items</strong></h5>
                            <h6>{{ $trip->deliveryNote ? $trip->deliveryNote->bags_loaded : 'Not Loaded' }}</h6>
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
