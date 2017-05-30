<?php

namespace SmoDav\Controllers\API;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Response;
use SmoDav\Factory\TruckFactory;
use SmoDav\Models\JobCard;
use SmoDav\Models\JobCardInspection;
use SmoDav\Models\JobCardTask;
use SmoDav\Models\Requisition;
use SmoDav\Models\WorkshopEmployee;
use SmoDav\Models\WorkshopInspectionCheckList;
use SmoDav\Models\WorkshopJobType;
use SmoDav\Support\Constants;

class JobCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $jobCards = JobCard::own()->with(['type' => function ($builder) {
            return $builder->select(['id', 'name']);
        }])
            ->get([
                'id', 'job_description', 'vehicle_number', 'created_at', 'expected_completion',
                'workshop_job_type_id', 'status'
            ]);

        $requisitions = Requisition::own()
            ->with(['jobCard' => function ($builder) {
                return $builder->select(['id', 'vehicle_number']);
            }])
            ->get([
                'id', 'job_card_id', 'created_at', 'status'
            ]);

        return Response::json([
            'cards' => $jobCards,
            'requisitions' => $requisitions,
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

        return Response::json([
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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->all();
            $data['raw_data'] = json_encode($data);
            $data['user_id'] = Auth::id();
            $data['time_in'] = Carbon::now()->setTimeFromTimeString($data['time_in']);
            $data['has_trailer'] = false;
            $data['status'] = Constants::STATUS_PENDING;
            $jobCard = JobCard::create($data);

            foreach ($data['inspections'] as $inspection) {
                $inspection['job_card_id'] = $jobCard->id;
                JobCardInspection::create($inspection);
            }

            foreach ($data['tasks'] as $task) {
                $task['job_card_id'] = $jobCard->id;
                $task['start_time'] = Carbon::parse($task['start_date'] . ' ' . $task['start_time']);

                JobCardTask::create($task);
            }
        });

        return Response::json([
            'message' => 'Successfully created new job card.',
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id  $jobCard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $card = JobCard::with(['user'])->findOrFail($id);
        $card->raw_data = json_decode($card->raw_data);

        return Response::json([
            'card' => $card,
        ]);
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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, JobCard $jobCard)
    {
        DB::transaction(function () use ($request, $jobCard) {
            $data = $request->all();
            $data['raw_data'] = json_encode($data);
            $data['user_id'] = Auth::id();
            $data['time_in'] = Carbon::now()->setTimeFromTimeString($data['time_in']);
            $data['has_trailer'] = false;
            $data['status'] = Constants::STATUS_PENDING;
            $jobCard->update($data);

            JobCardInspection::where('job_card_id', $jobCard->id)->delete();

            foreach ($data['inspections'] as $inspection) {
                $inspection['job_card_id'] = $jobCard->id;
                JobCardInspection::create($inspection);
            }

            JobCardTask::where('job_card_id', $jobCard->id)->delete();
            foreach ($data['tasks'] as $task) {
                $task['job_card_id'] = $jobCard->id;
                $task['start_time'] = Carbon::parse($task['start_date'] . ' ' . $task['start_time']);

                JobCardTask::create($task);
            }
        });

        return Response::json([
            'message' => 'Successfully updated job card.',
            'success' => true
        ]);
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

    public function approveJobCard($id)
    {
        JobCard::where('id', $id)->update([
            'status' => Constants::STATUS_APPROVED
        ]);

        return Response::json([
            'success' => 'true',
            'message' => 'Successfully approved job card.'
        ]);
    }

    public function disapproveJobCard($id)
    {
        JobCard::where('id', $id)->update([
            'status' => Constants::STATUS_DECLINED
        ]);

        return Response::json([
            'success' => 'true',
            'message' => 'Successfully declined job card.'
        ]);
    }
}
