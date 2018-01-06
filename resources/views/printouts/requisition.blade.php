<div class="container" style="font-size: 1.1em; margin-top: -40px; background: #fff">
    <div class="row">
        <div class="col-xs-4">
            <img style='display:block; margin: 20px auto;width: 70%' src="{{ asset('/images/logo.jpg') }}" alt="Sanghani">
        </div>
        <div class="col-xs-4 text-center">
            <h1><strong>{{ config('app.name') }}</strong></h1>
            <h4>BUILDING & GENERAL CONTRACTORS</h4>
            <h4>{{ config('app.postal') }}</h4>
            <h4>{{ config('app.location') }}</h4>
            <h2><strong>STORES REQUISITIONS</strong></h2>
        </div>
        <div class="col-xs-4 text-right">
            <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.postal') }}</h4>
            <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.telephone') }}</h4>
            <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.email') }}</h4>
            <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.location') }}</h4>
            <h3 class="text-right"><strong style="color: red !important;">REQ - {{ str_pad($requisition->id, 5, '0', STR_PAD_LEFT) }}</strong></h3>
        </div>
    </div>




    <div class="row" style="border: 1px solid #e5e5e5;">
        <div class="col-xs-2"><h4 class="text-left"><strong>Job Card #</strong></h4></div>
        <div class="col-xs-4"><h4 class="text-right" style="text-align: left">JC-{{ $requisition->job_card_id }}</h4></div>
        <div class="col-xs-2"><h4 class="text-left" style="text-align: right"><strong>Date</strong></h4></div>
        <div class="col-xs-4"><h4 class="text-right" >{{ Carbon\Carbon::parse($requisition->time_in)->format('dS F Y, h:i A') }}</h4></div>

        <div class="col-xs-2"><h4 class="text-left"><strong>KM</strong></h4></div>
        <div class="col-xs-4"><h4 class="text-right" style="text-align: left">{{ number_format($requisition->jobCard->current_km_reading, 0) }}</h4></div>
        <div class="col-xs-2"><h4>&nbsp;</h4></div>
        <div class="col-xs-4"><h4>&nbsp;</h4></div>

        <div class="col-xs-2"><h4 class="text-left"><strong>Time</strong></h4></div>
        <div class="col-xs-4"><h4 class="text-right">{{ $requisition->vehicle_number }}</h4></div>
        <div class="col-xs-2"><h4 class="text-left" style="text-align: right"><strong>Vehicle</strong></h4></div>
        <div class="col-xs-4"><h4 class="text-right">{{ $requisition->raw_data->vehicle_number }}</h4></div>
    </div>

    <h5>The following items have been issued.</h5>

    <div class="row">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th class="text-center">QTY</th>
                <th class="text-center">Item Description</th>
            </tr>
            @foreach($requisition->raw_data->lines as $item)
                <tr>
                    @if($requisition->status == \SmoDav\Support\Constants::STATUS_PENDING)
                        <td class="text-right">{{ number_format($item->approved_quantity, 0) }}</td>
                    @else
                        <td class="text-right">{{ number_format((int) $item->issued_quantity, 0) }}</td>
                    @endif
                    <td>{{ strtoupper($item->item_name) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-xs-12">
            I acknowledge the receipt of the items listed above.
        </div>

        <div class="row">
            <div class="col-xs-2"><h6 class="text-left">Authorized Sign</h6></div>
            <div class="col-xs-4"><h6 class="text-left" style="border-bottom: 1px solid #e5e5e5">&nbsp;</h6></div>
            <div class="col-xs-2"><h6 class="text-left">Time</h6></div>
            <div class="col-xs-4"><h6 class="text-left" style="border-bottom: 1px solid #e5e5e5">&nbsp;</h6></div>
        </div>
        @if($requisition->status == \SmoDav\Support\Constants::STATUS_PENDING)

            <div class="row">
                <div class="col-xs-2"><h6 class="text-left">Issued by:</h6></div>
                <div class="col-xs-4"><h6 class="text-left" style="text-align: left">&nbsp;</h6></div>
                <div class="col-xs-2"><h6 class="text-left">Signature:</h6></div>
                <div class="col-xs-4"><h6 class="text-left" style="border-bottom: 1px solid #e5e5e5">&nbsp;</h6></div>
            </div>
        @else
            <div class="row">
                <div class="col-xs-2"><h6 class="text-left">Requested By:</h6></div>
                <div class="col-xs-4"><h6 class="text-left" style="text-align: left">&nbsp;</h6></div>

                <div class="col-xs-2"><h6 class="text-left">Signature:</h6></div>
                <div class="col-xs-4"><h6 class="text-left" style="border-bottom: 1px solid #e5e5e5">&nbsp;</h6></div>
            </div>
            <div class="row">
                <div class="col-xs-2"><h6 class="text-left">Approved By:</h6></div>
                <div class="col-xs-4"><h6 class="text-left" style="border-bottom: 1px solid #e5e5e5"> &nbsp;</h6></div>

                <div class="col-xs-2"><h6 class="text-left">Signature:</h6></div>
                <div class="col-xs-4"><h6 class="text-left" style="border-bottom: 1px solid #e5e5e5">&nbsp;</h6></div>
            </div>

            <div class="row">
                <div class="col-xs-2"><h6 class="text-left">Issued To</h6></div>
                <div class="col-xs-4"><h6 class="text-left" style="border-bottom: 1px solid #e5e5e5">&nbsp;</h6></div>
                <div class="col-xs-2"><h6 class="text-left">Sign</h6></div>
                <div class="col-xs-4"><h6 class="text-left" style="border-bottom: 1px solid #e5e5e5">&nbsp;</h6></div>
            </div>

        @endif

        <div class="col-xs-12">
            Unless authorized signature with proper date & time appears on this form no goods will be released from the stores.
        </div>
    </div>

</div>
