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
use SmoDav\Models\Journey;
use SmoDav\Models\Mileage;
use SmoDav\Support\Constants;
use Auth;

class MileageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
            'mileages' => Mileage::when(request('s'), function ($builder) {
                return $builder->where('station_id', request('s'));
            })->get()
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
            'journeys' => Journey::when(request('s'), function ($builder) {
                return $builder->where('station_id', request('s'));
            })
                ->open()
                ->has('delivery')
                ->doesntHave('mileage')
                ->with(['driver', 'truck.trailer', 'route'])
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
        $data['user_id'] = Auth::id();

        $mileage = Mileage::create($data);

        return Response::json([
            'message' => 'Successfully created new mileage voucher number MLG-' . $mileage->id
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
        $mileage = Mileage::findOrFail($id);

        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->where('id', $mileage->journey_id)
            ->has('delivery')
            ->with(['driver', 'truck.trailer', 'route'])
            ->get();

        return Response::json([
            'mileage' => $mileage,
            'journeys' => $journeys
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @param Mileage $mileage
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Mileage $mileage)
    {
        $data = $request->all();
        unset($data['_token'], $data['_method']);
        $data['raw'] = json_encode($data);

        foreach ($data as $key => $value) {
            if ($value == 'null') {
                unset($data[$key]);
            }
        }

        $data['balance_amount'] = $data['standard_amount'] - $data['approved_amount'];

        $mileage->update($data);

        return Response::json([
            'message' => 'Successfully updated mileage voucher number MLG-' . $mileage->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Mileage $mileage
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function destroy(Mileage $mileage)
    {
        $mileage->delete();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted mileage voucher.',
            'mileages' => Mileage::when(request('s'), function ($builder) {
                return $builder->where('station_id', request('s'));
            })
                ->get()
        ]);
    }

    public function approve($id)
    {
        Mileage::where('id', $id)->update([
            'status' => Constants::STATUS_APPROVED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully approved mileage allowance.',
        ]);
    }

    public function close($id)
    {
        Journey::where('id', $id)->update([
            'status' => Constants::STATUS_CLOSED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully closed journey.',
        ]);
    }

    public function reopen($id)
    {
        Journey::where('id', $id)->update([
            'status' => Constants::STATUS_APPROVED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully reopened journey.',
        ]);
    }
}
