<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Contract;
use App\Driver;
use App\Http\Requests\TruckRequest;
use App\Http\Requests\UploadRequest;
use App\Support\Core;
use App\Trailer;
use App\Truck;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use function intval;
use function json_encode;
use Response;
use SmoDav\Factory\TruckFactory;
use SmoDav\Support\Excel;
use function str_replace;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
            'trucks' => TruckFactory::all()
        ]);
    }

    public function create()
    {
        if (request('truck_id')) {
            $assigned = TruckFactory::allAssignedDrivers(request('truck_id'))->toArray();


            return Response::json([
                'drivers' => Driver::whereNotIn('id', $assigned)->get(['id', 'first_name', 'last_name']),
                'trailers' => Trailer::whereNull('truck_id')
                    ->orWhere('truck_id', request('truck_id'))
                    ->get(['id', 'trailer_number']),
            ]);
        }

        return Response::json([
            'drivers' => Driver::unassigned()->get(['id', 'name']),
            'trailers' => Trailer::unassigned()->get(['id', 'trailer_number']),
        ]);
    }

    public function store(TruckRequest $request)
    {
        $data = $request->all();
        foreach ($request->all() as $key=>$item) {
            if ($request->hasFile($key)){
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $data[$key] = $filename;
            }
        }
        TruckFactory::create($data);

        return Response::json([
            'message' => 'Successfully added new truck.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     *
     */
    public function show($id)
    {
        return Response::json([
            'truck' => TruckFactory::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TruckRequest $request
     * @param                           $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(TruckRequest $request, $id)
    {
        $data = $request->all();

        foreach ($request->all() as $key => $item) {
            $data[$key] = $item;
            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $data[$key] = $filename;
            }
        }

        TruckFactory::update($data, $id);

        return Response::json([
            'message' => 'Successfully updated truck.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Truck  $truck
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(Truck $truck)
    {
        $truck->delete();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted truck',
            'trucks' => TruckFactory::all()
        ]);
    }

    public function progress(Request $request, $id)
    {
        $truck = TruckFactory::findOrFail($id);
        $data = $request->all();
        $data['driver_id'] = $truck->driver_id;
        $data['truck_id'] = $truck->id;
        $data['truck_id'] = $truck->id;
        $data['type'] = Checklist::class;
        $data['from_station'] = $truck->contract->route->source;
        $data['to_station'] = $truck->contract->route->destination;
        $data['fields'] = json_encode($data['items']);
        $data['inspector_id'] = 1;
        $data['supervisor_id'] = 1;
        $data['suitable_for_loading'] = $data['suitable_for_loading'] == 1;

        Checklist::create($data);

        $nextStep = Core::nextStep($truck->location);

        if ($nextStep == Core::IN_YARD) {
            TruckFactory::createBilling($truck);
        }

        $truck->update(['location' => $nextStep]);

        return Response::json([
            'message' => 'Successfully moved to stage ' . $nextStep,
            'trucks' => TruckFactory::assigned()
        ]);
    }

    public function getAtStage($id)
    {
        return Response::json([
            'trucks' => TruckFactory::atLocation($id)
        ]);
    }

    public function import(UploadRequest $request)
    {
        $file = $request->file('uploaded_file');
        if (! Excel::validateExcel($file)) {
            return Response::json([
                'status' => 'error',
                'message' => 'Please select a valid import file of type XLS or XLSX.'
            ]);
        }

        $trucks = TruckFactory::withTrash(['plate_number'])->map(function ($truck) {
            return $truck->plate_number;
        })->toArray();

        $rows = Excel::prepare($file)
            ->usingHeaders([
                'plate_number', 'make', 'model', 'max_load'
            ])
            ->whenNull(Excel::EXCLUDE_ROW)
            ->includeColumns([
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ])
            ->excludeRows('plate_number', $trucks)
            ->get();

        $rows = collect($rows)->keyBy('plate_number')->values()->toArray();

        $rows = array_map(function ($row) {
            $row['max_load'] = intval(str_replace(',', '', $row['max_load']));

            return $row;
        }, $rows);

        try {
            foreach ($rows as $row) {
                TruckFactory::create($row);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return Response::json([
                'status' => 'error',
                'message' => 'Please use the sample file format provided and fill all the required fields.'
            ]);
        }

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully imported trucks.',
            'trucks' => TruckFactory::all()
        ]);
    }
}
