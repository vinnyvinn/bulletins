<?php

namespace SmoDav\Controllers\API\LocalShunting;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SmoDav\Models\LocalShunting\LSMileage;
use SmoDav\Models\LocalShunting\LSDelivery;
use Auth;
use Response;
use SmoDav\Models\Vehicle;
use App\Contract;
use App\Employee;
use App\ContractConfig;

class LSMileageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    public function createMileage($truck, $contract)
    {
      if($truck && $contract) {
        return Response::json([
          'deliveries' => LSDelivery::where('contract_id', $contract)
              ->where('vehicle_id',$truck)
              ->with(['vehicle','user', 'temporary_driver'])
              ->get(),
          'mileages' => LSMileage::where('contract_id', $contract)
              ->where('vehicle_id',$truck)
              ->with('user')
              ->get(),
          'vehicle' =>Vehicle::where('id', $truck)
              ->with('trailer','driver')
              ->first(),
          'drivers_rate' => ContractConfig::where('contract_id', $contract)->first(['drivers_rate'])
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
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $lsmileage = LSMileage::create($data);
        return Response::json([
          'lsmileages' => LSMileage::where('contract_id', $request->contract_id)
            ->where('vehicle_id', $request->vehicle_id)
            ->with('user')
            ->get(),
          'status' => 'success',
          'message' => 'Mileage successfully allocated'
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
        //
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

    public function lsemployeeMileage ($id)
    {
      return Response::json([
        'vehicles' => Vehicle::where('contract_id', $id)->with('driver')->get(),
        'employees' => Employee::where('contract_id', $id)->get()
      ]);
    }
}
