<?php

namespace SmoDav\Controllers\API\LocalShunting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SmoDav\Models\LocalShunting\LSFuel;
use Auth;
use Response;
use SmoDav\Models\Vehicle;

class LSFuelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
          'vehicles' => Vehicle::has('contract')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['created_by'] = Auth::id();
        $lsfuel = LSFuel::create($data);

        $vehicle = Vehicle::findOrFail($data['vehicle_id']);
        $vehicle->current_km = $data['current_km'];
        $vehicle->current_fuel = $data['total_in_tank'];
        $vehicle->update();

        return Response::json([
          'status' => 'Success',
          'message' => 'Truck successfully issued with fuel'
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
