<?php

namespace App\Http\Controllers;

use App\ExternalService;
use App\ExternalServiceParts;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\JobCard;
use SmoDav\Support\Constants;

class ExternalServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $services = ExternalService::with([
            'jobCard' => function ($builder) {
                return $builder->select('id', 'vehicle_number');
            }
        ])
            ->when(! \request('status'), function ($builder) {
                return $builder->where('status', Constants::STATUS_PENDING);
            })
            ->when(\request('status'), function ($builder) {
                return $builder->where('status', \request('status'));
            })
            ->get();

        return \Response::json([
            'services' => $services
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function create()
    {

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
        if (! $data['mechanic_findings']) {
            $data['mechanic_findings'] = '';
        }

        if ($data['type'] == ExternalService::TYPE_VEHICLE) {
            unset($data['lines']);
            $data['raw'] = json_encode($data);
            ExternalService::create($data);

            return Response::json([
                'status' => 'success',
                'message' => 'Successfully processed external service.',
            ]);
        }

        $data['raw'] = json_encode($data);
        DB::transaction(function () use ($data) {
            $service = ExternalService::create($data);
            $toInsert = [];
            $now = Carbon::now();

            foreach ($data['lines'] as $line) {
                $toInsert [] = [
                    'external_service_id' => $service->id,
                    'item_id' => $line['item_id'],
                    'item_name' => $line['item_name'],
                    'item_description' => '',
                    'item_code' => '',
                    'quantity' => $line['quantity'],
                    'location' => '',
                    'status' => '',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            ExternalServiceParts::insert($toInsert);
        });

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully processed external service.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExternalService  $externalService
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($externalServiceId)
    {
        $external = ExternalService::findOrFail($externalServiceId);
        $external->raw = json_decode($external->raw);

        return \Response::json([
            'service' => $external
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExternalService  $externalService
     * @return \Illuminate\Http\Response
     */
    public function edit(ExternalService $externalService)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $externalServiceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $externalServiceId)
    {
        $service = ExternalService::findOrFail($externalServiceId);
        $data = $request->all();
        $data['user_id'] = \Auth::id();
        if (! $data['mechanic_findings']) {
            $data['mechanic_findings'] = '';
        }

        DB::transaction(function () use ($data, $service) {
            if ($service->type == ExternalService::TYPE_PARTS) {
                ExternalServiceParts::where('external_service_id', $service->id)->delete();
            }

            if ($data['type'] == ExternalService::TYPE_VEHICLE) {
                unset($data['lines']);
                $data['raw'] = json_encode($data);
                $service->update($data);

                return;
            }

            $data['raw'] = json_encode($data);

            $service->update($data);
            $toInsert = [];
            $now = Carbon::now();

            foreach ($data['lines'] as $line) {
                $toInsert [] = [
                    'external_service_id' => $service->id,
                    'item_id' => $line['item_id'],
                    'item_name' => $line['item_name'],
                    'item_description' => '',
                    'item_code' => '',
                    'quantity' => $line['quantity'],
                    'location' => '',
                    'status' => '',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            ExternalServiceParts::insert($toInsert);
        });

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully updated external service.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExternalService  $externalService
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExternalService $externalService)
    {
        //
    }

    public function approve($serviceId)
    {
        ExternalService::where('id', $serviceId)->update([
            'status' => Constants::STATUS_APPROVED
        ]);

        return \Response::json([
            'status' => 'success',
            'message' => 'Successfully approved external service'
        ]);
    }

    public function disapprove($serviceId)
    {
        ExternalService::where('id', $serviceId)->update([
            'status' => Constants::STATUS_DECLINED
        ]);

        return \Response::json([
            'status' => 'success',
            'message' => 'Successfully disapproved external service'
        ]);
    }

    public function close($serviceId)
    {
        ExternalService::where('id', $serviceId)->update([
            'status' => Constants::STATUS_CLOSED
        ]);

        return \Response::json([
            'status' => 'success',
            'message' => 'Successfully closed external service'
        ]);
    }
}
