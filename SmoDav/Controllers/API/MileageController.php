<?php

namespace SmoDav\Controllers\API;

use App\Driver;
use App\Http\Controllers\Controller;
use App\Route;
use App\Truck;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\Journey;
use SmoDav\Models\Mileage;
use SmoDav\Models\MileageType;
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

        $mileages = Mileage::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->with([
                'journey' => function ($builder) {
                    return $builder->select(['id', 'status', 'truck_id', 'driver_id']);
                },
                'journey.truck' => function ($builder) {
                    return $builder->select('id', 'plate_number');
                },
                'journey.driver' => function ($builder) {
                    return $builder->select('id', 'first_name', 'last_name');
                }
            ])
            ->whereHas('journey', function ($builder) use ($journeys) {
                return $builder->where(function ($builder) use ($journeys) {
                    return $builder->where('status', Constants::STATUS_APPROVED)
                        ->orWhere(function ($builder) use ($journeys) {
                            return $builder->where('status', Constants::STATUS_CLOSED)
                                ->whereIn('id', $journeys);
                        });
                });
            })
            ->get([
                'id', 'journey_id', 'mileage_type', 'standard_amount', 'requested_amount', 'approved_amount', 'status'
            ]);

        return Response::json([
            'mileages' => $mileages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function create()
    {
        if (request('id')) {
            $journey = Journey::with(['driver', 'truck.trailer', 'route', 'mileages' => function ($builder) {
                return $builder->select(['journey_id', 'mileage_type']);
            }])
                    ->where('id', request('id'))
                    ->firstOrFail();

            $toExclude = ['Return Mileage'];

            foreach ($journey->mileages as $mile) {
                $toExclude[] = $mile->mileage_type;
            }

            $mileages = MileageType::whereNotIn('name', $toExclude)->get(['name', 'slug']);

            unset($journey->mileages);

            return Response::json([
                'journey' => $journey,
                'mileageTypes' => $mileages,
                'toExclude' => $toExclude,
            ]);
        }

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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        unset($data['_token'], $data['_method']);
        $data['raw'] = \json_encode($data);

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

        $journey = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->where('id', $mileage->journey_id)
            ->with(['driver', 'truck.trailer.model.make', 'route', 'mileages' => function ($builder) use ($mileage) {
                return $builder->where('mileage_type', '<>', $mileage->mileage_type)->select(['journey_id', 'mileage_type']);
            }])
            ->first();

        $toExclude = [];

        if ($mileage->mileage_type != 'Return Mileage') {
            $toExclude[] = 'Return Mileage';
        }

        foreach ($journey->mileages as $mile) {
            $toExclude[] = $mile->mileage_type;
        }

        unset($journey->mileages);

        $mileages = MileageType::whereNotIn('name', $toExclude)->get(['name', 'slug']);

        return Response::json([
            'mileage' => $mileage,
            'journey' => $journey,
            'mileageTypes' => $mileages,
            'toExclude' => $toExclude,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Mileage                  $mileage
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Mileage $mileage)
    {
        $data = $request->all();
        unset($data['_token'], $data['_method']);
        $data['raw'] = \json_encode($data);

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
     */
    public function destroy(Mileage $mileage)
    {
        $mileage->delete();
        $mileages = Mileage::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->with(['journey' => function ($builder) {
                return $builder->select(['id', 'status']);
            }])
            ->get([
                'id', 'journey_id', 'mileage_type', 'standard_amount', 'requested_amount', 'approved_amount', 'status'
            ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted mileage voucher.',
            'mileages' => $mileages
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

    public function awaiting()
    {
        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->open()
            ->doesntHave('mileage')
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
}
