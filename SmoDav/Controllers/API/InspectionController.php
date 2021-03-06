<?php

namespace SmoDav\Controllers\API;

use App\Driver;
use App\Http\Controllers\Controller;
use App\Route;
use App\Truck;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\Inspection;
use SmoDav\Models\Journey;
use SmoDav\Support\Constants;

class InspectionController extends Controller
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
            ->where('status', Constants::STATUS_CLOSED)
            ->orderBy('id', 'desc')
            ->take(50)
            ->get(['id'])
            ->map(function ($item) {
                return $item->id;
            })
            ->toArray();

        $inspections = Inspection::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->whereHas('journey', function ($builder) use ($journeys) {
                return $builder->where(function ($builder) use ($journeys) {
                    return $builder->where('status', Constants::STATUS_APPROVED)
                        ->orWhere(function ($builder) use ($journeys) {
                            return $builder->where('status', Constants::STATUS_CLOSED)
                                ->whereIn('id', $journeys);
                        });
                });
            })
            ->with(['journey', 'journey.truck', 'journey.truck.trailer', 'journey.driver'])
            ->get([
                'id', 'journey_id', 'status', 'from_station', 'to_station', 'created_at','suitable_for_loading'
            ]);

        return Response::json([
            'inspections' => $inspections
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->open()
            ->doesntHave('inspection')
            ->with(['truck', 'driver', 'truck.trailer'])
            ->get(['id', 'raw', 'truck_id', 'driver_id']);

        return Response::json([
            'status' => 'success',
            'journeys' => $journeys,
            'supervisor' => false,
            'inspector' => true,
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
        unset($data['_token'], $data['_method']);
        $data['fields'] = \json_encode($data);
        $data['inspector_id'] = \Auth::id();
        $data['status'] = Constants::STATUS_APPROVED;
        $data['suitable_for_loading'] = $data['suitable_for_loading'] != 0;

        $inspection = Inspection::create($data);

        return Response::json([
            'message' => 'Successfully created new inspection number INSP-' . $inspection->id
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
        $inspection = Inspection::with(['journey','journey.truck','journey.driver','journey.truck.trailer','journey.route'])
        ->where('id', $id)
        ->first();
        $inspection->fields = \json_decode($inspection->fields);

        return Response::json([
            'status' => 'success',
            'supervisor' => true,
            'inspector' => false,
            'inspection' => $inspection
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Inspection               $inspection
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Inspection $inspection)
    {
        $data = $request->all();
        unset($data['_token'], $data['_method']);
        $data['fields'] = \json_encode($data);
        $data['inspector_id'] = \Auth::id();
        $data['status'] = Constants::STATUS_APPROVED;
        $data['suitable_for_loading'] = $data['suitable_for_loading'] != 0;

        $inspection->update($data);

        return Response::json([
            'message' => 'Successfully updated inspection number INSP-' . $inspection->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Inspection $inspection
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Inspection $inspection)
    {
        $inspection->delete();
        $inspections = Inspection::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->with(['journey'])->get([
                'id', 'journey_id', 'status', 'from_station', 'to_station', 'created_at'
            ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted inspection.',
            'inspections' => $inspections
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

    public function newInspection($id)
    {
        return Response::json([
            'status' => 'success',
            'journey' => Journey::with(['truck', 'driver', 'truck.trailer', 'route'])
                ->where('id', $id)
                ->first(['id', 'raw', 'truck_id', 'driver_id', 'route_id']),
            'supervisor' => false,
            'inspector' => true,
        ]);
    }
}
