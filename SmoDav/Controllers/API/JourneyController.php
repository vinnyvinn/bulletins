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
use Auth;

class JourneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {

        $journeys = Journey::with([
            'truck' => function ($builder) {
                return $builder->select(['id', 'plate_number', 'driver_id']);
            },
            'truck.driver',
            'contract.client',
        ])->get([
            'id',
            'is_contract_related',
            'truck_id',
            'contract_id',
            'journey_type',
            'job_date',
            'ref_no',
            'status',
        ]);

        return Response::json([
            'journeys' => $journeys,
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
            $contracts = Contract::open()
                ->whereRaw("(select count(*) from journeys where contracts.id = journeys.contract_id and status = 'Approved') < contracts.trucks_allocated")
                ->with('client')
                ->get(['id', 'raw', 'name', 'client_id']);


            return Response::json([
                'contracts' => $contracts,
            ]);
        }

        $journeys = Journey::where('status', '<>', 'Closed')
            ->get(['truck_id'])
            ->map(function ($journey) {
                return $journey->truck_id;
            })
            ->toArray();

        $trucks = Truck::with([
            'driver' => function ($builder) {
                return $builder->select(['id', 'first_name', 'last_name', 'mobile_phone']);
            },
        ])
            ->whereNotIn('id', $journeys)
            ->get(['driver_id', 'id', 'plate_number']);

        $last_journey_id = Journey::orderBy('created_at', 'desc')->first(['id']);

        return Response::json([
            'routes'                => Route::all(['id', 'source', 'destination', 'distance']),
            'clients'               => Client::all(['DCLink', 'Name', 'Account']),
            'cargo_classifications' => CargoClassification::all(['id', 'name']),
            'cargo_types'           => CargoType::all(['id', 'name', 'cargo_classification_id']),
            'carriage_points'       => CarriagePoint::all(['id', 'name']),
            'trucks'                => $trucks,
            'last_journey_id'       => $last_journey_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $total_trucks = Journey::where('contract_id', $data['contract_id'])->where('status','Approved')->count();
        $contract_allocated_trucks = Contract::findOrFail($data['contract_id'])->trucks_allocated;
        if ($total_trucks >= $contract_allocated_trucks) {
            return Response::json([
                'message' => 'Maximum trucks for this contract reached. This contract has been allocated ' . $contract_allocated_trucks . ' trucks',
            ]);
        }

        unset($data['_token'], $data['_method']);
        $data['raw'] = json_encode($data);
        $data['job_date'] = Carbon::parse(str_replace('/', '-', $data['job_date']))->format('Y-m-d');
        $data['user_id'] = Auth::id();


        foreach ($data as $key => $value) {
            if ($value == 'null') {
                unset($data[$key]);
            }
        }

        $journey = Journey::create($data);

        return Response::json([
            'message' => 'Successfully created new journey number JRNY-' . $journey->id,
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
        $journey = Journey::with(['contract', 'truck.driver', 'mileage', 'fuel'])->findOrFail($id);
        $journey->raw = json_decode($journey->raw);
        $contract = $journey->contract ? json_decode($journey->contract->raw) : new \stdClass();
        $contract->id = $journey->contract ? $journey->contract->id : '';
        unset($journey->contract);


        $journeys = Journey::where('status', '<>', 'Closed')
            ->where('id', '<>', $id)
            ->get(['truck_id'])
            ->map(function ($journey) {
                return $journey->truck_id;
            })
            ->toArray();

        $trucks = Truck::with([
            'driver' => function ($builder) {
                return $builder->select(['id', 'first_name', 'last_name', 'mobile_phone']);
            },
        ])
            ->whereNotIn('id', $journeys)
            ->get(['driver_id', 'id', 'plate_number']);

        return Response::json([
            'routes'                => Route::all(['id', 'source', 'destination', 'distance']),
            'clients'               => Client::all(['DCLink', 'Name', 'Account']),
            'cargo_classifications' => CargoClassification::all(['id', 'name']),
            'cargo_types'           => CargoType::all(['id', 'name', 'cargo_classification_id']),
            'carriage_points'       => CarriagePoint::all(['id', 'name']),
            'trucks'                => $trucks,
            'journey'               => $journey,
            'contract'              => $contract,
            'allocated' => Journey::open()->where('contract_id', $journey->contract_id)->count() - 1
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @param Journey $journey
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Journey $journey)
    {
        $data = $request->all();

        $total_trucks = Journey::where('contract_id', $data['contract_id'])->distinct()->count();
        $contract_allocated_trucks = Contract::findOrFail($data['contract_id'])->trucks_allocated;
        if ($total_trucks >= $contract_allocated_trucks) {
            return Response::json([
                'message' => 'Maximum trucks for this contract reached. This contract has been allocated ' . $contract_allocated_trucks . ' trucks',
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
            'message' => 'Successfully updated journey number JRNY-' . $journey->id,
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

        $journeys = Journey::with([
            'truck' => function ($builder) {
                return $builder->select(['id', 'plate_number']);
            },
        ])->get([
            'id',
            'is_contract_related',
            'journey_type',
            'job_date',
            'ref_no',
            'status',
        ]);

        return Response::json([
            'status'   => 'success',
            'message'  => 'Successfully deleted journey.',
            'journeys' => $journeys,
        ]);
    }

    public function approve($id)
    {
        Journey::where('id', $id)->update([
            'status' => Constants::STATUS_APPROVED,
        ]);

        return Response::json([
            'status'  => 'success',
            'message' => 'Successfully approved journey.',
        ]);
    }


    public function close(Request $request, Journey $journey, $id)
    {
        $journey = Journey::findOrFail($id);
        $data = $request->all();
        $journey->update([
            'mileage_balance' => $request['mileage_balance'],
            'status'          => Constants::STATUS_CLOSED,
            'closed_by'       => Auth::id(),
        ]);

        $truck = $journey->truck;
        $truck->current_km = $data['current_km'];
        $truck->current_fuel = $data['current_fuel'];
        $truck->update();

        return Response::json([
            'status'  => 'success',
            'message' => 'Successfully closed journey.',
        ]);
    }

    public function reopen($id)
    {
        Journey::where('id', $id)->update([
            'status' => Constants::STATUS_APPROVED,
        ]);

        return Response::json([
            'status'  => 'success',
            'message' => 'Successfully reopened journey.',
        ]);
    }

    public function trucks_already_allocated($contract_id)
    {
        return Response::json([
            'trucks_already_allocated' => Journey::open()->where('contract_id', $contract_id)->count(),
        ]);
    }
}
