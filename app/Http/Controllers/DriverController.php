<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Http\Requests\DriverRequest;
use Illuminate\Http\Request;
use Response;
use SmoDav\Factory\DriverFactory;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json(DriverFactory::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(DriverRequest $request)
    {
        $driver = DriverFactory::create($request->all());

        return Response::json([
            'message' => 'Successfully added new driver.',
            'driver' => $driver
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $driver = DriverFactory::findOrFail($id);

        return Response::json([
            'driver' => $driver
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(DriverRequest $request, $id)
    {
        $driver = DriverFactory::findOrFail($id);

        DriverFactory::update($driver, $request->all());

        return Response::json([
            'message' => 'Successfully updated driver details.',
            'driver' => $driver
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        //
    }
}
