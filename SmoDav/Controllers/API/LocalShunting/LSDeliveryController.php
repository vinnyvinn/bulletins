<?php

namespace SmoDav\Controllers\API\LocalShunting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SmoDav\Models\LocalShunting\LSDelivery;
use Auth;
use Response;
use SmoDav\Models\Vehicle;
use \Carbon\Carbon;

class LSDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if(request('id')) {
        return Response::json([
          'vehicle' => Vehicle::where('id',request('id'))->with('contract','driver','trailer')->first()
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
        $this->validate($request, [
            'loading_weighbridge_number' => 'bail|required|unique:l_s_deliveries',             
         ]);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['loading_time'] = Carbon::now();
        $lsdelivery = LSDelivery::create($data);

        return Response::json([
          'status' => 'success',
          'message' => 'Successfully created delivery note'
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
