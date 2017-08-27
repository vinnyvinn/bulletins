<?php

namespace SmoDav\Controllers\API\LocalShunting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SmoDav\Models\LocalShunting\LSFuel;
use Auth;
use Response;
use SmoDav\Models\Vehicle;
use SmoDav\Support\Constants;


class LSFuelController extends Controller
{

    public function lsfuelindex()
    {
        return Response::json([
          'lsfuels' => LSFuel::with('vehicle','vehicle.driver','user','approved_by')->get()
        ]);
    }

    public function index()
    {
        return Response::json([
          'vehicles' => Vehicle::has('contract')->with('driver','trailer')->get()
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $message = 'Truck successfully issued with fuel';

        $data = $request->all();
        $data['created_by'] = Auth::id();

        if($data['needs_approval']) {
          $data['status'] = Constants::STATUS_PENDING;
          $message = 'Fuel Request pending approval';
        } else {
          $data['status'] = Constants::STATUS_APPROVED;
        }

        $lsfuel = LSFuel::create($data);

        $vehicle = Vehicle::findOrFail($data['vehicle_id']);
        $vehicle->current_km = $data['current_km'];
        $vehicle->current_fuel = $data['total_in_tank'];
        $vehicle->update();



        return Response::json([
          'status' => 'Success',
          'message' => $message
        ]);

    }

    public function show($id)
    {
      return Response::json([
        'lsfuel' => LSFuel::where('id', $id)->with('contract', 'contract.route', 'contract.contractConfig', 'vehicle','vehicle.driver','user','approved_by')->first()
      ]);
    }

    public function approveLSFuel($id)
    {
        $lsfuel = LSFuel::findOrFail($id);
        $lsfuel->status = Constants::STATUS_APPROVED;
        $lsfuel->approved_by = Auth::id();
        $lsfuel->update();

        return Response::json([
          'status' => 'success',
          'message' => 'Fuel approved successfully',
          'lsfuel' => LSFuel::where('id', $id)->with('vehicle','vehicle.driver','user','approved_by')->first()
        ]);
    }


}
