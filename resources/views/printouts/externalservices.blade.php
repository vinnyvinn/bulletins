<div class="container" style="font-size: 1.1em; margin-top: -40px; background: #fff">
    <div class="row">
        <div class="col-xs-4">
            <img style='display:block; margin: 20px auto;width: 70%' src="{{ asset('/images/logo.jpg') }}"
                 alt="Sanghani">
        </div>
        <div class="col-xs-4 text-center">
            <h1><strong>{{ config('app.name') }}</strong></h1>
            <h4>BUILDING & GENERAL CONTRACTORS</h4>
            <h4>{{ config('app.postal') }}</h4>
            <h4>{{ config('app.location') }}</h4>
        </div>
        <div class="col-xs-4 text-right">
            <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.postal') }}</h4>
            <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.telephone') }}</h4>
            <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.email') }}</h4>
            <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.location') }}</h4>
            <h3 class="text-right"><strong style="color: red !important;">External Services</strong></h3>
        </div>
    </div>
    <div class="row" style="border: 1px solid #e5e5e5;">
        <div class="row">
            <div class="col-xs-2"><h4 class="text-left"><strong>Job Card #</strong></h4></div>
            <div class="col-xs-10"><h4 class="text-right2">JC-{{ $service->job_card_id }}</h4></div>
        </div>
        <div class="row">
            <div class="col-xs-2"><h4 style="text-align: left" class="text-left" style="text-align: right">
                    <strong>Date</strong></h4></div>
            <div class="col-xs-10"><h4
                        class="text-right2">{{ Carbon\Carbon::parse($service->time_in)->format('dS F Y, h:i A') }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-3"><h4 class="text-left"><strong>Mechanic Findings</strong></h4></div>
            <div class="col-xs-8"><h4 class="text-right2">
                    {{$service->mechanic_findings}}
                </h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-2"><h4 class="text-left"><strong>Supplier</strong></h4></div>
            <div class="col-xs-10"><h4
                        class="text-right2">{{($service->vendor)?$service->vendor->Name:''}}</h4></div>
        </div>
        <div class="row">
            <div class="col-xs-3"><h4 class="text-left"><strong>Cost: </strong></h4></div>
            <div class="col-xs-10"><h4 style="text-align: center">{{$service->approximate_cost}}</h4></div>
        </div>
        <div class="row">
            <div class="col-xs-2" class="text-left"><h4><strong>Type: </strong></h4></div>
            <div class="col-xs-4"><h4 class="text-right2">{{$service->type}}</h4>
            </div>

            <div class="row">

                <div class="col-sm-12">
                    @if($service->raw_data->type === 'Parts')
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Make</th>
                                <th>Model</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($service->raw_data->lines as $key=>$item)
                                <tr>
                                    <td>{{$key++}}</td>
                                    <td>{{$item->item_name}}</td>
                                    <td>{{$item->quantity}}</td>
                                    <td>{{$item->make}}</td>
                                    <td>{{$item->model}}</td>
                                </tr>

                            @endforeach()
                            </tbody>
                        </table>
                    @endif()
                </div>

            </div>

        </div>


    </div>
</div>
<style>
    .text-left {
        text-align: left;
    }

    .text-right2 {
        text-align: left;
        margin-left: 120px;
    }
</style>
