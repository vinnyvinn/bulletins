<?php

namespace App\Http\Controllers;

use App\JobCardQC;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\JobCard;
use SmoDav\Support\Constants;

class JobCardQCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (\request('status')) {
            $cards = JobCardQC::where('status', \request('status'))
                ->with(['jobCard' => function ($builder) {
                    return $builder->select('id', 'vehicle_number');
                }])
                ->get(['id', 'job_card_id', 'status', 'created_at']);

            return Response::json([
                'cards' => $cards
            ]);
        }

        $jobCards = JobCard::closed()
            ->with(['type' => function ($builder) {
                return $builder->select(['id', 'name']);
            }])
            ->when(! \Auth::user()->has('approve-job-card'), function ($builder) {
                return $builder->own();
            })
            ->doesntHave('qualityCheck')
            ->get([
                'id', 'job_description', 'vehicle_number', 'created_at', 'expected_completion',
                'workshop_job_type_id', 'status', 'breakdown_id'
            ]);

        return Response::json([
            'cards' => $jobCards,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $data['tasks'] = json_encode($data['tasks']);
        $data['status'] = Constants::STATUS_PENDING;
        if (! $data['remarks']) {
            $data['remarks'] = '';
        }

        JobCardQC::create($data);

        return \Response::json([
            'status' => 'success',
            'message' => 'Successfully process QC'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $jobCardQC
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($jobCardQC)
    {
        $jobCardQC = JobCardQC::find($jobCardQC);
        $jobCardQC->tasks = json_decode($jobCardQC->tasks);

        return \Response::json([
            'card' => $jobCardQC
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobCardQC  $jobCardQC
     * @return \Illuminate\Http\Response
     */
    public function edit(JobCardQC $jobCardQC)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JobCardQC  $jobCardQC
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobCardQC $jobCardQC)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobCardQC  $jobCardQC
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobCardQC $jobCardQC)
    {
        //
    }

    public function approve($jobCardQc)
    {
        JobCardQC::where('id', $jobCardQc)->update([
            'status' => Constants::STATUS_APPROVED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully approved quality check'
        ]);
    }

    public function disapprove($jobCardQc)
    {
        JobCardQC::where('id', $jobCardQc)->update([
            'status' => Constants::STATUS_DECLINED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully disapproved quality check'
        ]);
    }

    public function waiver($jobCardQc)
    {
        JobCardQC::where('id', $jobCardQc)->update([
            'status' => Constants::STATUS_WAIVERED
        ]);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully waivered quality check'
        ]);
    }
}
