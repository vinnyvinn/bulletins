<?php

namespace App\Http\Controllers;

use App\Employee;
use App\JobCardProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JobCardProgressController extends Controller
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
        $updates = JobCardProgress::with(['employee' => function ($builder) {
            return $builder->select(['id', 'first_name', 'last_name']);
        }])->where('job_card_id', request('id'))->get([
            'update', 'job_status', 'job_depends_on', 'employee_id', 'created_at'
        ])->map(function ($update) {
            $update->created = Carbon::parse($update->created_at)->format('d F Y');

            return $update;
        });

        return \Response::json([
            'employees' => Employee::where('category', 'Mechanics')->get(['id', 'first_name', 'last_name']),
            'updates' => $updates
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = \Auth::id();
        JobCardProgress::create($data);

        return \Response::json([
            'status' => 'success',
            'message' => 'Successfully posted update.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JobCardProgress  $jobCardProgress
     * @return \Illuminate\Http\Response
     */
    public function show(JobCardProgress $jobCardProgress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobCardProgress  $jobCardProgress
     * @return \Illuminate\Http\Response
     */
    public function edit(JobCardProgress $jobCardProgress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JobCardProgress  $jobCardProgress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobCardProgress $jobCardProgress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobCardProgress  $jobCardProgress
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobCardProgress $jobCardProgress)
    {
        //
    }
}
