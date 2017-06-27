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

        return Response::json([
            'months' => $months,
            'open_journeys' => $openJourneys
        ]);
    }

    /**
     * @return mixed
     */
    private function getOpenJourneys()
    {
        $openJourneys = Journey::open()
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
        $months = Journey::open()->get(['job_date'])
            ->map(function ($journey) {
                return Carbon::parse($journey->job_date)->format('m-Y');
            })
            ->unique();

        return $months;
    }
}
