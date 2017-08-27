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
            ->with(['journey', 'journey.driver', 'journey.route', 'journey.truck','user'])
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
        if (request('id')) {
            $journey = Journey::with(['driver', 'route', 'truck.model.make', 'truck.trailer'])
                ->where('id', request('id'))
                ->firstOrFail();

            $fuelRoute = FuelTruckRoute::where('route_id', $journey->route_id)
                ->where('model_id', $journey->truck->model_id)
                ->first(['model_id', 'route_id', 'amount']);

            return Response::json([
                'journey' => $journey,
                'fuelRoute' => $fuelRoute,
                'model_id' => $journey->truck->model_id
            ]);
        }

        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->open()
            ->doesntHave('fuel')
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['date'] = Carbon::parse(\str_replace('/', '-', $data['date']))->format('Y-m-d');
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
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $fuel = Fuel::with([
            'journey', 'journey.driver', 'journey.truck.model.make', 'journey.route', 'journey.truck.trailer', 'user'
        ])->findOrFail($id);
        $delivery_note = Delivery::where('journey_id', $fuel->journey_id)->first();
        $mileages = Mileage::with(['user'])->where('journey_id', $fuel->journey_id)->get();

        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->open()
            ->has('delivery')
            ->where('id', $fuel->journey_id)
            ->with(['driver', 'route', 'truck.model.make', 'truck.trailer'])
            ->get();

        $config = [
            'name' => config('app.name'),
            'telephone' => config('app.telephone'),
            'email' => config('app.email'),
            'location' => config('app.location'),
        ];

        $fuelRoute = FuelTruckRoute::where('route_id', $fuel->journey->route_id)
            ->where('model_id', $fuel->journey->truck->model_id)
            ->first(['model_id', 'route_id', 'amount']);

        return Response::json([
            'fuelRoute' => $fuelRoute,
            'fuel' => $fuel,
            'delivery_note' => $delivery_note,
            'mileages' => $mileages,
            'config' => $config
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token'], $data['_method']);
        $data['date'] = Carbon::parse(\str_replace('/', '-', $data['date']))->format('Y-m-d');
        $data['user_id'] = Auth::id();

        $fuel = Fuel::findOrFail($id);
        $fuel->update($data);

        $journey = Journey::findOrFail($data['journey_id']);

        $truck = $journey->truck;
        $truck->current_km = \floatval($data['current_km']);
        $truck->current_fuel = \intval($data['fuel_total']);
        $truck->update();

        return Response::json([
            'message' => 'Fuel Allocation Successfully updated.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
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
        if ($fuel->status == 'Awaiting Approval') {
            $fuel->status = Constants::STATUS_APPROVED;
            $fuel->user_id = Auth::id();
            $fuel->save();

            $fuel = Fuel::with(['journey', 'journey.driver', 'journey.route', 'journey.truck'])
                ->where('id', $id)->first();

            return Response::json([
                'status' => 'success',
                'message' => 'Successfully Approved fuel request.',
                'fuel' => $fuel,
            ]);
        }
        $fuel->status = 'Awaiting Approval';
        $fuel->user_id = '';
        $fuel->save();

        // $fuels = Fuel::with(['journey','journey.driver', 'journey.route', 'journey.truck'])->get();
        $fuel = Fuel::with(['journey', 'journey.driver', 'journey.route', 'journey.truck'])
            ->where('id', $id)
            ->first();

        return Response::json([
            'status' => 'success',
            'message' => 'Approval Revoked.',
            'fuel' => $fuel,
        ]);
    }

    public function awaiting()
    {
        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->open()
            ->doesntHave('fuel')
            ->whereHas('inspection', function ($query) {
                return $query->where('suitable_for_loading', true);
            })
            ->where(function ($builder) {
                return $builder->has('delivery')->orWhere('ignore_delivery_note', 1);
            })
            ->with(['truck', 'driver', 'truck.trailer'])
            ->get(['id', 'raw', 'truck_id', 'driver_id']);

        return Response::json([
            'status' => 'success',
            'journeys' => $journeys,
        ]);
    }

    public function lsfuel(Request $request)
    {
        $data = $request->all();
    }
}
