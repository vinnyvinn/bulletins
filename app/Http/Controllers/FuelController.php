<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fuel;
use Response;
use SmoDav\Models\Journey;


class FuelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json(['fuel'=>Fuel::with(['journey','journey.driver', 'journey.route', 'journey.truck'])->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Response::json(['journeys'=>Journey::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $fuel = new Fuel;
        $fuel->journey_id = 1;
        $fuel->date = '2017-06-13 00:10:14.000';
        $fuel->current_fuel = 500;
        $fuel->fuel_issued = 1000;
        $fuel->status = 'waiting';

        $fuel->save();

        return Response::json([
          'message' => 'Fuel Allocation Successfully saved.'
        ]);

        // return Response::json([
        //   $request->all()
        // ]);
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
}
