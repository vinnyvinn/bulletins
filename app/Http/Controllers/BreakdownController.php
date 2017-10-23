<?php

namespace App\Http\Controllers;

use App\Breakdown;
use App\BreakdownArea;
use App\Driver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SmoDav\Models\JobCard;
use SmoDav\Models\Vehicle;
use SmoDav\Models\WorkshopInspectionCheckList;
use SmoDav\Models\WorkshopJobType;
use SmoDav\Support\Constants;

class BreakdownController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $breakdowns = Breakdown::join('vehicles', 'vehicles.id', '=', 'breakdowns.vehicle_id')
            ->join('breakdown_areas', 'breakdown_areas.id', '=', 'breakdowns.breakdown_area_id')
            ->join('drivers', 'drivers.id', '=', 'breakdowns.driver_id')
            ->select([
                'vehicles.plate_number', 'drivers.first_name', 'drivers.last_name', 'breakdowns.created_at',
                'breakdown_areas.name', 'breakdowns.id', 'breakdowns.location'
            ])
            ->when(! request('status'), function ($builder) {
                return $builder->where('breakdowns.status', Breakdown::PENDING);
            })
            ->when(\request('status'), function ($builder) {
                return $builder->where('breakdowns.status', \request('status'));
            })
            ->get();

        return \Response::json([
            'breakdowns' => $breakdowns
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return \Response::json([
            'vehicles' => Vehicle::all(['id', 'plate_number', 'driver_id']),
            'areas' => BreakdownArea::all(['id', 'name']),
            'drivers' => Driver::all(['id', 'first_name', 'last_name'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['status'] = Breakdown::PENDING;
        $data['raw'] = json_encode($data);
        $data['incident_details'] = json_encode($data['incident_details']);
        $data['break_down_recovered'] = json_encode([]);
        $data['breakdown_approved'] = false;

        Breakdown::create($data);

        return \Response::json([
          'status' => 'success',
          'message' => 'Successfully logged new breakdown.'
        ]);
    }

    public function show($breakdownId)
    {
        $breakdown = Breakdown::where('id', $breakdownId)
            ->when(\request('full'), function ($builder) {
                return $builder->with([
                    'jobCards.vehicle',
                    'jobCards.type'
                ]);
            })
            ->first();

        $breakdown->incident_details = json_decode($breakdown->incident_details);
        $breakdown->break_down_recovered = json_decode($breakdown->break_down_recovered);

        return \Response::json([
            'vehicles' => Vehicle::all(['id', 'plate_number', 'driver_id']),
            'areas' => BreakdownArea::all(['id', 'name']),
            'drivers' => Driver::all(['id', 'first_name', 'last_name']),
            'breakdown' => $breakdown
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Breakdown  $breakdown
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Breakdown $breakdown)
    {
        $data = $request->all();
        unset($data['status']);
        $data['raw'] = json_encode($data);
        $data['incident_details'] = json_encode($data['incident_details']);
        $data['break_down_recovered'] = json_encode($data['break_down_recovered']);
        $data['breakdown_approved'] = false;

        $breakdown->update($data);

        return \Response::json([
            'status' => 'success',
            'message' => 'Successfully updated breakdown.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Breakdown  $breakdown
     * @return \Illuminate\Http\Response
     */
    public function destroy(Breakdown $breakdown)
    {
        //
    }

    public function jobCard($breakdownId)
    {
        $breakdown = Breakdown::with('vehicle')->find($breakdownId);

        $data = $this->prepareJobCardData($breakdown);

        $card = JobCard::create($data);

        return \Response::json([
            'status' => 'success',
            'jobCard' => $card->id,
            'message' => 'Successfully created new Job Card # ' . $card->id
        ]);
    }

    public function approve($breakdownId)
    {
        $breakdown = Breakdown::with('vehicle')->find($breakdownId);
        $breakdown->update(['status' => Breakdown::APPROVED]);

        $data = $this->prepareJobCardData($breakdown);

        $card = JobCard::create($data);

        return \Response::json([
            'status' => 'success',
            'message' => 'Successfully approved breakdown. New Job Card # ' . $card->id . ' created.'
        ]);
    }

    public function disapprove($breakdownId)
    {
        Breakdown::where('id', $breakdownId)->update(['status' => Breakdown::DECLINED]);

        return \Response::json([
            'status' => 'success',
            'message' => 'Successfully disapproved breakdown'
        ]);
    }

    public function close($breakdownId)
    {
        Breakdown::where('id', $breakdownId)->update(['status' => Breakdown::CLOSED]);

        return \Response::json([
            'status' => 'success',
            'message' => 'Successfully closed breakdown'
        ]);
    }

    /**
     * @param $breakdown
     * @return array
     */
    private function prepareJobCardData($breakdown)
    {
        $jobType = WorkshopJobType::where('name', 'Break Down')->first();
        $jobType = $jobType ?: new WorkshopJobType;
        $checklists = WorkshopInspectionCheckList::all(['name', 'id']);

        $data = [
            'service_type'         => 'Normal Job',
            'vehicle_id'           => $breakdown->vehicle_id,
            'vehicle_type'         => $breakdown->vehicle->type,
            'vehicle_number'       => $breakdown->vehicle->plate_number,
            'workshop_job_type_id' => $jobType->id,
            'expected_completion'  => Carbon::now()->addDay(7)->toDateString(),
            'time_in'              => Carbon::now()->toTimeString(),
            'job_description'      => $breakdown->description,
            'current_km_reading'   => 0,
            'fuel_balance'         => 0,
            'has_trailer'          => false,
            'status'               => Constants::STATUS_PENDING,
            'mechanic_findings',
            'user_id'              => \Auth::id(),
            'breakdown_id'         => $breakdown->id,
            'tasks'                => [],
            'inspections'          => [],
        ];

        foreach ($checklists as $checklist) {
            $data['inspections'][] = [
                'employee_id'                       => null,
                'inspection_name'                   => $checklist->name,
                'status'                            => 'Not Started',
                'workshop_inspection_check_list_id' => $checklist->id,
            ];
        }

        $data['raw_data'] = json_encode($data);

        return $data;
    }
}
