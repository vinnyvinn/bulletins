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

class LSDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
          'deliveries' => LSDelivery::with('vehicle', 'vehicle.driver')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if(request('id')) {
        return Response::json([
          'vehicle' => Vehicle::where('id',request('id'))->with('contract','driver','trailer')->first(),
          'drivers' => Driver::unassigned()->get()
        ]);
      }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'loading_weighbridge_number' => 'bail|required|unique:l_s_deliveries',
         ]);
        $data = $request->all();

        //Detach vehicle from gatepass
        $vehicle = Vehicle::findOrFail($data['vehicle_id']);
        $lsgatepass = LSGatepass::where('vehicle_id',$data['vehicle_id'])->first();
        $lsgatepass->delete();

        $data['user_id'] = Auth::id();
        $data['loading_time'] = Carbon::now();
        $lsdelivery = LSDelivery::create($data);

        return Response::json([
          'status' => 'success',
          'message' => 'Successfully created delivery note'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
