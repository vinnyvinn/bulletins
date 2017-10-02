

    <div class="container" style="font-size: 1.1em">
        <div class="row">
            <div class="col-xs-4">
                <img style='display:block' src="/images/logo.jpg" alt="Sanghani">
                <h3>DELIVERY NOTE</h3>
            </div>
            <div class="col-xs-4 text-center">
                <h2><strong>{{ $copyHeader }} Copy</strong></h2>
            </div>
            <div class="col-xs-4 text-right">
                <h3>{{ config('app.name') }}</h3>
                <h5>{{ config('app.telephone') }}</h5>
                <h5>{{ config('app.email') }}</h5>
                <h5>{{ config('app.location') }}</h5>
                <h4 class="text-right"><strong style="color: red !important;">KBS - {{ str_pad($trip->id, 5, '0', STR_PAD_LEFT) }}</strong></h4>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-xs-6">
                <h4 class="text-left"><strong>Client Name: </strong> {{ $trip->contract->client->Name }}</h4>
                <h4 class="text-left"><strong>Address: </strong> {{ $trip->contract->client->Address }}</h4>
                <h4 class="text-left"><strong>Client Contact: </strong> {{ $trip->contract->client->Telephone }}</h4>
                <h4 class="text-left"><strong>LPO No: </strong> </h4>
                <h4 class="text-left"><strong>Route: </strong> {{ $trip->route->source }} <strong>TO</strong> {{ $trip->route->destination }}</h4>
            </div>
            <div class="col-xs-6">
                <div class="pull-right">
                    <h4 class="text-right"><strong>Delivery Date: </strong> {{ Carbon\Carbon::parse($trip->loading_time)->format('d F Y') }}</h4>
                    <h4 class="text-right"><strong>Journey No: </strong> JRNY-{{ $trip->journey->id }}</h4>
                    <h4 class="text-right"><strong>Delivery To: </strong> {{ $trip->route->destination }}</h4>
                    <h4 class="text-right"><strong>Weighbridge Ticket No: </strong> {{ $trip->loading_weighbridge_number }}</h4>
                    <h4 class="text-right"><strong>Delivery: </strong> {{ $trip->route->destination }}</h4>
                </div>
            </div>

            <div class="col-xs-12">
                <br>
                <table class="table">
                    <thead>
                    <tr>
                        <th class="text-uppercase text-left"><h4><strong>Loading Details</strong></h4></th>
                        <th class="text-uppercase text-left"><h4><strong>Loading Date: {{ Carbon\Carbon::parse($trip->loading_time)->format('d F Y') }}</strong></h4></th>
                        <th class="text-uppercase text-left"><h4><strong>Offloading Details</strong></h4></th>
                        <th class="text-uppercase text-left"><h4><strong>Offloading Date: {{ $trip->offloading_time ? Carbon\Carbon::parse($trip->offloading_time)->format('d F Y') : '' }}</strong></h4></th>
                    </tr>
                    </thead>
                    <tbody style="font-size: 16px !important;">
                    <tr>
                        <td><strong>Weighbridge Ticket No</strong></td>
                        <td class="text-right">{{ $trip->loading_weighbridge_number }}</td>
                        <td><strong>Weighbridge Ticket No</strong></td>
                        <td class="text-right">{{ $trip->offloading_weighbridge_number ? $trip->offloading_weighbridge_number : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Product</strong></td>
                        <td class="text-right">{{ $trip->contract->cargoType->name }}</td>
                        <td><strong>Product</strong></td>
                        <td class="text-right">{{ $trip->contract->cargoType->name }}</td>
                    </tr>
                    @if($trip->bags_loaded)
                    <tr>
                        <td><strong>{{ $trip->bags_loaded ? 'No. of Packages' : '' }}</strong></td>
                        <td class="text-right">{{ $trip->bags_loaded ?: '' }}</td>
                        <td><strong>{{ $trip->bags_loaded ? 'No. of Packages' : '' }}</strong></td>
                        <td class="text-right">{{ $trip->bags_loaded ?: '' }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Gross Weight</strong></td>
                        <td class="text-right">{{ number_format($trip->loading_gross_weight, 2) }} KGs</td>
                        <td><strong>Gross Weight</strong></td>
                        <td class="text-right">{{ $trip->offloading_gross_weight != 0.00 ? number_format($trip->offloading_gross_weight, 2) . ' KGs' : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tare Weight</strong></td>
                        <td class="text-right">{{ number_format($trip->loading_tare_weight, 2) }} KGs</td>
                        <td><strong>Tare Weight</strong></td>
                        <td class="text-right">{{ $trip->offloading_tare_weight != 0.00 ? number_format($trip->offloading_tare_weight, 2) . ' KGs' : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Net Weight</strong></td>
                        <td class="text-right">{{ number_format($trip->loading_net_weight, 2) }} KGs</td>
                        <td><strong>Net Weight</strong></td>
                        <td class="text-right">{{ $trip->offloading_net_weight != 0.00 ? number_format($trip->offloading_net_weight, 2) . ' KGs' : '' }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>

            <div class="col-xs-8">
                <br>
                <h5>
                    <strong>Driver's Sign: __________________________________</strong>
                </h5>
                <h5>
                    <strong>Driver: </strong> {{ $trip->driver->first_name }}
                </h5>
                <br>
                <h5>
                    <strong>Supervisor's Sign: _______________________________</strong>
                </h5>
                <h5>
                    <strong>Supervisor Name: </strong> {{ $trip->user->first_name }} {{ $trip->user->last_name }}
                </h5>
                <br>

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
                    We, <strong>{{ $trip->contract->client->Name }}</strong>, hereby acknowledge the full receipt n full goods as per above details
                    and certify that the goods have arrived in good order and condition.
                </p>
                <br>
            </div>

            <div class="col-xs-8">
                <strong>Remarks: </strong>_____________________________________________________________________________
                <br>______________________________________________________________________________________
                <br>______________________________________________________________________________________
                <br>
                <div class="row">
                    <div class="col-xs-6">
                        <h5><strong>Name: </strong>_________________________</h5>
                    </div>
                    <div class="col-xs-6">
                        <h5><strong>Date: </strong>_________________________</h5>
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="" style="border: 1px; border-style: solid;  height: 100px;">
                    <h5><strong>Stamp:</strong></h5>
                </div>
                <br>
                <h5><strong>Signature</strong>_______________________</h5>
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
