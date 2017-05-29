<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6">
                        <h3>
                            <strong>LOADING ORDER</strong>
                        </h3>
                    </div>
                    <div class="col-xs-6 text-right">
                        <h3>{{ config('app.name') }}</h3>
                        <h4>{{ config('app.telephone') }}</h4>
                        <h4>{{ config('app.email') }}</h4>
                        <h4>{{ config('app.location') }}</h4>
                    </div>

                    <div class="col-xs-12">
                        <hr>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <h4>
                            This is to authorise the truck <strong>{{ $trip->truck->plate_number }}</strong> driven by
                            <strong>{{ $trip->driver->name }}</strong> to be loaded with
                            a consignment from <strong>{{ $trip->source }}</strong> to <strong>{{ $trip->destination }}</strong>.
                        </h4>
                        <br>
                        <h4>Refer to the details below.</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Voucher No: </strong> {{ $trip->id }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Truck No: </strong> <span class="text-uppercase">{{ $trip->truck->plate_number }}</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Date to be issued: </strong> {{ Carbon\Carbon::parse($trip->trip_date)->format('d F Y') }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Driver: </strong> {{ $trip->driver->name }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Route: </strong> <span class="text-uppercase">{{ $trip->route->source }}</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>To: </strong> <span class="text-uppercase">{{ $trip->route->destination }}</span>
                    </div>
                </div>

                <br>
                <br>

                <div class="row">
                    <div class="col-xs-12">
                        <strong>Supervisors Comment</strong>
                        <br>
                        <p>{{ $trip->preLoadingChecklist->supervisors_comments }}</p>
                    </div>
                </div>

                <br>
                <br>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Voucher Approved By:</strong>
                        <br>
                        {{ $trip->preLoadingChecklist->supervisor->name }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Voucher Processed By: </strong>
                        <br>
                        {{ $trip->preLoadingChecklist->supervisor->name }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Recipient Sign: </strong>
                        <br>
                        .....................................................................
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>
<br>

<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6">
                        <h3>
                            <strong>FUEL VOUCHER</strong>
                            <span> <small>TOP UP</small></span>
                        </h3>
                    </div>
                    <div class="col-xs-6">
                        <div class="text-right">
                            <h3>{{ config('app.name') }}</h3>
                            <h4>{{ config('app.telephone') }}</h4>
                            <h4>{{ config('app.email') }}</h4>
                            <h4>{{ config('app.location') }}</h4>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <hr>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Voucher No: </strong> {{ $trip->id }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Truck No: </strong> <span class="text-uppercase">{{ $trip->truck->plate_number }}</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Date to be issued: </strong> {{ Carbon\Carbon::parse($trip->trip_date)->format('d F Y') }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Journey No: </strong> {{ $trip->id }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Route: </strong> <span class="text-uppercase">{{ $trip->route->source }}</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>To: </strong> <span class="text-uppercase">{{ $trip->route->destination }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Driver: </strong> {{ $trip->driver->name }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Distance: </strong> <span class="text-uppercase">{{ $trip->route->distance }} KMs</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Fuel Qty: </strong> <span class="text-uppercase">{{ $trip->route->fuel_required }} Litres</span>
                    </div>
                </div>

                <br>
                <br>

                <div class="row">
                    <div class="col-xs-12">
                        <strong>Supervisors Comment</strong>
                        <br>
                        <p>{{ $trip->preLoadingChecklist->supervisors_comments }}</p>
                    </div>
                </div>

                <br>
                <br>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Voucher Approved By:</strong>
                        <br>
                        {{ $trip->preLoadingChecklist->supervisor->name }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Voucher Processed By: </strong>
                        <br>
                        {{ $trip->preLoadingChecklist->supervisor->name }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Recipient Sign: </strong>
                        <br>
                        .....................................................................
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>