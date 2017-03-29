<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Driver;
use App\Http\Requests\TruckRequest;
use App\Support\Core;
use App\Truck;
use Illuminate\Http\Request;
use Response;
use SmoDav\Factory\TruckFactory;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
            'trucks' => TruckFactory::all()
        ]);
    }

    public function create()
    {
        return Response::json([
            'drivers' => Driver::unassigned()->get(['id', 'name']),
        ]);
    }

    public function store(TruckRequest $request)
    {
        TruckFactory::create($request->all());

        return Response::json([
            'message' => 'Successfully added new truck.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     *
     */
    public function show($id)
    {
        return Response::json([
            'truck' => TruckFactory::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TruckRequest $request
     * @param                           $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(TruckRequest $request, $id)
    {
        TruckFactory::update($request->all(), $id);

        return Response::json([
            'message' => 'Successfully updated truck.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Truck  $truck
     * @return \Illuminate\Http\Response
     */
    public function destroy(Truck $truck)
    {
        //
    }

    public function progress($id)
    {
        $truck = TruckFactory::findOrFail($id);
        $nextStep = Core::nextStep($truck->location);

        if ($nextStep == Core::IN_YARD) {
            TruckFactory::createBilling($truck);
        }

        $truck->update(['location' => $nextStep]);

        return Response::json([
            'message' => 'Successfully moved to stage ' . $nextStep,
            'trucks' => TruckFactory::all()
        ]);
    }

    public function allocated()
    {
        return Response::json([
            'trucks' => TruckFactory::assigned()
        ]);
    }
}
