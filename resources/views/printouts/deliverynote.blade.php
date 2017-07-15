

    <div class="container">
        <div class="row">
            <div class="col-xs-4">
                <img style='display:block' src="/images/logo.jpg" alt="Sanghani">
                <h3>DELIVERY NOTE</h3>
            </div>
            <div class="col-xs-4 text-center">
                <h1><strong style="color: red !important;">RKS - {{ str_pad($trip->id, 5, '0', STR_PAD_LEFT) }}</strong></h1>
            </div>
            <div class="col-xs-4 text-right">
                <h3>{{ config('app.name') }}</h3>
                <h5>{{ config('app.telephone') }}</h5>
                <h5>{{ config('app.email') }}</h5>
                <h5>{{ config('app.location') }}</h5>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-xs-6">
                <h5><strong>Client Name: </strong> {{ $trip->contract->client->Name }}</h5>
                <h5><strong>Address: </strong> {{ $trip->contract->client->Address }}</h5>
                <h5><strong>Client Contact: </strong> {{ $trip->contract->client->Telephone }}</h5>
                <h5><strong>LPO No: </strong> </h5>
                <h5><strong>Route: </strong> {{ $trip->route->source }} <strong>TO</strong> {{ $trip->route->destination }}</h5>
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <h5><strong>Delivery Date: </strong> {{ Carbon\Carbon::parse($trip->journey->job_date)->format('d F Y') }}</h5>
                    <h5><strong>Journey No: </strong> JRNY-{{ $trip->journey->id }}</h5>
                    <h5><strong>Delivery To: </strong> {{ $trip->route->destination }}</h5>
                    <h5><strong>Weighbridge Ticket No: </strong> {{ $trip->loading_weighbridge_number }}</h5>
                    <h5><strong>Delivery: </strong> {{ $trip->route->destination }}</h5>
                </div>
            </div>

            <div class="col-xs-12">
                <br>
                <br>
                <br>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Loading Details</th>
                        <th>Loading Date: {{ Carbon\Carbon::parse($trip->loading_time)->format('d F Y') }}</th>
                        <th>Offloading Details</th>
                        <th>Offloading Date: {{ $trip->offloading_time ? Carbon\Carbon::parse($trip->offloading_time)->format('d F Y') : '' }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong>Product</strong></td>
                        <td><strong>{{ $trip->bags_loaded ? 'No. of Packages' : '' }}</strong></td>
                        <td><strong>Product</strong></td>
                        <td><strong>{{ $trip->bags_loaded ? 'No. of Packages' : '' }}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ $trip->contract->cargoType->name }}</td>
                        <td>{{ $trip->bags_loaded ?: '' }}</td>
                        <td>{{ $trip->contract->cargoType->name }}</td>
                        <td>{{ $trip->bags_loaded ?: '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Gross Weight</strong></td>
                        <td>{{ number_format($trip->loading_gross_weight, 2) }} KGs</td>
                        <td><strong>Gross Weight</strong></td>
                        <td>{{ $trip->offloading_gross_weight != 0.00 ? number_format($trip->offloading_gross_weight, 2) . ' KGs' : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tare Weight</strong></td>
                        <td>{{ number_format($trip->loading_tare_weight, 2) }} KGs</td>
                        <td><strong>Tare Weight</strong></td>
                        <td>{{ $trip->offloading_tare_weight != 0.00 ? number_format($trip->offloading_tare_weight, 2) . ' KGs' : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Net Weight</strong></td>
                        <td>{{ number_format($trip->loading_net_weight, 2) }} KGs</td>
                        <td><strong>Net Weight</strong></td>
                        <td>{{ $trip->offloading_net_weight != 0.00 ? number_format($trip->offloading_net_weight, 2) . ' KGs' : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Weighbridge Ticket No</strong></td>
                        <td>{{ $trip->loading_weighbridge_number }}</td>
                        <td><strong>Weighbridge Ticket No</strong></td>
                        <td>{{ $trip->offloading_weighbridge_number ? $trip->offloading_weighbridge_number : '' }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>

            <div class="col-xs-8">
                <h5>
                    <strong>Driver: </strong> {{ $trip->driver->first_name }}
                </h5>
                <h5>
                    <strong>Driver's Sign: __________________________________</strong>
                </h5>
                <h5>
                    <strong>Supervisor Name: </strong> {{ $trip->user->first_name }} {{ $trip->user->last_name }}
                </h5>
                <h5>
                    <strong>Supervisor's Sign: _______________________________</strong>
                </h5>

            </div>
            <div class="col-xs-4" style="border: 1px; border-style: solid;">
              <h5>
                  <strong>Vehicle Number: </strong> {{ $trip->truck->plate_number }}
              </h5>
              <h5>
                  <strong>Attached Trailer: {{ $trip->truck->trailer->plate_number }}</strong>
              </h5>
              <h5>
                  <strong>Lot No: {{ $trip->contract->lot_number }}</strong>
              </h5>
              <h5>
                  <strong>Vessel Name: {{ $trip->contract->vessel_name }} </strong>
              </h5>
            </div>

            <div class="col-xs-12">
                <p>
                    We, {{ $trip->contract->client->Name }}, hereby acknowledge the full receipt n full goods as per above details
                    and certify that the goods have arrived in good order and condition.
                </p>
                <br>
                <strong>Remarks: </strong>________________________
            </div>

            <div class="col-xs-4">
                <h5><strong>Date: </strong>_________________________</h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>Name: </strong>_________________________</h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>Signature</strong>_______________________</h5>
                <h5><strong>Stamp:</strong></h5>
                <div class="" style="border: 1px; border-style: solid;  height: 100px;">

                </div>
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
