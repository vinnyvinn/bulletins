<?php

namespace App\Http\Controllers;

use App\RouteCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\Journey;

class RouteCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cards = RouteCard::with(['user' => function ($builder) {
            return $builder->select('first_name', 'last_name', 'id');
        }])
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
        $data = $request->all();
        $data['arrival_date'] = Carbon::parse($data['arrival_date']);
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
