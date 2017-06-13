<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fuel;
use Response;
use SmoDav\Models\Journey;
use Carbon\Carbon;


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
        return Response::json(['journeys'=>Journey::with(['driver', 'route', 'truck', 'truck.trailer'])->get()]);
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
        $data['date'] = Carbon::parse(str_replace('/', '-', $data['date']))->format('Y-m-d');
        $fuel = Fuel::create($data);

        return Response::json([
          'message' => 'Fuel Allocation Successfully saved.'
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
        $fuel = Fuel::with('journey')->findOrFail($id);

        return Response::json([
          'fuel'=>$fuel
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

      return Response::json([
        'message' => 'Fuel Allocation Successfully updated.'
      ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fuel = Fuel::findOrFail($id)->delete();
        $fuels = Fuel::with(['journey','journey.driver', 'journey.route', 'journey.truck'])->get();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted fuel.',
            'fuel' => $fuels
        ]);
    }

    public function approve($id)
    {
      $fuel = Fuel::findOrFail($id);
      $fuel->status = 'Approved';
      $fuel->save();

      $fuels = Fuel::with(['journey','journey.driver', 'journey.route', 'journey.truck'])->get();

      return Response::json([
          'status' => 'success',
          'message' => 'Successfully Approved fuel request.',
          'fuel' => $fuels
      ]);

    }
}
