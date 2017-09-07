<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SmoDav\Models\Delivery;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loading(Request $request)
    {
        $start = Carbon::parse($request->get('start_date'))->startOfDay();
        $end = Carbon::parse($request->get('end_date'))->endOfDay();

        $columns = [
            'contracts.name', 'loading_gross_weight', 'loading_tare_weight', 'loading_net_weight',
            'loading_weighbridge_number', 'loading_time', 'bags_loaded'
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
            ->when($request->get('summary') == 1, function ($builder) use ($columns) {
                return $builder
                    ->select(\DB::raw(
                        '
                        contracts.name,
                        count(deliveries.id) as total,
                        sum(bags_loaded) as bags_loaded,
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
}
