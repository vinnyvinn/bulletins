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
        $inspections = Inspection::with(['journey','journey.truck','journey.truck.trailer','journey.driver'])->get([
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
        return Response::json([
            'status' => 'success',
            'journeys' => Journey::open()->doesntHave('inspection')->with('truck','truck.driver','truck.trailer')->get(['id', 'raw', 'truck_id']),
            'supervisor' => false,
            'inspector' => true,
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
        unset($data['_token'], $data['_method']);
        $data['fields'] = json_encode($data);
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
        $inspection = Inspection::findOrFail($id);
        $inspection->fields = json_decode($inspection->fields);

        return Response::json([
            'status' => 'success',
            'journeys' => Journey::open()->with(['truck.driver', 'truck.trailer'])->get(['id', 'raw', 'truck_id']),
            'supervisor' => true,
            'inspector' => false,
            'inspection' => $inspection
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @param Inspection                $inspection
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Inspection $inspection)
    {
        $data = $request->all();
        unset($data['_token'], $data['_method']);
        $data['fields'] = json_encode($data);
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
        $inspections = Inspection::with(['journey'])->get([
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

    public function newInspection ($id)
    {
        return Response::json([
            'status' => 'success',
            'journey' => Journey::with('truck','truck.driver','truck.trailer','route')->where('id',$id)->first(['id', 'raw', 'truck_id','route_id']),
            'supervisor' => false,
            'inspector' => true,
        ]);
    }

}
