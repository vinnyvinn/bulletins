<?php

namespace SmoDav\Controllers\API;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Truck;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Response;
use SmoDav\Factory\TruckFactory;
use SmoDav\Factory\VehicleFactory;
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
        $jobCards = JobCard::with(['type' => function ($builder) {
            return $builder->select(['id', 'name']);
        }])
            ->when(! \Auth::user()->has('approve-job-card'), function ($builder) {
                return $builder->own();
            })
            ->when(! \request('status'), function ($builder) {
                return $builder->where('status', 'Pending Approval');
            })
            ->when(\request('status'), function ($builder) {
                return $builder->where('status', \request('status'));
            })
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $trucks = VehicleFactory::all();

        return Response::json([
            'vehicles' => $trucks,
            'job_types' => WorkshopJobType::with(['operations.tasks'])->get(['id', 'name', 'service_type']),
            'checklist' => WorkshopInspectionCheckList::all(['name', 'id']),
            'employees' => Employee::where('category', '=','Mechanics')->get(['id', 'first_name', 'last_name']),
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
        DB::transaction(function () use ($request) {
            $data = $request->all();
            $data['raw_data'] = \json_encode($data);
            $data['user_id'] = Auth::id();
            $data['time_in'] = Carbon::now()->setTimeFromTimeString($data['time_in']);
            $data['has_trailer'] = false;
            $data['status'] = Constants::STATUS_PENDING;

            $jobCard = JobCard::create($data);

            foreach ($data['inspections'] as $inspection) {
                $inspection['job_card_id'] = $jobCard->id;
                JobCardInspection::create($inspection);
            }

           /* foreach ($data['tasks'] as $task) {
                $task['job_card_id'] = $jobCard->id;
                $task['start_time'] = Carbon::parse($task['start_date'] . ' ' . $task['start_time']);

                JobCardTask::create($task);
            }*/
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
        $card->raw_data = \json_decode($card->raw_data);

        return Response::json([
            'card' => $card,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\JobCard $jobCard
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(JobCard $jobCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\JobCard             $jobCard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, JobCard $jobCard)
    {
        DB::transaction(function () use ($request, $jobCard) {
            $data = $request->all();
            $jobCard->update([
                'raw_data' => \json_encode($data)
            ]);

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
     * @param \App\JobCard $jobCard
     *
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

    public function closeCard(Request $request, $id)
    {
        JobCard::where('id', $id)->update([
            'status' => Constants::STATUS_CLOSED,
            'closing_remarks' => $request->get('closing_remarks')
        ]);

        return Response::json([
            'success' => 'true',
            'message' => 'Successfully closed job card.'
        ]);
    }

    public function qcCheck(Request $request, $id){
        $card = JobCard::with('qualityCheck')->where('id', $id)->first();
        return Response::json([
            'status' => ($card->quality_check)?true:false
        ]);
    }

    public function truckDetails(Request $request, $reg_no){

        $make = Truck::get();
        return Response::json([
            'truck' => $make
        ]);
    }

    public function printCard($id)
    {
        $card = JobCard::with(['vehicle.make', 'vehicle.model', 'jobType'])->findOrFail($id);
        $card->raw_data = json_decode($card->raw_data);
        $employeeIds = [];

        foreach ($card->raw_data->tasks as $task) {
            if (! in_array($task->employee_id, $employeeIds)) {
                $employeeIds[] = $task->employee_id;
            }
        }

        $employees = Employee::whereIn('id', $employeeIds)->get(['id', 'first_name', 'last_name'])->keyBy('id');

        return view('printouts.jobcard')
            ->with('card', $card)
            ->with('employees', $employees);
    }
}
