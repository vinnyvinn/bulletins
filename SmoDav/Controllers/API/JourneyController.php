<?php

namespace SmoDav\Controllers\API;

use App\Client;
use App\Contract;
use App\Driver;
use App\Http\Controllers\Controller;
use App\Route;
use App\Truck;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\CargoClassification;
use SmoDav\Models\CargoType;
use SmoDav\Models\CarriagePoint;
use SmoDav\Models\Journey;
use SmoDav\Support\Constants;

class JourneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        $journeys = Journey::with(['truck' => function ($builder) {
            return $builder->select(['id', 'plate_number']);
        }])->get([
            'id', 'is_contract_related', 'journey_type', 'job_date', 'ref_no', 'status'
        ]);

        return Response::json([
            'journeys' => $journeys
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function create()
    {
        if (request('contracts')) {
            return Response::json([
                'contracts' => Contract::open()->get([
                    'id', 'raw'
                ])
            ]);
        }

        $journeys = Journey::where('status','<>','Closed')
            ->get(['truck_id'])
            ->map(function ($journey) {
                return $journey->truck_id;
            })
            ->toArray();

        $trucks = Truck::with(['driver' => function ($builder) {
            return $builder->select(['id', 'first_name', 'last_name', 'mobile_phone']);
        }])
            ->whereNotIn('id', $journeys)
            ->get(['driver_id', 'id', 'plate_number']);

        $last_journey_id = Journey::orderBy('created_at','desc')->first(['id']);

        return Response::json([
            'routes' => Route::all(['id', 'source', 'destination', 'distance']),
            'clients' => Client::all(['DCLink', 'Name', 'Account']),
            'cargo_classifications' => CargoClassification::all(['id', 'name']),
            'cargo_types' => CargoType::all(['id', 'name', 'cargo_classification_id']),
            'carriage_points' => CarriagePoint::all(['id', 'name']),
            // 'drivers' => Driver::all(['id', 'first_name', 'last_name', 'mobile_phone']),
            //  'trucks' => Truck::,
            'trucks' => $trucks,
            'last_journey_id' => $last_journey_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $total_trucks = Journey::where('contract_id',$data['contract_id'])->distinct()->count();
        $contract_allocated_trucks = Contract::findOrFail($data['contract_id'])->trucks_allocated;
        if($total_trucks >= $contract_allocated_trucks)
        {
          return Response::json([
            'message' => 'Maximum trucks for this contract reached. This contract has been allocated '.$contract_allocated_trucks.' trucks'
          ]);
        }

        unset($data['_token'], $data['_method']);
        $data['raw'] = json_encode($data);
        $data['job_date'] = Carbon::parse(str_replace('/', '-', $data['job_date']))->format('Y-m-d');

        foreach ($data as $key => $value) {
            if ($value == 'null') {
                unset($data[$key]);
            }
        }

        $journey = Journey::create($data);

        return Response::json([
            'message' => 'Successfully created new journey number JRNY-' . $journey->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $journey = Journey::with(['contract'])->findOrFail($id);
        $journey->raw = json_decode($journey->raw);
        $contract = $journey->contract ? json_decode($journey->contract->raw): new \stdClass();
        $contract->id = $journey->contract ? $journey->contract->id : '';
        unset($journey->contract);

        return Response::json([
            'routes' => Route::all(['id', 'source', 'destination', 'distance']),
            'clients' => Client::all(['DCLink', 'Name', 'Account']),
            'cargo_classifications' => CargoClassification::all(['id', 'name']),
            'cargo_types' => CargoType::all(['id', 'name', 'cargo_classification_id']),
            'carriage_points' => CarriagePoint::all(['id', 'name']),
            'drivers' => Driver::all(['id', 'first_name', 'last_name', 'mobile_phone']),
            'trucks' => Truck::all(['id', 'plate_number']),
            'journey' => $journey,
            'contract' => $contract
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @param Journey                   $journey
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Journey $journey)
    {
        $data = $request->all();

        $total_trucks = Journey::where('contract_id',$data['contract_id'])->distinct()->count();
        $contract_allocated_trucks = Contract::findOrFail($data['contract_id'])->trucks_allocated;
        if($total_trucks >= $contract_allocated_trucks)
        {
          return Response::json([
            'message' => 'Maximum trucks for this contract reached. This contract has been allocated '.$contract_allocated_trucks.' trucks'
          ]);
        }

        unset($data['_token'], $data['_method']);
        $data['raw'] = json_encode($data);
        $data['job_date'] = Carbon::parse(str_replace('/', '-', $data['job_date']))->format('Y-m-d');


        foreach ($data as $key => $value) {
            if ($value == 'null') {
                unset($data[$key]);
            }
        }

        $journey->update($data);

        return Response::json([
            'message' => 'Successfully updated journey number JRNY-' . $journey->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Journey $journey
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function destroy(Journey $journey)
    {
        $journey->delete();

        $journeys = Journey::with(['truck' => function ($builder) {
            return $builder->select(['id', 'plate_number']);
        }])->get([
            'id', 'is_contract_related', 'journey_type', 'job_date', 'ref_no', 'status'
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted journey.',
            'journeys' => $journeys
        ]);
    }

    public function approve($id)
    {
        Journey::where('id', $id)->update([
            'status' => Constants::STATUS_APPROVED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully approved journey.',
        ]);
    }

    public function close($id)
    {
        Journey::where('id', $id)->update([
            'status' => Constants::STATUS_CLOSED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully closed journey.',
        ]);
    }

    public function reopen($id)
    {
        Journey::where('id', $id)->update([
            'status' => Constants::STATUS_APPROVED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully reopened journey.',
        ]);
    }
}
