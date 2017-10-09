<?php

namespace App\Http\Controllers;

use App\CheckpointRoute;
use App\Route;
use Illuminate\Http\Request;
use SmoDav\Models\Checkpoint;

class CheckpointRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //$table->unsignedInteger('route_id')->index();
//        $table->unsignedInteger('checkpoint_id')->index();
//        $table->integer('number');
//        $table->double('minutes')->default(0);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return \Response::json([
            'checkpoints' => Checkpoint::all(['name', 'id'])
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
        $data = $request->get('checkpoints');
        $data = array_map(function ($checkpoint) use ($request) {
            $checkpoint['route_id'] = $request->get('route_id');

            return $checkpoint;
        }, $data);

        \DB::table('checkpoint_routes')->where('route_id', $request->get('route_id'))->delete();
        \DB::table('checkpoint_routes')->insert($data);

        return \Response::json([
            'message' => 'Successfully updated checkpoints.',
            'status' => 'success'
        ]);
    }

    public function show($routeId)
    {
        $route = Route::with(['checkpoints'])->find($routeId);
        $route->checkpoints = $route->checkpoints->sortBy('number');


        return \Response::json([
            'route' => $route,
            'checkpoints' => Checkpoint::all(['name', 'id'])->keyBy('id'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CheckpointRoute  $checkpointRoute
     * @return \Illuminate\Http\Response
     */
    public function edit(CheckpointRoute $checkpointRoute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CheckpointRoute  $checkpointRoute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CheckpointRoute $checkpointRoute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CheckpointRoute  $checkpointRoute
     * @return \Illuminate\Http\Response
     */
    public function destroy(CheckpointRoute $checkpointRoute)
    {
        //
    }
}
