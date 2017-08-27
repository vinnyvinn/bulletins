<?php

namespace App\Http\Controllers;

use App\Allocation;
use App\Contract;
use App\Support\Core;
use App\Truck;
use Illuminate\Http\Request;
use Response;
use SmoDav\Factory\TruckFactory;

class AllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
            'allocations' => TruckFactory::assigned()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function create()
    {
        return Response::json([
            'trucks' => TruckFactory::unassigned(),
            'contracts' => Contract::current()->get(['name', 'id'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Truck::whereIn('id', $request->get('truck_id'))->update([
            'contract_id' => $request->get('contract_id'),
            'location' => Core::PRE_LOADING
        ]);

        return Response::json([
            'message' => 'Successfully allocated truck.',
        ]);
    }

    public function edit($id)
    {
        $allocation = TruckFactory::findOrFail($id);

        return Response::json([
            'allocation' => $allocation,
            'contracts' => Contract::current()->get(['name', 'id'])
        ]);
    }

    public function update(Request $request, $id)
    {
        $truck = TruckFactory::findOrFail($id);
        $truck->update([
            'contract_id' => $request->get('contract_id'),
        ]);

        return Response::json([
            'message' => 'Successfully modified allocation',
        ]);
    }

    public function destroy($id)
    {
        $truck = TruckFactory::findOrFail($id);
        $truck->update([
            'contract_id' => null,
            'location' => Core::AWAITING_ALLOCATION
        ]);

        return Response::json([
            'message' => 'Successfully unassigned truck',
            'allocations' => TruckFactory::assigned()
        ]);
    }
}
