<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SmoDav\Models\Delivery;
use SmoDav\Models\Mileage;
use SmoDav\Models\Station;
use App\Contract;
use App\Fuel;
use Response;

class ReportController extends Controller
{
    public function init()
    {
        return Response::json([
            'contracts' => Contract::all(['id', 'name']),
            'stations' => Station::all(['id', 'name']),
        ]);
    }

    public function loading(Request $request)
    {
        $start = Carbon::parse($request->get('start_date'))->startOfDay();
        $end = Carbon::parse($request->get('end_date'))->endOfDay();

        $columns = [
            'contracts.name', 'loading_gross_weight', 'loading_tare_weight', 'loading_net_weight',
            'loading_weighbridge_number', 'loading_time', 'plate_number'
        ];

        if ($request->get('summary') == 1) {
            unset(
                $columns['loading_weighbridge_number'],
                $columns['loading_time']
            );
        }

        $deliveries = Delivery::where('loading_time', '>=', $start)
            ->where('loading_time', '<=', $end)
            ->join('journeys', 'journeys.id', '=', 'deliveries.journey_id')
            ->join('contracts', 'journeys.contract_id', '=', 'contracts.id')
            ->join('vehicles', 'journeys.truck_id', '=', 'vehicles.id')
            ->when($request->get('contract_id'), function ($builder) use ($request) {
                return $builder->where('contracts.id', $request->get('contract_id'));
            })
            ->when($request->get('station_id'), function ($builder) use ($request) {
                return $builder->where('deliveries.station_id', $request->get('station_id'));
            })
            ->when($request->get('summary') == 1, function ($builder) use ($columns) {
                return $builder
                    ->select(\DB::raw(
                        '
                        contracts.name,
                        count(deliveries.id) as total,
                        sum(loading_gross_weight) as loading_gross_weight,
                        sum(loading_tare_weight) as loading_tare_weight,
                        sum(loading_net_weight) as loading_net_weight
                        '
                    ))
                    ->groupBy('contracts.name');
            })
            ->get($columns);

        if ($request->get('group_contract') == 1) {
            $deliveries = $deliveries->groupBy('name');
        }

        return \Response::json([
            'status' => 'success',
            'deliveries' => $deliveries
        ]);
    }

    public function offloading(Request $request)
    {
        $start = Carbon::parse($request->get('start_date'))->startOfDay();
        $end = Carbon::parse($request->get('end_date'))->endOfDay();

        $columns = [
            'contracts.name', 'offloading_gross_weight', 'offloading_tare_weight', 'offloading_net_weight',
            'offloading_weighbridge_number', 'offloading_time', 'plate_number'
        ];

        if ($request->get('summary') == 1) {
            unset(
                $columns['offloading_weighbridge_number'],
                $columns['offloading_time']
            );
        }

        $deliveries = Delivery::where('offloading_time', '>=', $start)
            ->where('offloading_time', '<=', $end)
            ->join('journeys', 'journeys.id', '=', 'deliveries.journey_id')
            ->join('contracts', 'journeys.contract_id', '=', 'contracts.id')
            ->join('vehicles', 'journeys.truck_id', '=', 'vehicles.id')
            ->when($request->get('contract_id'), function ($builder) use ($request) {
                return $builder->where('contracts.id', $request->get('contract_id'));
            })
            ->when($request->get('station_id'), function ($builder) use ($request) {
                return $builder->where('deliveries.station_id', $request->get('station_id'));
            })
            ->when($request->get('summary') == 1, function ($builder) use ($columns) {
                return $builder
                    ->select(\DB::raw(
                        '
                        contracts.name,
                        count(deliveries.id) as total,
                        sum(offloading_gross_weight) as offloading_gross_weight,
                        sum(offloading_tare_weight) as offloading_tare_weight,
                        sum(offloading_net_weight) as offloading_net_weight
                        '
                    ))
                    ->groupBy('contracts.name');
            })
            ->get($columns);

        if ($request->get('group_contract') == 1) {
            $deliveries = $deliveries->groupBy('name');
        }

        return \Response::json([
            'status' => 'success',
            'deliveries' => $deliveries
        ]);
    }

    public function fuel(Request $request)
    {
        $start = Carbon::parse($request->get('start_date'))->startOfDay();
        $end = Carbon::parse($request->get('end_date'))->endOfDay();

        $columns = [
            'date', 'fuels.current_fuel', 'fuel_requested', 'fuel_issued', 'fuel_total', 'tank',
            'pump', 'top_up', 'top_up_reason', 'top_up_quantity', 'stations.name as station_name',
            'contracts.name', 'vehicles.plate_number'
        ];

        if ($request->get('summary') == 1) {
            unset(
                $columns['offloading_weighbridge_number'],
                $columns['offloading_time']
            );
        }

        $deliveries = Fuel::where('date', '>=', $start)
            ->where('date', '<=', $end)
            ->join('journeys', 'journeys.id', '=', 'fuels.journey_id')
            ->join('contracts', 'journeys.contract_id', '=', 'contracts.id')
            ->join('vehicles', 'journeys.truck_id', '=', 'vehicles.id')
            ->join('stations', 'fuels.station_id', '=', 'stations.id')
            ->when($request->get('contract_id'), function ($builder) use ($request) {
                return $builder->where('contracts.id', $request->get('contract_id'));
            })
            ->when($request->get('station_id'), function ($builder) use ($request) {
                return $builder->where('fuels.station_id', $request->get('station_id'));
            })
            ->when($request->get('summary') == 1, function ($builder) use ($columns) {
                return $builder
                    ->select(\DB::raw(
                        '
                        stations.name as station_name,
                        contracts.name,
                        count(fuels.id) as total,
                        sum(fuels.current_fuel) as current_fuel,
                        sum(fuel_requested) as fuel_requested,
                        sum(fuel_issued) as fuel_issued,
                        sum(fuel_total) as fuel_total
                        '
                    ))
                    ->groupBy('contracts.name')
                    ->groupBy('stations.name');
            })
            ->where('fuels.status', 'Approved')
            ->get($columns);

        if ($request->get('group_contract') == 1) {
            $deliveries = $deliveries->groupBy('name');
        }

        return \Response::json([
            'status' => 'success',
            'deliveries' => $deliveries
        ]);
    }

    public function mileage(Request $request)
    {
        $start = Carbon::parse($request->get('start_date'))->startOfDay();
        $end = Carbon::parse($request->get('end_date'))->endOfDay();

        $columns = [
            'mileage_type', 'requested_amount', 'approved_amount', 'balance_amount',
            'standard_amount', 'top_up_amount', 'contracts.name', 'vehicles.plate_number',
            'journeys.job_date as date', 'stations.name as station_name'
        ];

        $deliveries = Mileage::join('journeys', 'journeys.id', '=', 'mileages.journey_id')
            ->join('contracts', 'journeys.contract_id', '=', 'contracts.id')
            ->join('vehicles', 'journeys.truck_id', '=', 'vehicles.id')
            ->join('stations', 'mileages.station_id', '=', 'stations.id')
            ->where('journeys.job_date', '>=', $start)
            ->where('journeys.job_date', '<=', $end)
            ->when($request->get('contract_id'), function ($builder) use ($request) {
                return $builder->where('contracts.id', $request->get('contract_id'));
            })
            ->when($request->get('station_id'), function ($builder) use ($request) {
                return $builder->where('mileages.station_id', $request->get('station_id'));
            })
            ->when($request->get('summary') == 1, function ($builder) use ($columns) {
                return $builder
                    ->select(\DB::raw(
                        '
                        stations.name as station_name,
                        contracts.name,
                        mileage_type,
                        count(mileages.id) as total,
                        sum(requested_amount) as requested_amount,
                        sum(approved_amount) as approved_amount,
                        sum(balance_amount) as balance_amount,
                        sum(standard_amount) as standard_amount,
                        sum(top_up_amount) as top_up_amount
                        '
                    ))
                    ->groupBy('contracts.name')
                    ->groupBy('stations.name')
                    ->groupBy('mileage_type');
            })
            ->where('mileages.status', 'Approved')
            ->get($columns);

        if ($request->get('group_contract') == 1) {
            $deliveries = $deliveries->groupBy('name');
        }

        return \Response::json([
            'status' => 'success',
            'deliveries' => $deliveries
        ]);
    }
}