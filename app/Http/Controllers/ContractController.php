<?php

namespace App\Http\Controllers;

use App\Client;
use App\Contract;
use App\Http\Helpers\Helpers;
use App\Option;
use App\Route;
use App\StockItem;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\CargoClassification;
use SmoDav\Models\CargoType;
use SmoDav\Models\CarriagePoint;
use SmoDav\Support\Constants;
use SmoDav\Models\Vehicle;
use function str_replace;
use Auth;
use SmoDav\Models\Journey;
use App\Employee;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        $contracts = Contract::withCount('journeys')
            ->with([
                'client' => function ($builder) {
                    return $builder->select(['DCLink', 'Name']);
                },
                'deliveries' => function ($builder) {
                    return $builder->select('journey_id', 'offloading_net_weight');
                }
            ])
            ->when(request()->has('duration') && request()->has('date'), function ($builder) {
                $endDate = Carbon::parse(request('date'));
                $startDate = Carbon::parse(request('date'))->subMonths(\request('duration'));

                return $builder->where('start_date', '>=', $startDate)->where('start_date', '<=', $endDate);
            })
            ->get([
                'id', 'client_id', 'created_at', 'start_date', 'end_date', 'quantity', 'amount', 'rate', 'status'
            ])
            ->map(function ($contract) {
                $contract->totalDeliveries = $contract->deliveries->sum('offloading_net_weight');
                unset($contract->deliveries);

                return $contract;
            });

        return Response::json([
            'contracts' => $contracts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function create()
    {
        $items = StockItem::where('ItemGroup', Helpers::get_option(Option::BILLABLE_GROUP))
            ->select(['StockLink', 'Description_1'])
            ->get();
        $last_contract_id = Contract::orderBy('created_at','desc')->first(['id']);


        return Response::json([
            'routes' => Route::all(['id', 'source', 'destination', 'distance']),
            'clients' => Client::all(['DCLink', 'Name', 'Account']),
            'cargo_classifications' => CargoClassification::all(['id', 'name']),
            'cargo_types' => CargoType::all(['id', 'name', 'cargo_classification_id']),
            'carriage_points' => CarriagePoint::all(['id', 'name']),
            'stockItems' => $items,
            'last_contract_id' => $last_contract_id
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
        foreach ($data as $key => $value) {
            if ($value == 'null') {
                unset($data[$key]);
            }
        }

        $data['start_date'] = Carbon::parse(str_replace('/', '-', $data['start_date']))->format('Y-m-d');
        $data['end_date'] = Carbon::parse(str_replace('/', '-', $data['start_date']))
            ->addDays($data['estimated_days'])
            ->format('Y-m-d');

        $data['berthing_date'] = isset($data['berthing_date']) ?
            Carbon::parse(str_replace('/', '-', $data['berthing_date']))->format('Y-m-d') :
            null;
        $data['vessel_arrival_date'] = isset($data['vessel_arrival_date']) ?
            Carbon::parse(str_replace('/', '-', $data['vessel_arrival_date']))->format('Y-m-d') :
            null;

        $data['unloading_points'] = json_decode($data['unloading_points']);
        $data['shifts'] = json_decode($data['shifts']);

        $data['raw'] = json_encode($data);
        $data['shifts'] = json_encode($data['shifts']);
        $data['unloading_points'] = json_encode($data['unloading_points']);
        $data['user_id'] = Auth::id();


        unset($data['_token'], $data['_method']);

        $contract = Contract::create($data);
        $ignore = [
            'start_date', 'end_date', '_token', '_method', 'updated_at', 'deleted_at', 'berthing_date',
            'vessel_arrival_date'
        ];

        foreach ($data as $key => $item) {
            if (in_array($key, $ignore)) {
                continue;
            }

            $contract->{$key} = $item;
            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $contract->{$key} = $filename;
            }
        }

        $contract->save();

        return Response::json([
            'message' => 'Successfully created new contract ending on ' .
                Carbon::parse(str_replace('/', '-', $data['end_date']))->format('l dS F Y')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $items = StockItem::where('ItemGroup', Helpers::get_option(Option::BILLABLE_GROUP))
            ->select(['StockLink', 'Description_1'])
            ->get();

        $contract = Contract::with([
            'journeys' => function ($builder) {
                return $builder->select([
                    'journeys.id', 'contract_id', 'journey_type', 'job_date', 'status', 'truck_id'
                ]);
            },
            'journeys.truck' => function ($builder) {
                return $builder->select([
                    'vehicles.id', 'plate_number'
                ]);
            },
            'deliveries' => function ($builder) {
                return $builder->select([
                    'deliveries.id', 'journey_id', 'loading_net_weight', 'offloading_net_weight',
                    'loading_time', 'offloading_time'
                ]);
            }
        ])->findOrFail($id);

        $contract->raw = json_decode($contract->raw);

        return Response::json([
            'routes' => Route::all(['id', 'source', 'destination', 'distance']),
            'clients' => Client::all(['DCLink', 'Name', 'Account']),
            'cargo_classifications' => CargoClassification::all(['id', 'name']),
            'cargo_types' => CargoType::all(['id', 'name', 'cargo_classification_id']),
            'carriage_points' => CarriagePoint::all(['id', 'name']),
            'stockItems' => $items,
            'contract' => $contract
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contract  $contract
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        $data = $request->all();
        foreach ($data as $key => $value) {
            if ($value == 'null') {
                unset($data[$key]);
            }
        }
        $data['start_date'] = Carbon::parse(str_replace('/', '-', $data['start_date']))->format('Y-m-d');
        $data['end_date'] = Carbon::parse(str_replace('/', '-', $data['start_date']))
            ->addDays($data['estimated_days'])
            ->format('Y-m-d');

        $data['berthing_date'] = isset($data['berthing_date']) ?
            Carbon::parse(str_replace('/', '-', $data['berthing_date']))->format('Y-m-d') :
            null;
        $data['vessel_arrival_date'] = isset($data['vessel_arrival_date']) ?
            Carbon::parse(str_replace('/', '-', $data['vessel_arrival_date']))->format('Y-m-d') :
            null;


        $data['unloading_points'] = json_decode($data['unloading_points']);
        $data['shifts'] = json_decode($data['shifts']);

        $data['raw'] = json_encode($data);
        $data['shifts'] = json_encode($data['shifts']);
        $data['unloading_points'] = json_encode($data['unloading_points']);

        unset($data['_token'], $data['_method']);
        $ignore = [
            'start_date', 'end_date', '_token', '_method', 'updated_at', 'deleted_at', 'berthing_date',
            'vessel_arrival_date'
        ];

        foreach ($data as $key => $item) {
            if (in_array($key, $ignore)) {
                continue;
            }

            if ($item == 'null') {
                unset($data[$key]);
                continue;
            }

            $contract->{$key} = $item;

            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $contract->{$key} = $filename;
            }
        }

        $contract->save();

        return Response::json([
            'message' => 'Successfully updated contract.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted contract.',
            'contracts' => Contract::with(['client'])->get()
        ]);
    }

    public function approve($id)
    {
        Contract::where('id', $id)->update([
            'status' => Constants::STATUS_APPROVED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully approved contract.',
        ]);
    }

    public function close($id)
    {
        Contract::where('id', $id)->update([
            'status' => Constants::STATUS_CLOSED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully closed contract.',
        ]);
    }

    public function reopen($id)
    {
        Contract::where('id', $id)->update([
            'status' => Constants::STATUS_APPROVED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully reopened contract.',
        ]);
    }

    public function allocateTruck(Request $request)
    {
      $data = $request->all();
      $contract = Contract::findOrFail($data['contract_id']);

      $currently_allocated_trucks = $contract->vehicles;

      foreach($currently_allocated_trucks as $currently_allocated_truck) {
        $currently_allocated_truck->contract_id = null;
        $currently_allocated_truck->update();
      }

      foreach($data['allocatedtrucks'] as $truck) {
        $truck = Vehicle::findOrFail($truck['id']);
        $contract->vehicles()->save($truck);
      }

      return Response::json([
        'status' => 'success',
        'message' => 'Trucks Successfully Allocated',
        'allocated_trucks' => $contract->vehicles
      ]);
    }


    public function lscontracts ()
    {
      return Response::json([
        'contracts' => Contract::with('client')->get()
      ]);
    }

    public function lscontractShow($id)
    {
      return Response::json([
        'contract' => Contract::where('id', $id)
        ->with(['vehicles', 'client'])
        ->first()
      ]);
    }

    public function contractTrucks ($id)
    {
      return Response::json([
        'contract_trucks' => Contract::select('trucks_allocated')->findOrFail($id),
        'contract_employees' => Employee::where('contract_id', $id)->get(),
        'allocated_trucks' => Vehicle::where('contract_id', $id)->with('trailer')->get()
      ]);
    }

}
