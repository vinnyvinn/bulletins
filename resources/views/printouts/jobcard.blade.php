<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'appname' => config('app.name'),
            'user' => Auth::id(),
            'station_id' => session('station_id', 0),
            'contract_id' => session('contract_id', 0)
        ]) !!};
    </script>

    <style>
        * {
            font-size: 1.2em;
        }
    </style>
</head>
<body>

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
                <h2><strong>JOB CARD</strong></h2>
            </div>
            <div class="col-xs-4 text-right">
                <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.postal') }}</h4>
                <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.telephone') }}</h4>
                <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.email') }}</h4>
                <h4 style="font-size: 20px !important;" class="text-right">{{ config('app.location') }}</h4>
                <h3 class="text-right"><strong style="color: red !important;">JC - {{ str_pad($card->id, 5, '0', STR_PAD_LEFT) }}</strong></h3>
            </div>
        </div>


        <div class="row" style="border: 1px solid #e5e5e5;">
            <div class="col-xs-3"><h4 class="text-left"><strong>Date</strong></h4></div>
            <div class="col-xs-3"><h4 class="text-right">{{ Carbon\Carbon::parse($card->time_in)->format('dS F Y, h:i A') }}</h4></div>
            <div class="col-xs-3"><h4 class="text-left"><strong>Department</strong></h4></div>
            <div class="col-xs-3"><h4 class="text-right">{{ $card->vehicle_number }}</h4></div>
        </div>


        <div class="row" style="border: 1px solid #e5e5e5;">
            <div class="col-xs-3"><h4 class="text-left"><strong>KM</strong></h4></div>
            <div class="col-xs-3"><h4 class="text-right">{{ number_format($card->raw_data->current_km_reading, 0) }}</h4></div>
            <div class="col-xs-3"><h4 class="text-left"><strong>Cost Center</strong></h4></div>
            <div class="col-xs-3"><h4 class="text-right">&nbsp;</h4></div>
        </div>


        <div class="row" style="border: 1px solid #e5e5e5;">
            <div class="col-xs-3">
                <h4 class="text-left"><strong>Make</strong></h4>
            </div>
            <div class="col-xs-3">
                <h4 class="text-right">{{ $card->vehicle->make->name }}</h4>
            </div>

            <div class="col-xs-6">
                <h4 class="text-left"><strong>Machine Serial Number</strong></h4>
            </div>

            <div class="col-xs-3">
                <h4 class="text-left"><strong>Model</strong></h4>
            </div>
            <div class="col-xs-3">
                <h4 class="text-right">{{ $card->vehicle->model->name }}</h4>
            </div>

        </div>

        <div class="row" style="border: 1px solid #e5e5e5;">
            <div class="col-xs-6">
                <h4 class="text-left"><strong>Maintenance Section</strong></h4>
            </div>
            <div class="col-xs-6">
                <h4 class="text-left">{{ $card->jobType->name }}</h4>
            </div>
        </div>

        <div class="row">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th class="text-center" rowspan="2">Type of Maintenance</th>
                    <th class="text-center" rowspan="2">Description of Work Done</th>
                    <th class="text-center" rowspan="2">Done By</th>
                    <th class="text-center" colspan="2">Time Taken</th>
                </tr>
                <tr>
                    <th class="text-center">Start</th>
                    <th class="text-center">Finish</th>
                </tr>
                @foreach($card->raw_data->tasks as $task)
                    <?php
                    $employee = $employees->get($task->employee_id);
                    $employee = $employee ?: new stdClass();
                    ?>
                    <tr>
                        <td>{{ $task->task_name }}</td>
                        <td></td>
                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <h4 style="border-bottom: 1px solid #e5e5e5; text-align: left;">Remarks</h4>
            <h4 style="border-bottom: 1px solid #e5e5e5">&nbsp;</h4>
            <h4 style="border-bottom: 1px solid #e5e5e5">&nbsp;</h4>
            <h4 style="border-bottom: 1px solid #e5e5e5">&nbsp;</h4>
            <h4 style="border-bottom: 1px solid #e5e5e5">&nbsp;</h4>
            <h4>&nbsp;</h4>
        </div>

        <div class="row">
            <table class="table table-bordered">
                <tr>
                    <td>MECHANIC</td>
                    <td>SUPERVISOR</td>
                    <td>FOREMAN</td>
                    <td>MANAGER</td>
                </tr>
                <tr>
                    <td rowspan="3"></td>
                    <td></td>
                    <td></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        window.print();
        setTimeout(function () {
            window.close();
        }, 500);
    </script>
</body>
</html>
