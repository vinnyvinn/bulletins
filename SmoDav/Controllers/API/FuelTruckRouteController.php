<?php

namespace SmoDav\Controllers\API;

use App\Http\Controllers\Controller;
use App\Route;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\FuelTruckRoute;
use SmoDav\Models\Make;

class FuelTruckRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $routes = FuelTruckRoute::with(['model.make', 'route'])
            ->get()
            ->map(function ($route) {
                $route->isEdit = false;
                $route->editValue = $route->amount;

                return $route;
            });

        return Response::json([
            'fuelRoutes' => $routes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $routes = Route::all(['id', 'source', 'destination', 'distance'])->keyBy('id');
        $makes = Make::with(['models' => function ($builder) {
            return $builder->select(['id', 'make_id', 'name']);
        }])->get(['id', 'name'])->keyBy('id');


        return Response::json([
            'routes' => $routes,
            'makes' => $makes,
            'fuelRoutes' => FuelTruckRoute::all(['model_id', 'route_id'])
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
        FuelTruckRoute::create($request->all());

        $routes = Route::all(['id', 'source', 'destination', 'distance'])->keyBy('id');
        $makes = Make::with(['models' => function ($builder) {
            return $builder->select(['id', 'make_id', 'name']);
        }])->get(['id', 'name'])->keyBy('id');


        return Response::json([
            'status' => 'success',
            'message' => 'Successfully entered fuel requirements.',
            'routes' => $routes,
            'makes' => $makes,
            'fuelRoutes' => FuelTruckRoute::all(['model_id', 'route_id'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $fuelRouteId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $fuelRouteId)
    {
        $fuel = FuelTruckRoute::findOrFail($fuelRouteId);
        $data = $request->all();
        $data['amount'] = $data['editValue'];
        $fuel->update($data);
        $fuel->isEdit = false;
        $fuel->editValue = $fuel->amount;

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully updated record.',
            'fuelRoute' => $fuel
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $fuelRouteId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($fuelRouteId)
    {
        FuelTruckRoute::where('id', $fuelRouteId)->delete();

        return Response::json([
            'message' => 'Successfully deleted fuel allocation',
            'status' => 'success',
            'fuelRoutes' => FuelTruckRoute::with(['model.make', 'route'])->get()
        ]);
    }
}
