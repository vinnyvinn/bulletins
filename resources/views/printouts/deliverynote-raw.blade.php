    <div class="container" style="font-size: 1.1em">
        <div class="row">
            <div class="col-xs-3">
                <h4 class="text-left"><strong style="color: red !important;">SHREEJIBAPA</strong></h4>
            </div>
            <div class="col-xs-6 text-center">
                <h2><strong><em style="color: red !important;">Jay Shree Swaminarayan</em></strong></h2>
                <h1><strong>{{ config('app.name') }}</strong></h1>
            </div>

            <div class="col-xs-3">
                <h4 class="text-right"><strong style="color: red !important;">SWAMIBAPA</strong></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <h4 style="font-size: 20px !important;" class="text-left"><strong>Name: </strong> {{ $trip->contract->client->Name }}</h4>
                <h4 style="font-size: 20px !important;" class="text-left"><strong>Address: </strong> {{ $trip->contract->client->Address }}</h4>
                <h4 style="font-size: 20px !important;" class="text-left"><strong>Client Contact: </strong> {{ $trip->contract->client->Telephone }}</h4>
                <h4 style="font-size: 20px !important;" class="text-left"><strong>LPO No: </strong> </h4>
                <h4 style="font-size: 20px !important;" class="text-left"><strong>Route: </strong> {{ $trip->route->source }} <strong>TO</strong> {{ $trip->route->destination }}</h4>
            </div>
            <div class="col-xs-4 text-center">
                <img style='display:block; margin: 0 auto;' src="{{ asset('/images/logo.jpg') }}" alt="Sanghani">
                <h2><strong>DELIVERY NOTE</strong></h2>
            </div>
            <div class="col-xs-4 text-right">
                <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.postal') }}</h4>
                <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.telephone') }}</h4>
                <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.email') }}</h4>
                <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.location') }}</h4>
                <h3 class="text-right"><strong style="color: red !important;">{{ config('delivery_prefix') }} - {{ str_pad($trip->id, 5, '0', STR_PAD_LEFT) }}</strong></h3>


                <div class="pull-right">
                    <h4 class="text-right"><strong>Journey No: </strong> JRNY-{{ $trip->journey->id }}</h4>
                </div>
            </div>
        </div>


        <div class="row"  style="padding: 16px">
            <div class="col-xs-12">
                <div class="row" style="border: 1px solid #e5e5e5; border-style: solid;">
                    <div class="col-xs-4">
                        <h3 class="text-left">
                            <strong>Vehicle Number: </strong><br><br>{{ $trip->truck->plate_number }} - {{ $trip->truck->trailer->plate_number }}
                        </h3>
                    </div>
                    <div class="col-xs-4">
                        <h3 class="text-left">
                            <strong>Lot No: </strong><br><br>{{ $trip->contract->lot_number }}
                        </h3>
                    </div>
                    <div class="col-xs-4">
                        <h3 class="text-left">
                            <strong>Vessel Name:</strong><br><br>{{ $trip->contract->vessel_name }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th colspan="2" class="text-uppercase text-left"><h4><strong>Loading Details</strong></h4></th>
                        <th colspan="2" class="text-uppercase text-left"><h4><strong>Offloading Details</strong></h4></th>
                    </tr>
                    </thead>
                    <tbody style="font-size: 20px !important;">
                    <tr>
                        <td colspan="3"><strong>Product</strong></td>
                        <td class="text-right">{{ $trip->contract->cargoType->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Weighbridge Ticket No</strong></td>
                        <td class="text-right" style="border-right: 1px solid #e5e5e5;">{{ $trip->loading_weighbridge_number }}</td>
                        <td><strong>Weighbridge Ticket No</strong></td>
                        <td class="text-right">{{ $trip->offloading_weighbridge_number ? $trip->offloading_weighbridge_number : '' }}</td>
                    </tr>
                    @if($trip->bags_loaded)
                    <tr>
                        <td><strong>{{ $trip->bags_loaded ? 'No. of Packages' : '' }}</strong></td>
                        <td class="text-right" style="border-right: 1px solid #e5e5e5;">{{ $trip->bags_loaded ?: '' }}</td>
                        <td><strong>{{ $trip->bags_loaded ? 'No. of Packages' : '' }}</strong></td>
                        <td class="text-right">{{ $trip->bags_loaded ?: '' }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Gross Weight</strong></td>
                        <td class="text-right" style="border-right: 1px solid #e5e5e5;">{{ number_format($trip->loading_gross_weight, 2) }} KGs</td>
                        <td><strong>Gross Weight</strong></td>
                        <td class="text-right">{{ $trip->offloading_gross_weight != 0.00 ? number_format($trip->offloading_gross_weight, 2) . ' KGs' : '' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tare Weight</strong></td>
                        <td class="text-right" style="border-right: 1px solid #e5e5e5;">{{ number_format($trip->loading_tare_weight, 2) }} KGs</td>
                        <td><strong>Tare Weight</strong></td>
                        <td class="text-right">{{ $trip->offloading_tare_weight != 0.00 ? number_format($trip->offloading_tare_weight, 2) . ' KGs' : '' }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid #e5e5e5;"><strong>Net Weight</strong></td>
                        <td class="text-right" style="border-right: 1px solid #e5e5e5;border-bottom: 1px solid #e5e5e5">{{ number_format($trip->loading_net_weight, 2) }} KGs</td>
                        <td style="border-bottom: 1px solid #e5e5e5;"><strong>Net Weight</strong></td>
                        <td style="border-bottom: 1px solid #e5e5e5;" class="text-right">{{ $trip->offloading_net_weight != 0.00 ? number_format($trip->offloading_net_weight, 2) . ' KGs' : '' }}</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid #e5e5e5;"><strong>Loading Date</strong></td>
                        <td class="text-right" style="border-right: 1px solid #e5e5e5;border-bottom: 1px solid #e5e5e5">{{ Carbon\Carbon::parse($trip->loading_time)->format('d-m-Y') }}</td>
                        <td style="border-bottom: 1px solid #e5e5e5;"><strong>Offloading Date</strong></td>
                        <td style="border-bottom: 1px solid #e5e5e5;" class="text-right">{{ Carbon\Carbon::parse($trip->offloading_time)->format('d-m-Y') }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>

            <div class="col-xs-6">
                <br>
                <h4 class="text-left">
                    <strong>Supervisor's Sign: ____________________________________________</strong>
                </h4>
                <h4 class="text-left">
                    <strong>Processed By: </strong> {{ $trip->user->first_name }} {{ $trip->user->last_name }}
                </h4>
                <br>
            </div>
            <div class="col-xs-6">
                <br>

                <h4 class="text-left">
                    <strong>Driver's Sign: _________________________________________________</strong>
                </h4>
                <h4 class="text-left">
                    <strong>Driver: </strong> {{ $trip->driver->first_name }}
                </h4>
                <br>
            </div>

            <div class="col-xs-12">
                <p>
                    We, <strong>{{ $trip->contract->client->Name }}</strong>, hereby acknowledge the full receipt n full goods as per above details
                    and certify that the goods have arrived in good order and condition.
                </p>
                <br>
            </div>

            <div class="col-xs-8">
                <strong>Remarks: </strong>__________________________________________________________________________________
                <br>
                <br>_____________________________________________________________________________________________
                <br>
                <br>_____________________________________________________________________________________________
                <br>
                <br>
                <div class="row">
                    <div class="col-xs-7">
                        <h5 class="text-left"><strong>Client's Name: </strong>_____________________________________</h5>
                    </div>
                    <div class="col-xs-5">
                        <h5 class="text-left"><strong>Date: </strong>___________________________</h5>
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="" style="border: 1px; border-style: solid;  height: 100px;">
                    <h5 class="text-left"><strong>Client's Stamp:</strong></h5>
                </div>
                <br>
                <h5 class="text-left"><strong>Client's Signature</strong> __________________________________</h5>
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

        <div class="row">
            <div class="col-xs-4 col-xs-offset-4 text-center">
                <h2><strong>{{ $copyHeader }} Copy</strong></h2>
            </div>
        </div>
    </div>
