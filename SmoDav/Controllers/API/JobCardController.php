<?php

namespace SmoDav\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SmoDav\Factory\TruckFactory;
use SmoDav\Models\JobCard;
use SmoDav\Models\WorkshopEmployee;
use SmoDav\Models\WorkshopInspectionCheckList;
use SmoDav\Models\WorkshopJobType;

class JobCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return \Response::json([
            'job_cards' => JobCard::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $trucks = TruckFactory::all();

        return \Response::json([
            'vehicles' => $trucks,
            'job_types' => WorkshopJobType::with(['operations.tasks'])->get(['id', 'name', 'service_type']),
            'checklist' => WorkshopInspectionCheckList::all(['name', 'id']),
            'employees' => WorkshopEmployee::all(['id', 'first_name', 'last_name'])
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
     * @param  \App\JobCard  $jobCard
     * @return \Illuminate\Http\Response
     */
    public function show(JobCard $jobCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobCard  $jobCard
     * @return \Illuminate\Http\Response
     */
    public function edit(JobCard $jobCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JobCard  $jobCard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobCard $jobCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobCard  $jobCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobCard $jobCard)
    {
        //
    }
}
