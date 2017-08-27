<?php

namespace SmoDav\Controllers\API\LocalShunting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SmoDav\Models\LocalShunting\LSGatePass;
use SmoDav\Models\Vehicle;
use Auth;
use Response;

class GatePassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
        'gatepasses' => LSGatePass::with('vehicle', 'vehicle.driver', 'user')->get()
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Response::json([
        'vehicles' => Vehicle::has('contract')
                  ->doesntHave('lsgatepass')
                  ->whereDoesntHave('lsdelivery', function ($q) {
                      return $q->where('status', 'Loaded');
                  })
                  ->get(),
        'gatepasses' => LSGatePass::with('vehicle', 'user')->get()
      ]);
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
          'vehicle_id' => 'required|unique:l_s_gate_passes'
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::id();

        $gatepass = LSGatePass::create($data);

        return Response::json([
          'status' => 'success',
          'message' => 'Successfully created gatepass inwards',
          'gatepasses' => LSGatePass::with('vehicle', 'user')->get(),
          'vehicles' => Vehicle::has('contract')
                    ->doesntHave('lsgatepass')
                    ->whereDoesntHave('lsdelivery', function ($q) {
                        return $q->where('status', 'Loaded');
                    })
                    ->get(),
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
        //
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
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
