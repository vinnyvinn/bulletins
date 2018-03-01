<?php

namespace SmoDav\Controllers\API;

use App\Driver;
use App\Employee;
use App\Http\Controllers\Controller;
use App\JobCardQC;
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
    public function index(Request $request)
    {
        
        $jobCards = JobCard::where('station_id',$request->station)->with(['type' => function ($builder) {
            return $builder->select(['id', 'name']);
        }])
            ->when(! \Auth::user()->has('view-job-card'), function ($builder) {
                return $builder->own();
            })
            ->when(! \request('status'), function ($builder) {
                return $builder->where('status', 'Pending Approval');
            })
            ->when(\request('status'), function ($builder) {
                return $builder->where('status', \request('status'));
            })
            ->get([
                'id', 'job_description', 'vehicle_number','raw_data', 'created_at', 'expected_completion',
                'workshop_job_type_id', 'status', 'breakdown_id'
            ]);

        foreach ($jobCards as $key=>$card){
            //get driver details and contact if there is
            $driver_name = '';
            $driver_contact = '';
            $decoded = json_decode($card['raw_data']);
            if(property_exists($decoded ,"driver_id")){
                //find the driver
                $driver = Driver::where('id', $decoded->driver_id)->first();
                if($driver){
                    $driver_name = $driver->first_name. " ".$driver->last_name;
                    $driver_contact = $driver->mobile_phone;
                }

            }
           $card->driver_name = $driver_name;
           $card->driver_contact = $driver_contact;

           //remove rawdata
           unset($card->raw_data, $card);
        }

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
            //'employees' => Employee::where('category', '=','Mechanics')->get(['id', 'first_name', 'last_name']),
            'employees' => Employee::orderBy('first_name', 'ASC')->get(['id', 'first_name', 'last_name']),
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
            $data['station_id'] = (int)$request->input('station_id');

            $jobCard = JobCard::create($data);

            foreach ($data['inspections'] as $inspection) {
                $inspection['job_card_id'] = $jobCard->id;
                JobCardInspection::create($inspection);
            }

            foreach ($data['tasks'] as $task) {
                $task['job_card_id'] = $jobCard->id;
                $task['task_name']=$task["task_name"];
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
        $closed = false;
        $card = JobCardQC::where('job_card_id',$id)
            ->where(function($q) {
                $q->where('status', Constants::STATUS_APPROVED)
                    ->orWhere('status', Constants::STATUS_DECLINED)
                    ->orWhere('status', Constants::STATUS_WAIVERED);
            })
            ->first();
        if($card){
            JobCard::where('id', $id)->update([
                'status' => Constants::STATUS_CLOSED,
                'closing_remarks' => $request->get('closing_remarks')
            ]);
            $closed = true;
        }

        return Response::json([
            'success' => $closed,
            'message' => ($closed)?'Successfully closed job card.':'JobCard requires quality check before closing'
        ]);
    }

    public function qcCheck(Request $request, $id){

        $card = JobCardQC::where('job_card_id',$id)
            ->where(function($q) {
                $q->where('status', Constants::STATUS_APPROVED)
                    ->orWhere('status', Constants::STATUS_DECLINED)
                    ->orWhere('status', Constants::STATUS_WAIVERED);
            })
            ->first();

        return Response::json([
            'status' => ($card)?true:false,
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

        if(property_exists($card->raw_data ,"driver_id")){
            //if driver is assigned
            $card->driver = Driver::where('id', $card->raw_data->driver_id)->first();
        }
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
