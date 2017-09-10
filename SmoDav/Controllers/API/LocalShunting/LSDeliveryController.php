<?php

namespace SmoDav\Controllers\API\LocalShunting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SmoDav\Models\LocalShunting\LSDelivery;
use Auth;
use Response;
use SmoDav\Models\Vehicle;
use \Carbon\Carbon;
use SmoDav\Models\LocalShunting\LSGatePass;
use App\Driver;
use SmoDav\Support\Constants;

class LSDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return Response::json([
            'deliveries' => LSDelivery::with('vehicle', 'vehicle.driver')
                ->when(\request('contract'), function ($builder) {
                    return $builder->where('contract_id', \request('contract'));
                })
                ->where('status', Constants::LOADED)
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request('id')) {
            return Response::json([
                'vehicle' => Vehicle::where('id', request('id'))->with('contract', 'driver', 'trailer')->first(),
                'drivers' => Driver::unassigned()->orderBy('first_name')->get(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'loading_weighbridge_number' => 'bail|required|unique:l_s_deliveries',
        ]);
        $data = $request->all();

        //Detach vehicle from gatepass
        Vehicle::findOrFail($data['vehicle_id']);
        $lsgatepass = LSGatepass::where('vehicle_id', $data['vehicle_id'])->first();
        $lsgatepass->delete();

        $data['user_id'] = Auth::id();
        $data['loading_time'] = Carbon::now();
        LSDelivery::create($data);

        return Response::json([
            'status'  => 'success',
            'message' => 'Successfully created delivery note',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Response::json([
            'delivery' => LSDelivery::where('id', $id)->with('vehicle', 'vehicle.driver', 'vehicle.trailer')->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token'], $data['_method']);
        $data['raw'] = \json_encode($data);

        foreach ($data as $key => $value) {
            if ($value == 'null') {
                unset($data[$key]);
            }
        }

        $data['offloading_time'] = Carbon::now();
        $data['status'] = Constants::OFFLOADED;

        $delivery = LSDelivery::findOrFail($id);
        $delivery->update($data);

        return Response::json([
            'message' => 'Successfully offloaded',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
