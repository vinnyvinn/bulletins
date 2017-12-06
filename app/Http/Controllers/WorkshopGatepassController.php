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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $passes = WorkshopGatepass::when(! \request('status'), function ($builder) {
            return $builder->where('status', Constants::STATUS_PENDING);
        })
            ->when(\request('status'), function ($builder) {
                return $builder->where('status', \request('status'));
            })
            ->get([
                'id', 'job_card_id', 'type', 'supplier_name', 'external_service_id', 'created_at'
            ]);

        return Response::json([
            'gatepasses' => $passes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $jobCards = JobCard::open()
            ->whereHas('externalServices', function ($builder) {
                return $builder->where('status', Constants::STATUS_APPROVED);
            })
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
                'externalServices' => function ($builder) {
                    return $builder->where('status', Constants::STATUS_APPROVED)
                        ->select(['job_card_id', 'id', 'type', 'raw', 'vendor_id', 'approximate_cost']);
                }
            ])
            ->whereDoesntHave('gatepass', function ($builder) {
                return $builder->where('status', Constants::STATUS_OPEN);
            })
            ->get();

        $jobCards = $jobCards->map(function ($card) {
            $external = $card->externalServices->map(function ($service) {
                $service->raw = json_decode($service->raw);

                return $service;
            });
            unset($card->externalServices);
            $card->externalServices = $external;

            return $card;
        });


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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $parts = json_encode([]);
        
        if (isset($data['parts'])) {
            $parts = json_encode($data['parts']);
        }

        $data['parts'] = $parts;

        if (! $data['remarks']) {
            $data['remarks'] = '';
        }
        if (! $data['fuel_reading']) {
            $data['fuel_reading'] = 0;
        }
        if (! $data['km_reading']) {
            $data['km_reading'] = 0;
        }

        $data['status'] = Constants::STATUS_PENDING;

        WorkshopGatepass::create($data);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully processed external gatepass'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $passId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($passId)
    {
        $pass = WorkshopGatepass::findOrFail($passId);

        $jobCards = JobCard::open()
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
                'externalServices' => function ($builder) {
                    return $builder->where('status', Constants::STATUS_APPROVED)
                        ->select(['job_card_id', 'id', 'type', 'raw', 'approximate_cost']);
                }
            ])
            ->whereDoesntHave('gatepass', function ($builder) {
                return $builder->where('status', Constants::STATUS_OPEN);
            })
            ->where('id', $pass->job_card_id)
            ->get();

        $jobCards = $jobCards->map(function ($card) {
            $external = $card->externalServices->map(function ($service) {
                $service->raw = json_decode($service->raw);

                return $service;
            });
            unset($card->externalServices);
            $card->externalServices = $external;

            return $card;
        });


        return Response::json([
            'job_cards' => $jobCards,
            'drivers' => Driver::all(['id', 'first_name', 'last_name']),
            'suppliers' => Vendor::all(['DCLink', 'Account', 'Name']),
            'gatepass' => $pass,
        ]);
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

    public function approve($passId)
    {
        WorkshopGatepass::where('id', $passId)->update([
            'status' => Constants::STATUS_APPROVED
        ]);

        return \Response::json([
            'status' => 'success',
            'message' => 'Successfully approved gate pass'
        ]);
    }

    public function disapprove($passId)
    {
        WorkshopGatepass::where('id', $passId)->update([
            'status' => Constants::STATUS_DECLINED
        ]);

        return \Response::json([
            'status' => 'success',
            'message' => 'Successfully disapproved gate pass'
        ]);
    }

    public function printPass($passId)
    {
        $pass = WorkshopGatepass::with(['service.jobCard', 'driver'])->findOrFail($passId);
        $pass->parts = json_decode($pass->parts);
        $pass->total = 1;
        if ($pass->type == 'Parts') {
            $pass->total = array_reduce($pass->parts, function ($prev, $curr) {
                return $prev + $curr->quantity;
            }, 0);
        }

        $printout = view('printouts.workshop-gatepass')->with('pass', $pass)->render();

        return Response::json([
            'message' => 'Successfully completed loading.',
            'shouldPrint' => true,
            'printout' => $printout
        ]);
    }
}
