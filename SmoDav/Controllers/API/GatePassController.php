<?php

namespace SmoDav\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Response;
use SmoDav\Models\GatePass;
use SmoDav\Models\Journey;
use SmoDav\Support\Constants;

class GatePassController extends Controller
{
    public function awaiting()
    {
        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->open()
            ->doesntHave('gatepass')
            ->whereHas('inspection', function ($query) {
                return $query->where('suitable_for_loading', true);
            })
            ->where(function ($builder) {
                return $builder->has('delivery')->orWhere('ignore_delivery_note', 1);
            })
            ->with(['truck', 'driver', 'truck.trailer'])
            ->get(['id', 'raw', 'truck_id', 'driver_id']);

        return Response::json([
            'status' => 'success',
            'journeys' => $journeys,
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->where('status', Constants::STATUS_CLOSED)
            ->orderBy('id', 'desc')
            ->take(50)
            ->get(['id'])
            ->map(function ($item) {
                return $item->id;
            })
            ->toArray();

        $gatepasses = GatePass::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->whereHas('journey', function ($builder) use ($journeys) {
                return $builder->where(function ($builder) use ($journeys) {
                    return $builder->where('status', Constants::STATUS_APPROVED)
                        ->orWhere(function ($builder) use ($journeys) {
                            return $builder->where('status', Constants::STATUS_CLOSED)
                                ->whereIn('id', $journeys);
                        });
                });
            })
            ->with(['journey', 'journey.driver', 'journey.route', 'journey.truck'])
            ->get();

        return Response::json([
            'gatepasses' => $gatepasses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request('id')) {
            $journey = Journey::with(['driver', 'contract.cargoType', 'route', 'truck.model.make', 'truck.trailer'])
                ->where('id', request('id'))
                ->firstOrFail();

            return Response::json([
                'journey' => $journey,
            ]);
        }

        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->open()
            ->doesntHave('fuel')
            ->has('delivery')
            ->with(['driver', 'contract', 'route', 'truck.model.make', 'truck.trailer'])
            ->get();

        return Response::json([
            'journeys' => $journeys,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();

        GatePass::create($data);

        return Response::json([
            'message' => 'Successfully processed gatepass.',
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $gatePassId
     *
     * @return \Illuminate\Http\Response
     */
    public function show($gatePassId)
    {
        $gatepass = GatePass::with([
                'journey.truck.model.make', 'journey.truck.trailer', 'journey.driver', 'journey.route'
            ])
            ->findOrFail($gatePassId);

        return Response::json([
            'gatepass' => $gatepass,
            'status' => 'success'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $gatePassId
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($gatePassId)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $gatePassId
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $gatePassId)
    {
        $gatepass = GatePass::find($gatePassId);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $gatepass->update($data);

        return Response::json([
            'message' => 'Successfully updated gatepass.',
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $gatePassId
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($gatePassId)
    {
        GatePass::where('id', $gatePassId)->delete();
        $gatepasses = GatePass::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->with(['journey', 'journey.driver', 'journey.route', 'journey.truck'])
            ->get();

        return Response::json([
            'gatepasses' => $gatepasses,
            'status' => 'success',
            'message' => 'Successfully deleted gate pass',
        ]);
    }

    public function printPass($passId)
    {
        $note = GatePass::with([
            'journey.route', 'journey.driver', 'journey.truck.trailer', 'journey.delivery', 'creator'
        ])->findOrFail($passId);
        $note->contract = $note->journey->contract;
        $note->route = $note->journey->route;
        $note->driver = $note->journey->driver;
        $note->truck = $note->journey->truck;
        $note->deliveryNote = $note->journey->delivery;
        if (! $note->journey->is_contract_related) {
            $note->contract = new \stdClass();
            $note->contract->client = new \stdClass();
        }
        unset($note->journey->contract, $note->journey->route, $note->journey->driver, $note->journey->truck, $note->journey->delivery);

        $printout = view('printouts.gatepass')->with('trip', $note)->render();

        return Response::json([
            'message' => 'Successfully completed loading.',
            'shouldPrint' => true,
            'printout' => $printout
        ]);
    }
}
