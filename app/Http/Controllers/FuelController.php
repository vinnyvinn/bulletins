<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fuel;
use Response;
use SmoDav\Models\FuelTruckRoute;
use SmoDav\Models\Journey;
use SmoDav\Models\Delivery;
use SmoDav\Models\Mileage;
use Carbon\Carbon;
use Auth;
use App\Truck;
use SmoDav\Support\Constants;



class FuelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $fuel = Fuel::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->with(['journey', 'journey.driver', 'journey.route', 'journey.truck'])
            ->get();

        return Response::json([
            'fuel' => $fuel
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->open()
            ->has('delivery')
            ->with(['driver', 'route', 'truck.model.make', 'truck.trailer'])
            ->get();

        return Response::json([
            'journeys' => $journeys,
            'fuelRoutes' => FuelTruckRoute::all(['model_id', 'route_id', 'amount'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['date'] = Carbon::parse(str_replace('/', '-', $data['date']))->format('Y-m-d');
        $data['user_id'] = Auth::id();
        Fuel::create($data);

        $journey = Journey::findOrFail($data['journey_id']);

        $truck = $journey->truck;
        $truck->current_km = $data['current_km'];
        $truck->current_fuel = $data['fuel_total'];
        $truck->update();

        return Response::json([
          'message' => 'Fuel Allocation Successfully saved.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $fuel = Fuel::with('journey', 'journey.driver', 'journey.truck', 'journey.route')->findOrFail($id);
        $delivery_note = Delivery::where('journey_id', $fuel->journey_id)->first();
        $mileage = Mileage::where('journey_id', $fuel->journey_id)->first();

        return Response::json([
            'fuel' => $fuel,
            'delivery_note' => $delivery_note,
            'mileage' => $mileage
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
      $data = $request->all();
      unset($data['_token'], $data['_method']);
      $data['date'] = Carbon::parse(str_replace('/', '-', $data['date']))->format('Y-m-d');

      $fuel = Fuel::findOrFail($id);
      $fuel->update($data);

      $journey = Journey::findOrFail($data['journey_id']);

      $truck = $journey->truck;
      $truck->current_km = intval($data['current_km']);
      $truck->current_fuel = intval($data['fuel_total']);
      $truck->update();

      return Response::json([
        'message' => 'Fuel Allocation Successfully updated.'
      ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Fuel::where('id', $id)->delete();
        $fuels = Fuel::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->with(['journey','journey.driver', 'journey.route', 'journey.truck'])->get();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted fuel.',
            'fuel' => $fuels
        ]);
    }

    public function approve($id)
    {
      $fuel = Fuel::findOrFail($id);
      if($fuel->status == 'Awaiting Approval'){
        $fuel->status = Constants::STATUS_APPROVED;
        $fuel->user_id = Auth::id();
        $fuel->save();

        $fuel = Fuel::with(['journey','journey.driver', 'journey.route', 'journey.truck'])->where('id',$id)->first();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully Approved fuel request.',
            'fuel' => $fuel
        ]);
      }
      else {
        $fuel->status = 'Awaiting Approval';
        $fuel->user_id = '';
        $fuel->save();

        // $fuels = Fuel::with(['journey','journey.driver', 'journey.route', 'journey.truck'])->get();
        $fuel = Fuel::with(['journey','journey.driver', 'journey.route', 'journey.truck'])->where('id',$id)->first();

        return Response::json([
            'status' => 'success',
            'message' => 'Approval Revoked.',
            'fuel' => $fuel
        ]);
      }
    }
}
