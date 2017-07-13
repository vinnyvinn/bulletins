<?php

namespace SmoDav\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Response;
use SmoDav\Models\Journey;

class DashboardController extends Controller
{
    public function index()
    {
        $openJourneys = $this->getOpenJourneys();
        $months = $this->getJourneyMonths();
        $unloadedJourneys = $this->getUnloadedJourneys();
        $notFueledJourneys = $this->getUnfueledJourneys();
        $notPaidJourneys = $this->getUnpaidJourneys();

        return Response::json([
            'months' => $months,
            'open_journeys' => $openJourneys,
            'not_loaded' => $unloadedJourneys,
            'not_fueled' => $notFueledJourneys,
            'not_paid' => $notPaidJourneys
        ]);
    }

    /**
     * @return mixed
     */
    private function getOpenJourneys()
    {
        $openJourneys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->open()
            ->when(! request('month'), function ($builder) {
                $today = Carbon::now();

                return $builder->whereMonth('job_date', $today->month)->whereYear('job_date', $today->year);
            })
            ->when(request('month'), function ($builder) {
                $dates = explode('-', request('month'));
                if (count($dates) < 2) {
                    return $builder;
                }

                return $builder->whereMonth('job_date', $dates[0])->whereYear('job_date', $dates[1]);
            })
            ->get([
                'id', 'is_contract_related', 'journey_type', 'job_date', 'ref_no', 'status', 'created_at'
            ]);

        return $openJourneys;
    }

    /**
     * @return mixed
     */
    private function getJourneyMonths()
    {
        $months = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->open()->get(['job_date'])
            ->map(function ($journey) {
                return Carbon::parse($journey->job_date)->format('m-Y');
            })
            ->unique();

        return $months;
    }

    private function getUnloadedJourneys()
    {
        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->doesntHave('delivery')
            ->open()
            ->when(! request('month'), function ($builder) {
                $today = Carbon::now();

                return $builder->whereMonth('job_date', $today->month)->whereYear('job_date', $today->year);
            })
            ->when(request('month'), function ($builder) {
                $dates = explode('-', request('month'));
                if (count($dates) < 2) {
                    return $builder;
                }

                return $builder->whereMonth('job_date', $dates[0])->whereYear('job_date', $dates[1]);
            })
            ->get([
                'id', 'is_contract_related', 'journey_type', 'job_date', 'ref_no', 'status', 'created_at'
            ]);

        return $journeys;
    }

    private function getUnfueledJourneys()
    {
        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->doesntHave('fuel')
            ->open()
            ->when(! request('month'), function ($builder) {
                $today = Carbon::now();

                return $builder->whereMonth('job_date', $today->month)->whereYear('job_date', $today->year);
            })
            ->when(request('month'), function ($builder) {
                $dates = explode('-', request('month'));
                if (count($dates) < 2) {
                    return $builder;
                }

                return $builder->whereMonth('job_date', $dates[0])->whereYear('job_date', $dates[1]);
            })
            ->get([
                'id', 'is_contract_related', 'journey_type', 'job_date', 'ref_no', 'status', 'created_at'
            ]);

        return $journeys;
    }

    private function getUnpaidJourneys()
    {
        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->doesntHave('mileage')
            ->open()
            ->when(! request('month'), function ($builder) {
                $today = Carbon::now();

                return $builder->whereMonth('job_date', $today->month)->whereYear('job_date', $today->year);
            })
            ->when(request('month'), function ($builder) {
                $dates = explode('-', request('month'));
                if (count($dates) < 2) {
                    return $builder;
                }

                return $builder->whereMonth('job_date', $dates[0])->whereYear('job_date', $dates[1]);
            })
            ->get([
                'id', 'is_contract_related', 'journey_type', 'job_date', 'ref_no', 'status', 'created_at'
            ]);

        return $journeys;
    }
}
