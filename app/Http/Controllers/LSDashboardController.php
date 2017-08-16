<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Contract;
use SmoDav\Models\LocalShunting\LSDelivery;
use SmoDav\Models\LocalShunting\LSFuel;
use SmoDav\Models\LocalShunting\LSGatePass;
use SmoDav\Models\LocalShunting\LSMileage;

class LSDashboardController extends Controller
{
    public function dashboard()
    {
      return Response::json([
        'contracts'   => Contract::with('lsfuels','lsgatepasses','lsdeliveries','lsmileages')->get(),
        'deliveries'  => LSDelivery::all(),
        'fuels'       => LSFuel::all(),
        'gatepasses'  => LSGatePass::all(),
        'mileages'    => LSMileage::all()
      ]);
    }

}
