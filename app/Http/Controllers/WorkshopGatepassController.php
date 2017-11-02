<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Vendor;
use App\WorkshopGatepass;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\JobCard;
use SmoDav\Support\Constants;

class WorkshopGatepassController extends Controller
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $jobCards = JobCard::open()
            ->has('externalServices')
            ->with([
                'vehicle' => function ($builder) {
                    return $builder->select(['id', 'make_id', 'model_id', 'plate_number']);
                },
                'vehicle.make' => function ($builder) {
                    return $builder->select(['id', 'name']);
                },
                'vehicle.model' => function ($builder) {
                    return $builder->select(['id', 'name']);
                },
            ])
            ->whereDoesntHave('gatepass', function ($builder) {
                return $builder->where('status', Constants::STATUS_OPEN);
            })
            ->get();


        return Response::json([
            'job_cards' => $jobCards,
            'drivers' => Driver::all(['id', 'first_name', 'last_name']),
            'suppliers' => Vendor::all(['DCLink', 'Account', 'Name'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WorkshopGatepass  $workshopGatepass
     * @return \Illuminate\Http\Response
     */
    public function show(WorkshopGatepass $workshopGatepass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WorkshopGatepass  $workshopGatepass
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkshopGatepass $workshopGatepass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WorkshopGatepass  $workshopGatepass
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkshopGatepass $workshopGatepass)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WorkshopGatepass  $workshopGatepass
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkshopGatepass $workshopGatepass)
    {
        //
    }
}
