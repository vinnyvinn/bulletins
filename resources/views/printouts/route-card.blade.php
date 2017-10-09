<link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
<style>
    * { background: #fff; color: #000; }

    section {
        border-bottom:2px solid #000;
        padding-top: 5px;
    }
    h4 { text-align: left}
    section h5 { margin-top: 0}
    .pt5 {padding-top: 5px}
    .line {border-bottom: 1px solid #000}
    .table-bordered > thead > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > tfoot > tr > td {border: 1px solid #000 !important;}
    @media print {.table-bordered > thead > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > tfoot > tr > td {border: 1px solid #000 !important;} }
</style>
<div class="container-fluid" style="margin-top: -20px">
    <section>
        <div class="row">
            <div class="col-xs-4">
                <img class="img-responsive pull-left" src="/images/logo.jpg" alt="Sanghani">
            </div>
            <div class="col-xs-4">
                <h3 class="text-center" style="margin-top: 0"><strong>ROUTE CARD</strong></h3>
                <h4 class="text-center"><strong>S/N {{ str_pad($journey->id, 6, '0', STR_PAD_LEFT) }}</strong></h4>
                <h5 class="text-center"><strong>JRN-{{ $journey->id }}</strong></h5>
                <h5 class="text-center"><strong>{{ Carbon\Carbon::parse($journey->job_date)->format('dS F Y') }}</strong></h5>
            </div>
            <div class="col-xs-4 text-right">
                <h3 style="margin-top: 0"><strong>{{ config('app.name') }}</strong></h3>
                <h5>{{ config('app.postal') }}</h5>
                <h5>{{ config('app.location') }}</h5>
                <h5>{{ config('app.telephone') }}</h5>
                <h5>{{ config('app.email') }}</h5>
            </div>
        </div>
    </section>

    <section>
        <div class="row">
            <div class="col-xs-12">
                <h5><strong>TRUCK DETAILS</strong></h5>
            </div>
            <div class="col-xs-3">
                <h4><strong>TRUCK:</strong> {{ $journey->truck->plate_number }}</h4>
            </div>
            <div class="col-xs-3">
                <h4><strong>TRAILER:</strong> {{ $journey->truck->trailer->plate_number }}</h4>
            </div>
            <div class="col-xs-6">
                <h4><strong>DRIVER:</strong> {{ $journey->driver->first_name }}  {{ $journey->driver->last_name }}</h4>
            </div>

        </div>
    </section>

    <section>
        <div class="row">
            <div class="col-xs-12">
                <h5><strong>PORT OF LOADING</strong></h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>ARRIVAL DATE:</strong> {{ Carbon\Carbon::parse($card->arrival_date)->format('d/m/Y') }}</h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>ARRIVAL TIME:</strong> {{ Carbon\Carbon::parse($card->arrival_time)->format('h:i A') }}</h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>PLACE:</strong> {{ $journey->station->name }}</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <h5><strong>LOADING: </strong>{{ Carbon\Carbon::parse($journey->delivery->loading_time)->format('d/m/Y') }}</h5>
            </div>

            <div class="col-xs-4">
                <h5><strong>LOADING TIME: </strong> {{ Carbon\Carbon::parse($journey->delivery->loading_time)->format('h:i A') }}</h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>DELIVERY NO: </strong> {{ config('app.delivery_prefix') }} - {{ str_pad($journey->delivery->id, 5, '0', 0) }}</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <h5><strong>INSPECTION NO: </strong>INSP-{{ str_pad($journey->inspection->id, 5, '0', 0) }}</h5>
            </div>

            <div class="col-xs-4">
                <h5><strong>PRODUCT: </strong> {{ $journey->contract->cargoType->name }}</h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>DESTINATION: </strong> {{ $journey->route->destination }}</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <h5><strong>DEPARTURE: </strong>{{ Carbon\Carbon::parse($card->departure_date)->format('d/m/Y') }}</h5>
            </div>

            <div class="col-xs-4">
                <h5><strong>DEPARTURE TIME: </strong> {{ Carbon\Carbon::parse($card->departure_time)->format('h:i A') }}</h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>AUTHORIZATION:</strong> {{ $card->user->name }}</h5>
            </div>
            <div class="col-xs-6">
                <h5><strong>SIGNATURE:</strong><div class="line"></div></h5>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-12">
                <div class="line">
                    <div class="row">
                        <div class="col-xs-8"><h5><strong>TOOLBOX TALK</strong></h5></div>
                        <div class="col-xs-4"><h5><strong>TOPIC</strong></h5></div>
                    </div>
                </div>

                <div class="line">
                    <div class="row">
                        <div class="col-xs-12">
                            &nbsp;
                        </div>
                    </div>
                </div>
                <div class="line">
                    <div class="row">
                        <div class="col-xs-12">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

    </section>

    <section>
        <h4 style="margin-top: 0" class="text-center"><strong>JOURNEY PLANNER</strong></h4>
    </section>

    <section>
        <h4 style="margin-top: 0">
            <strong>{{ $journey->route->source }} - {{ $journey->route->destination }}</strong>
            <small>(TO BE COMPLETED BY DRIVERS AND CLERKS)</small>
        </h4>

        <table class="table table-bordered">
            <tr>
                <th rowspan="2" class="text-center">DATE</th>
                <th rowspan="2" class="text-center">LOCATION</th>
                <th colspan="2" class="text-center">ESTIMATED</th>
                <th colspan="2" class="text-center">ACTUAL</th>
                <th rowspan="2" class="text-center">REMARKS/STAMP</th>
            </tr>
            <tr>
                <th class="text-center">ARRIVAL</th>
                <th class="text-center">DEPARTURE</th>
                <th class="text-center">ARRIVAL</th>
                <th class="text-center">DEPARTURE</th>
            </tr>
            <tr>
                <td>{{ Carbon\Carbon::parse($card->depature_date)->format('d/m/Y') }}</td>
                <td class="text-uppercase">company yard</td>
                <td class="text-center">{{ Carbon\Carbon::parse($card->arrival_time)->format('h:i A') }}</td>
                <td class="text-center">{{ Carbon\Carbon::parse($card->departure_time)->format('h:i A') }}</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
            </tr>
            <?php $depature = Carbon\Carbon::parse($card->depature_date . ' ' . $card->departure_time); ?>
            @foreach($journey->route->checkpoints as $checkpoint)
                <?php
                    $expected = $depature->copy()->addMinutes($checkpoint->pivot->minutes);
                    $depature = $expected->copy()->addMinutes(20);
                ?>
                <tr>
                    <td>{{ $depature->format('d/m/Y') }}</td>
                    <td class="text-uppercase">{{ $checkpoint->name }}</td>
                    <td class="text-center">{{ $expected->format('h:i A') }}</td>
                    <td class="text-center">{{ $depature->format('h:i A') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </table>

    </section>

    <section>
        <div class="row">
            <div class="col-xs-12">
                <h5><strong>PORT OF DISCHARGE <small>(TO BE COMPLETED BY A SAFETY MARSHALL ON SITE)</small></strong></h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>ARRIVAL DATE:</strong> <div class="line"></div></h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>ARRIVAL TIME:</strong> <div class="line"></div></h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>PLACE:</strong> <div class="line"></div></h5>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <h5><strong>OFFLOADING: </strong> <div class="line"></div></h5>
            </div>

            <div class="col-xs-4">
                <h5><strong>LOADING TIME: </strong> <div class="line"></div></h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>DELIVERY NO: </strong>  <div class="line"></div></h5>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <h5><strong>INSPECTION NO: </strong> <div class="line"></div></h5>
            </div>

            <div class="col-xs-4">
                <h5><strong>PRODUCT: </strong>  <div class="line"></div></h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>PORT: </strong>  <div class="line"></div></h5>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <h5><strong>DEPARTURE: </strong> <div class="line"></div></h5>
            </div>

            <div class="col-xs-4">
                <h5><strong>DEPARTURE TIME: </strong>  <div class="line"></div></h5>
            </div>
            <div class="col-xs-4">
                <h5><strong>AUTHORIZATION:</strong>  <div class="line"></div></h5>
            </div>
            <div class="col-xs-6">
                <h5><strong>SIGNATURE:</strong>  <div class="line"></div></h5>
            </div>
        </div>

        <br>

    </section>

    <section>
        <h4 style="margin-top: 0" class="text-center">
            <strong>EMERGENCY CONTACTS 0722519444 / 0731339119 / 0731339100</strong>
        </h4>
    </section>
</div>

<div class="page-break"></div>

<section>
    <h4 style="margin-top: 0" class="text-center"><strong>JOURNEY PLANNER</strong></h4>
</section>

<div class="container-fluid">
    <section>
        <h4 style="margin-top: 0">
            <strong>{{ $journey->route->destination }} - {{ $journey->route->source }}</strong>
            <small>(TO BE COMPLETED BY DRIVERS AND CLERKS)</small>
        </h4>

        <table class="table table-bordered">
            <tr>
                <th rowspan="2" class="text-center">DATE</th>
                <th rowspan="2" class="text-center">LOCATION</th>
                <th colspan="2" class="text-center">ESTIMATED</th>
                <th colspan="2" class="text-center">ACTUAL</th>
                <th rowspan="2" class="text-center">REMARKS/STAMP</th>
            </tr>
            <tr>
                <th class="text-center">ARRIVAL</th>
                <th class="text-center">DEPARTURE</th>
                <th class="text-center">ARRIVAL</th>
                <th class="text-center">DEPARTURE</th>
            </tr>
            <tr>
                <td></td>
                <td class="text-uppercase">company yard</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
            </tr>
            @foreach($journey->route->checkpoints as $checkpoint)
                <tr>
                    <td></td>
                    <td class="text-uppercase">{{ $checkpoint->name }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </table>

    </section>

    <section>
        <h4 class="text-center"><strong>REMARKS AND POTENTIAL INCIDENCES</strong></h4>
    </section>

    <section>
        <h3 class="line">&nbsp;</h3>
        <h3 class="line">&nbsp;</h3>
        <h3 class="line">&nbsp;</h3>
        <h3 class="line">&nbsp;</h3>
        <h3 class="line">&nbsp;</h3>
        <h3 class="line">&nbsp;</h3>
        <h3>&nbsp;</h3>
    </section>

    <section>
        <h4 class="text-center"><strong>DRIVER'S CODE OF CONDUCT</strong></h4>
    </section>

    <section>
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-bordered">
                    <tr>
                        <td width="50%">Speed Limit 75KM/HR.</td>
                        <td width="50%">Put on Reflective Jacket.</td>
                    </tr>
                    <tr>
                        <td>Do not carry unauthorized passengers.</td>
                        <td>Put on safety belt.</td>
                    </tr>
                    <tr>
                        <td>Driving hours 5.30am to 8.00pm.</td>
                        <td>Ensure journey plan is stamped at all check points.</td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <br>

    </section>

    <section>
        <h4 style="margin-top: 0" class="text-center">
            <strong>EMERGENCY CONTACTS 0722519444 / 0731339119 / 0731339100</strong>
        </h4>
    </section>
</div>


<script>
    window.print();
    setTimeout(function () {
        window.close();
    }, 500);
</script>