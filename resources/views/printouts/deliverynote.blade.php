
<div class="container">
    <header class="text-center">
        <h3>DELIVERY NOTE</h3>
        <h3>{{ config('app.name') }}</h3>
        <h5>{{ config('app.telephone') }}</h5>
        <h5>{{ config('app.email') }}</h5>
        <h5>{{ config('app.location') }}</h5>
    </header>
    <br>
    <br>
    <br>

    <div class="row">
        <div class="col-xs-6">
            <h5><strong>Client Name: </strong> {{ $trip->contract->client->Name }}</h5>
            <h5><strong>Address: </strong> {{ $trip->contract->client->Address }}</h5>
            <h5><strong>.</strong> {{ $trip->contract->client->Telephone }}</h5>
            <h5><strong>LPO No: </strong> </h5>
            <h5><strong>Route: </strong> {{ $trip->route->source }} <strong>TO</strong> {{ $trip->route->destination }}</h5>
        </div>
        <div class="col-xs-6">
            <h5><strong>Delivery Note No:</strong> RKS - {{ $trip->id }}</h5>
            <h5><strong>Delivery Date: </strong> {{ Carbon\Carbon::parse($trip->trip_date)->format('d F Y') }}</h5>
            <h5><strong>Journey No: </strong> {{ $trip->id }}</h5>
            <h5><strong>Delivery To: </strong> {{ $trip->route->destination }}</h5>
            <h5><strong>Weighbridge Ticket No: </strong> {{ $trip->deliveryNote->fields->weighbridge_number }}</h5>
            <h5><strong>Delivery: </strong> {{ $trip->route->destination }}</h5>
        </div>

        <div class="col-xs-12">
            <table class="table">
                <thead>
                <tr>
                    <th>Loading Details</th>
                    <th>Loading Date: {{ Carbon\Carbon::parse($trip->deliveryNote->for_date)->format('d F Y') }}</th>
                    <th>Offloading Details</th>
                    <th>Offloading Date: {{ $trip->receiveNote ? Carbon\Carbon::parse($trip->receiveNote->for_date)->format('d F Y') : '' }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><strong>Product</strong></td>
                    <td><strong>No. of Packages</strong></td>
                    <td><strong>Product</strong></td>
                    <td><strong>No. of Packages</strong></td>
                </tr>
                <tr>
                    <td>{{ $trip->deliveryNote->fields->items_loaded }}</td>
                    <td>{{ $trip->deliveryNote->fields->number_of_packages }}</td>
                    <td>{{ $trip->deliveryNote->fields->items_loaded }}</td>
                    <td>{{ $trip->receiveNote ? number_format($trip->receiveNote->fields->number_of_packages, 2) : '' }}</td>
                </tr>
                <tr>
                    <td><strong>Gross Weight</strong></td>
                    <td>{{ number_format($trip->deliveryNote->fields->gross_weight, 2) }} KGs</td>
                    <td><strong>Gross Weight</strong></td>
                    <td>{{ $trip->receiveNote ? number_format($trip->receiveNote->fields->gross_weight, 2) . ' KGs' : '' }}</td>
                </tr>
                <tr>
                    <td><strong>Tare Weight</strong></td>
                    <td>{{ number_format($trip->deliveryNote->fields->tare_weight, 2) }} KGs</td>
                    <td><strong>Tare Weight</strong></td>
                    <td>{{ $trip->receiveNote ? number_format($trip->receiveNote->fields->tare_weight, 2) . ' KGs' : '' }}</td>
                </tr>
                <tr>
                    <td><strong>Net Weight</strong></td>
                    <td>{{ number_format($trip->deliveryNote->fields->gross_weight - $trip->deliveryNote->fields->tare_weight, 2) }} KGs</td>
                    <td><strong>Net Weight</strong></td>
                    <td>{{ $trip->receiveNote ? number_format($trip->receiveNote->fields->gross_weight - $trip->receiveNote->fields->tare_weight, 2) : '' }}</td>
                </tr>
                <tr>
                    <td><strong>Weighbridge Ticket No</strong></td>
                    <td>{{ $trip->deliveryNote->fields->weighbridge_number }}</td>
                    <td><strong>Weighbridge Ticket No</strong></td>
                    <td>{{ $trip->receiveNote ? $trip->receiveNote->fields->weighbridge_number : '' }}</td>
                </tr>
                </tbody>
            </table>

        </div>

        <div class="col-xs-6">
            <h5>
                <strong>Driver: </strong> {{ $trip->driver->name }}
            </h5>
            <h5>
                <strong>Driver's Sign: </strong>_______________________________________________________________________
            </h5>
            <h5>
                <strong>Supervisor Name: </strong>_________________________________________________________________________
            </h5>
            <h5>
                <strong>Vehicle Number: </strong> {{ $trip->truck->plate_number }}
            </h5>
        </div>
        <div class="col-xs-6">
            <h5>
                <strong>Vessel Name: </strong>
            </h5>
            <h5>
                <strong>Lot No: </strong>
            </h5>
            <h5>
                <strong>Attached Trailer: {{ $trip->truck->trailer->trailer_number }}</strong>
            </h5>
        </div>

        <div class="col-xs-12">
            <p>
                We, {{ $trip->contract->client->Name }}, hereby acknowledge the full receipt n full goods as per above details
                and certify that the goods have arrived in good order and condition.
            </p>
            <br>
            <strong>Remarks: </strong>_______________________________________________________________________________________________________________________________________
        </div>

        <div class="col-xs-4">
            <h5><strong>Date: </strong>___________________________________________</h5>
        </div>
        <div class="col-xs-4">
            <h5><strong>Name: </strong>___________________________________________</h5>
        </div>
        <div class="col-xs-4">
            <h5><strong>Signature & Stamp: </strong>________________________________</h5>
        </div>

        <div class="col-xs-12">
            <ul style="list-style-type: decimal">
                <li>Goods transported and handled at owners risk.</li>
                <li>Goods Mentioned above should be INSURED whilst they are transported or handled by us.</li>
                <li>{{ config('app.name') }} should not be liable of any loss, damage or any detention of consignment or any part thereof
                    by or through negligence of the company, its servants, agents or otherwise.
                </li>
                <li>All goods stored and carried at the owners risk and the company will not be liable in any whatsoever for negligence of the company or its servants or agents</li>
                <li>Without Prejudice to the generality of the foregoing, the company will not be responsible of any loss, damage, delay or detention of any
                    goods due to collision, overturning, robbery, theft, civil commotion, riots or disturbances, war or inherent.</li>
                <li>The company shall have a general as well as particular lien on all goods for unpaid accounts.</li>
            </ul>
        </div>
    </div>
</div>

<br>
<br>
<hr>
<br>
<br>
<br>

@if(! $trip->receiveNote)
<div class="container">
    <div class="row">
        <div class="col-xs-5">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <h3 class="text-center">{{ config('app.name')  }}</h3>
                            <h4 class="text-center">GATE PASS</h4>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>S/NO: </strong>{{ $trip->id }}</h5>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>Date: </strong>{{ Carbon\Carbon::parse($trip->trip_date)->format('d F Y') }}</h5>
                        </div>
                        <div class="col-xs-12">
                            <h5><strong>TO: </strong>{{ $trip->route->destination }}</h5>
                        </div>
                        <div class="col-xs-12">
                            <h5><strong>Item Description: </strong></h5>
                            <h5>{{ $trip->deliveryNote->fields->items_loaded }}</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Total No. of Items: </strong>{{ $trip->deliveryNote->fields->number_of_packages }}</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Reason: </strong>______________________________________________</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>M.V. No: </strong>{{ $trip->truck->plate_number }}</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Driver: </strong>{{ $trip->driver->name }}</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Approved By: </strong>{{ $trip->preLoadingChecklist->supervisor->name }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-5 col-xs-offset-2">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <h3 class="text-center">{{ config('app.name')  }}</h3>
                            <h4 class="text-center">GATE PASS</h4>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>S/NO: </strong>{{ $trip->id }}</h5>
                        </div>
                        <div class="col-xs-6">
                            <h5><strong>Date: </strong>{{ Carbon\Carbon::parse($trip->trip_date)->format('d F Y') }}</h5>
                        </div>
                        <div class="col-xs-12">
                            <h5><strong>TO: </strong>{{ $trip->route->destination }}</h5>
                        </div>
                        <div class="col-xs-12">
                            <h5><strong>Item Description: </strong></h5>
                            <h5>{{ $trip->deliveryNote->fields->items_loaded }}</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Total No. of Items: </strong>{{ $trip->deliveryNote->fields->number_of_packages }}</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Reason: </strong>______________________________________________</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>M.V. No: </strong>{{ $trip->truck->plate_number }}</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Driver: </strong>{{ $trip->driver->name }}</h5>
                        </div>

                        <div class="col-xs-12">
                            <h5><strong>Approved By: </strong>{{ $trip->preLoadingChecklist->supervisor->name }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
