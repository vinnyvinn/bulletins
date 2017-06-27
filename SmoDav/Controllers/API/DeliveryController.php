<?php

namespace SmoDav\Controllers\API;

use App\Client;
use App\Contract;
use App\Driver;
use App\Http\Controllers\Controller;
use App\Route;
use App\Truck;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\CargoClassification;
use SmoDav\Models\CargoType;
use SmoDav\Models\CarriagePoint;
use SmoDav\Models\Delivery;
use SmoDav\Models\Journey;
use SmoDav\Models\Mileage;
use SmoDav\Support\Constants;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
            'deliveries' => Delivery::all()
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
            'journeys' => Journey::open()
                ->whereHas('inspection', function ($query) {
                    $query->where('suitable_for_loading', true);
                })
                ->doesntHave('delivery')
                ->with(['driver', 'truck.trailer', 'route', 'contract'])
                ->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        unset($data['_token'], $data['_method']);
        $data['raw'] = json_encode($data);

        foreach ($data as $key => $value) {
            if ($value == 'null') {
                unset($data[$key]);
            }
        }

        $data['loading_time'] = Carbon::now();
        $delivery = Delivery::create($data);

        return Response::json([
            'message' => 'Successfully created new delivery note number DLN-' . $delivery->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $journeys = Journey::open()
            ->whereHas('inspection', function ($query) {
                return $query->where('suitable_for_loading', true);
            })
            ->whereHas('delivery', function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->with(['driver', 'truck.trailer', 'route', 'contract'])
            ->get();

        return Response::json([
            'delivery' => Delivery::findOrFail($id),
            'journeys' => $journeys,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Delivery $delivery
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $delivery)
    {
        $data = $request->all();
        unset($data['_token'], $data['_method']);
        $data['raw'] = json_encode($data);

        foreach ($data as $key => $value) {
            if ($value == 'null') {
                unset($data[$key]);
            }
        }

        $data['offloading_time'] = Carbon::now();
        $delivery->update($data);

        return Response::json([
            'message' => 'Successfully updated delivery note number DLN-' . $delivery->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Delivery $delivery
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted delivery note.',
            'deliveries' => Delivery::all()
        ]);
    }

    public function printNote($id)
    {
        $note = Delivery::with([
            'journey.contract.cargoType', 'journey.route', 'journey.driver', 'journey.truck.trailer'
        ])->findOrFail($id);
        $note->contract = $note->journey->contract;
        $note->route = $note->journey->route;
        $note->driver = $note->journey->driver;
        $note->truck = $note->journey->truck;
        if (! $note->journey->is_contract_related) {
            $note->contract = new \stdClass();
            $note->contract->client = new \stdClass();
        }
        unset($note->journey->contract, $note->journey->route, $note->journey->driver, $note->journey->truck);

        $printout = view('printouts.deliverynote')->with('trip', $note)->render();

        return Response::json([
            'message' => 'Successfully completed loading.',
            'shouldPrint' => true,
            'printout' => $printout
        ]);
    }
}
