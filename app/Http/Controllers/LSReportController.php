<?php

namespace App\Http\Controllers;

use Response;
use App\Client;
use App\Contract;
use SmoDav\Models\LocalShunting\LSDelivery;

class LSReportController extends Controller
{
    public function index()
    {
        return Response::json([
          'contracts' => Contract::with('client')->get()
        ]);
    }

    public function create()
    {
        //
    }

    public function show($id)
    {
        return Response::json([
          'contract' => Contract::where('id', $id)->with('client')
              ->with('lsdeliveries','lsdeliveries.vehicle','lsdeliveries.vehicle.driver',
              'lsfuels','lsfuels.vehicle','lsfuels.vehicle.driver',
              'lsgatepasses', 'lsgatepasses.vehicle', 'lsgatepasses.vehicle.driver')
              ->first()
        ]);
    }

    public function loadingUnloading($id)
    {
        return Response::json([
        'lsdeliveries' => LSDelivery::where('contract_id', $id)
              ->with('vehicle', 'vehicle.driver')
              ->get(),
        'contract' => Contract::where('id', $id)
              ->with('client', 'route')
              ->first()
      ]);
    }
}
