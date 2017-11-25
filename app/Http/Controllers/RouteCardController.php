<?php

namespace App\Http\Controllers;

use App\RouteCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\Journey;
use SmoDav\Support\Constants;

class RouteCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
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

        $cards = RouteCard::with(['user' => function ($builder) {
            return $builder->select('first_name', 'last_name', 'id');
        }])
            ->whereHas('journey', function ($builder) use ($journeys) {
                return $builder->where(function ($builder) use ($journeys) {
                    return $builder->where('status', Constants::STATUS_APPROVED)
                        ->orWhere(function ($builder) use ($journeys) {
                            return $builder->where('status', Constants::STATUS_CLOSED)
                                ->whereIn('id', $journeys);
                        });
                });
            })
            ->when(\request('s'), function ($builder) {
                return $builder->where('station_id', \request('s'));
            })
            ->get();

        return Response::json([
            'cards' => $cards
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $journeys = Journey::when(request('s'), function ($builder) {
            return $builder->where('station_id', request('s'));
        })
            ->open()
            ->has('delivery')
            ->doesntHave('routeCard')
            ->with(['truck', 'driver', 'truck.trailer'])
            ->get(['id', 'raw', 'truck_id', 'driver_id']);

        return Response::json([
            'status' => 'success',
            'journeys' => $journeys,
            'supervisor' => false,
            'inspector' => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $journey = Journey::find($request->get('journey_id'));
        $lastJourney = Journey::where('truck_id', $journey->truck_id)->orderBy('id', 'desc')
            ->take(2)
            ->get(['updated_at'])
            ->toArray();

        $arrival = Carbon::now();

        if (count($lastJourney) > 1) {
            if (isset($lastJourney[1]['updated_at'])) {
                $arrival = Carbon::parse($lastJourney[1]['updated_at']);
            }
        }

        $data = $request->all();
        $data['arrival_date'] = $arrival;
        $data['arrival_time'] = $arrival;
        $data['departure_date'] = Carbon::parse($data['departure_date']);
        $data['user_id'] = \Auth::id();
        $data['station_id'] = $journey->station_id;

        $card = RouteCard::create($data);

        return Response::json([
            'shouldPrint' => true,
            'printout' => route('route-card-print', $card->id),
            'status' => 'success',
            'message' => 'Successfully generated route card.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        if (\request()->ajax()) {
            $routeCard = RouteCard::with(['journey.truck.trailer', 'journey.driver', 'journey.route'])->find($id);

            return Response::json([
                'status' => 'success',
                'card' => $routeCard
            ]);
        }
        return $this->renderOutput($id);
    }

    private function renderOutput($cardId, $actual = false)
    {
        $routeCard = RouteCard::with([
            'user',
            'journey.driver', 'journey.truck.trailer', 'journey.route.checkpoints', 'journey.station',
            'journey.delivery',
            'journey.inspection' => function ($q) {
                return $q->select('id', 'journey_id');
            },
            'journey.contract.cargoType'
        ])->find($cardId);

        $journey = $routeCard->journey;
        unset($routeCard->journey);

        if ($actual) {
            return view('printouts.route-card')
                ->with('card', $routeCard)
                ->with('journey', $journey)
                ->render();
        }

        return view('printouts.route-card')
            ->with('card', $routeCard)
            ->with('journey', $journey);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
