<?php

namespace SmoDav\Controllers\API;

use App\Client;
use App\Contract;
use App\Driver;
use App\Http\Controllers\Controller;
use App\Route;
use App\Truck;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\CargoClassification;
use SmoDav\Models\CargoType;
use SmoDav\Models\CarriagePoint;
use SmoDav\Models\Journey;
use SmoDav\Models\Mileage;
use SmoDav\Models\Vehicle;
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
        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->with([
                'truck' => function ($builder) {
                    return $builder->select(['id', 'plate_number', 'driver_id']);
                },
                'driver',
                'contract.client',
            ])
            ->where('status', '<>', 'Closed')
            ->get([
                'id', 'driver_id', 'is_contract_related', 'truck_id', 'contract_id', 'journey_type', 'job_date',
                'ref_no', 'status',
            ]);

        return Response::json([
            'journeys' => $journeys,
            'drivers' => Driver::all()
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
                // ->whereRaw("(select count(*) from journeys where contracts.id = journeys.contract_id and status = 'Approved') < contracts.trucks_allocated")
                ->with('client')
                ->get(['id', 'raw', 'name', 'client_id', 'ignore_delivery_note', 'allow_route_change']);

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

        $ls = Vehicle::whereNotNull('contract_id')
            ->get(['id'])
            ->map(function ($item) {
                return $item->id;
            })
            ->toArray();

        $trucks = Vehicle::typeTruck()
            ->has('trailer')
            ->with([
                'driver' => function ($builder) {
                    return $builder->select(['id', 'first_name', 'last_name', 'mobile_phone']);
                },
                'trailer',
                'lsdelivery'
            ])
            ->whereNotIn('id', $journeys)
            ->whereNotIn('id', $ls)
            ->get(['driver_id', 'id', 'plate_number', 'trailer_id']);

        $last_journey_id = Journey::orderBy('created_at', 'desc')->first(['id']);
        $drivers = Driver::whereDoesntHave('journey', function ($builder) {
            return $builder->where('status', Constants::STATUS_APPROVED);
        })->get(['id', 'first_name', 'last_name', 'mobile_phone']);

        return Response::json([
            'routes' => Route::all(['id', 'source', 'destination', 'distance']),
            'clients' => Client::all(['DCLink', 'Name', 'Account']),
            'cargo_classifications' => CargoClassification::all(['id', 'name']),
            'cargo_types' => CargoType::all(['id', 'name', 'cargo_classification_id']),
            'carriage_points' => CarriagePoint::all(['id', 'name']),
            'trucks' => $trucks,
            'last_journey_id' => $last_journey_id,
            'drivers' => $drivers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $total_trucks = Journey::where('contract_id', $data['contract_id'])->where('status', 'Approved')->count();
        $contract_allocated_trucks = Contract::findOrFail($data['contract_id'])->trucks_allocated;
        if ($total_trucks >= $contract_allocated_trucks) {
            return Response::json([
                'message' => 'Maximum trucks for this contract reached. This contract has been allocated ' . $contract_allocated_trucks . ' trucks',
            ]);
        }

        unset($data['_token'], $data['_method']);
        $data['raw'] = \json_encode($data);
        $data['job_date'] = Carbon::parse(\str_replace('/', '-', $data['job_date']))->format('Y-m-d');
        $data['user_id'] = Auth::id();
        $data['subcontracted'] = 0;

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
        $journey = Journey::with([
            'contract', 'route', 'driver', 'truck.trailer', 'mileage', 'fuel','delivery',
            'closer' => function ($builder) {
                return $builder->select('id', 'first_name', 'last_name');
            },
            'opener' => function ($builder) {
                return $builder->select('id', 'first_name', 'last_name');
            }
        ])->findOrFail($id);

        $journey->raw = \json_decode($journey->raw);
        $contract = $journey->contract ? \json_decode($journey->contract->raw) : new \stdClass();
        $contract->id = $journey->contract ? $journey->contract->id : '';
        unset($journey->contract);

        $journeys = Journey::where('status', '<>', 'Closed')
            ->where('id', '<>', $id)
            ->get(['truck_id'])
            ->map(function ($journey) {
                return $journey->truck_id;
            })
            ->toArray();

        $trucks = Vehicle::typeTruck()
            ->has('trailer')
            ->with([
                'driver' => function ($builder) {
                    return $builder->select(['id', 'first_name', 'last_name', 'mobile_phone']);
                },
            ])
            ->whereNotIn('id', $journeys)
            ->get(['driver_id', 'id', 'plate_number']);

        $drivers = Driver::whereDoesntHave('journey', function ($builder) {
            return $builder->where('status', Constants::STATUS_APPROVED);
        })
            ->orWhere('id', $journey->driver_id)
            ->get(['id', 'first_name', 'last_name', 'mobile_phone']);

        $contracts = Contract::open()
            ->orWhere('id', $journey->contract_id)
            ->whereRaw("(select count(*) from journeys where contracts.id = journeys.contract_id and status = 'Approved') < contracts.trucks_allocated")
            ->with('client')
            ->get(['id', 'raw', 'name', 'client_id', 'ignore_delivery_note']);

        return Response::json([
            'routes' => Route::all(['id', 'source', 'destination', 'distance']),
            'clients' => Client::all(['DCLink', 'Name', 'Account']),
            'cargo_classifications' => CargoClassification::all(['id', 'name']),
            'cargo_types' => CargoType::all(['id', 'name', 'cargo_classification_id']),
            'carriage_points' => CarriagePoint::all(['id', 'name']),
            'trucks' => $trucks,
            'journey' => $journey,
            'contract' => $contract,
            'drivers' => $drivers,
            'contracts' => $contracts,
            'allocated' => Journey::open()->where('contract_id', $journey->contract_id)->count() - 1,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Journey                  $journey
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
        $data['raw'] = \json_encode($data);
        $data['job_date'] = Carbon::parse(\str_replace('/', '-', $data['job_date']))->format('Y-m-d');

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
     */
    public function destroy(Journey $journey)
    {
        $journey->delete();

        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->with([
                'truck' => function ($builder) {
                    return $builder->select(['id', 'plate_number']);
                },
            ])
            ->get([
                'id', 'is_contract_related', 'journey_type', 'job_date', 'ref_no', 'status',
            ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted journey.',
            'journeys' => $journeys,
        ]);
    }

    public function approve($id)
    {
        Journey::where('id', $id)->update([
            'status' => Constants::STATUS_APPROVED,
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully approved journey.',
        ]);
    }

    public function close(Request $request, Journey $journey, $id)
    {
        $returnMileage = $request->get('returnMileage');
        $returnMileage['journey_id'] = $id;
        $returnMileage['balance_amount'] = 0;
        $returnMileage['top_up'] = 0;
        $returnMileage['top_up_amount'] = 0;
        $returnMileage['top_up_reason'] = '';
        $returnMileage['user_id'] = Auth::id();
        $returnMileage['raw'] = \json_encode($returnMileage);
        $returnMileage['status'] = 'Approved';

        $mileage = \DB::transaction(function () use ($id, $request, $returnMileage) {
            $journey = Journey::findOrFail($id);
            $data = $request->all();
            $journey->update([
                'mileage_balance' => $request['mileage_balance'],
                'status' => Constants::STATUS_CLOSED,
                'closed_by' => Auth::id(),
            ]);

            $mileage = Mileage::create($returnMileage);

            $truck = $journey->truck;
            $truck->current_km = $data['current_km'];
            $truck->current_fuel = $data['current_fuel'];
            $truck->update();

            return $mileage;
        });

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully closed journey.',
            'config' => [
                'name' => config('app.name'),
                'telephone' => config('app.telephone'),
                'email' => config('app.email'),
                'location' => config('app.location'),
            ],
            'mileage' => Mileage::with(['user', 'journey.truck'])->find($mileage->id)
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully closed journey.',
        ]);
    }

    public function reopen($id)
    {
        Journey::where('id', $id)->update([
            'status' => Constants::STATUS_APPROVED,
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully reopened journey.',
        ]);
    }

    public function trucksAlreadyAllocated($contract_id)
    {
        return Response::json([
            'trucks_already_allocated' => Journey::open()->where('contract_id', $contract_id)->count(),
        ]);
    }
}
